<?php

namespace App\Http\Controllers\Admin\space;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\City;
use App\Models\Country;
use App\Models\FeatureCharge;
use App\Models\GlobalDay;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use App\Models\SpaceSubCategory;
use App\Models\State;
use App\Models\TimeSlot;
use App\Rules\ImageMimeTypeRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class SpaceController extends Controller
{
  public function settings(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $space_settings = Basic::select('tax', 'space_units', 'fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental', 'admin_profile')->first();
    return view('admin.space-management.space.settings', compact('language', 'space_settings'));
  }

  public function settingsUpdate(Request $request)
  {
    $packages = Package::all();
    // Define a function to check feature usage
    $checkFeatureUsage = function ($featureName) use ($packages) {
      $packageIds = $packages->filter(function ($package) use ($featureName) {
        $features = json_decode($package->package_feature, true);
        return !empty($features) && in_array($featureName, $features);
      })->pluck('id')->toArray();

      return Membership::whereIn('package_id', $packageIds)->exists();
    };

    // Check features based on request input
    if ($request->fixed_time_slot_rental == 0 && $checkFeatureUsage('Fixed Timeslot Rental')) {
      session()->flash('warning', __('You cannot disable Fixed Timeslot Rental feature') . ' ' . __('because it is used in a package bought by a vendor') . '.');
      return response()->json(['status' => 'warning'], 200);
    }

    if ($request->hourly_rental == 0 && $checkFeatureUsage('Hourly Rental')) {
      session()->flash('warning', __('You cannot disable Hourly Rental feature') . ' ' . __('because it is used in a package bought by a vendor') . '.');
      return response()->json(['status' => 'warning'], 200);
    }
    if ($request->multi_day_rental == 0 && $checkFeatureUsage('Multi Day Rental')) {
      session()->flash('warning', __('You cannot disable Multi Day Rental feature') . ' ' . __('because it is used in a package bought by a vendor') . '.');
      return response()->json(['status' => 'warning'], 200);
    }

    $rules = [
      'tax'                     => 'required',
      'space_units'             => 'required',
      'fixed_time_slot_rental'  => 'required',
      'hourly_rental'           => 'required',
      'multi_day_rental'        => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    // Custom validation to ensure at least one of the rentals is enabled
    $validator->after(function ($validator) use ($request) {
      if ($request->input('fixed_time_slot_rental') == 0 && $request->input('hourly_rental') == 0 && $request->input('multi_day_rental') == 0) {
        $validator->errors()->add('fixed_time_slot_rental', __('At least one of the rentals must be enabled') . ' (' . __('Fixed Timeslot Rental or Hourly Rental') . ')') . '.';

        $validator->errors()->add('fixed_time_slot_rental', __('At least one of the rentals must be enabled') . ' (' . __('Fixed Timeslot Rental or Hourly Rental') . ')' . '.');

        $validator->errors()->add('fixed_time_slot_rental', __('At least one of the rentals must be enabled') . ' (' . __('Fixed Timeslot Rental or Hourly Rental or Multi Day Rental') . ')' . '.');
      }
    });

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    $space_settings                         = Basic::first();

    $space_settings->tax                    = $request->tax;
    $space_settings->space_units            = $request->space_units;
    $space_settings->fixed_time_slot_rental = $request->fixed_time_slot_rental;
    $space_settings->hourly_rental          = $request->hourly_rental;
    $space_settings->multi_day_rental       = $request->multi_day_rental;
    $space_settings->save();

    Session::flash('success', __('Space settings update successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View|\Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $seller = $title = $s_space_type = $s_category = null;

    if ($request->filled('seller')) {
      $seller = $request->seller;
    }
    if ($request->filled('title')) {
      $title = $request->title;
    }
    if ($request->filled('category')) {
      $s_category = $request->category;
    }
    if ($request->filled('space_type')) {
      $s_space_type = $request->space_type;
    }
    $language = getAdminLanguage();
    $information['spaces'] = Space::query()
      ->select(
        'spaces.id',
        'spaces.thumbnail_image',
        'spaces.seller_id',
        'spaces.space_type',
        'spaces.is_featured',
        'spaces.space_status as status',
        'space_contents.title as space_title',
        'space_contents.address',
        'space_contents.slug',
        'space_contents.space_category_id',
        'space_contents.country_id',
        'space_contents.state_id',
        'space_contents.city_id',
        'space_contents.sub_category_id'
      )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->when($seller, function ($query) use ($seller) {
        if ($seller == 'admin') {
          $seller_id = 0;
        } else {
          $seller_id = $seller;
        }
        return $query->where('spaces.seller_id', '=', $seller_id);
      })
      ->when($title, function ($query) use ($title) {
        return $query->where('space_contents.title', 'like', '%' . $title . '%');
      })
      ->where([
        ['space_contents.language_id', '=', $language->id],
        ['space_contents.title', '!=', null],
      ])
      ->when($s_space_type, function ($query) use ($s_space_type) {
        return $query->where('spaces.space_type', '=', $s_space_type);
      })
      ->when($s_category, function ($query) use ($s_category) {
        return $query->where('space_contents.space_category_id', '=', $s_category);
      })
      ->orderByDesc('spaces.id')
      ->paginate(10);

    // Fetch the space category data and add it to the $information['spaces'] collection
    foreach ($information['spaces'] as $service) {
      $category = SpaceCategory::where('id', $service->space_category_id)
        ->where('language_id', $language->id)
        ->first();
      $service->category = $category ?? null;
    }

    $information['sellers']         = Seller::select('id', 'username')->where('id', '!=', 0)->get();
    $information['langs']           = Language::all();
    $information['featuredCharges'] = FeatureCharge::query()->get();
    $online                         = OnlineGateway::query()->where('status', 1)->get();
    $offline                        = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
    $information['offline']         = $offline;
    $information['payment_methods'] = $online->concat($offline);
    $stripe                         = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe_info                    = json_decode($stripe->information, true);
    $information['stripe_key']      = $stripe_info['key'];
    $information['categories'] = SpaceCategory::where([
      ['language_id', $language->id],
    ])->select('name', 'id', 'slug')->orderByDesc('id')->get();

    return view('admin.space-management.space.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return JsonResponse
   */
  public function getSpaceSubcategories(Request $request)
  {
    $subcategories = SpaceSubCategory::where([
      ['space_category_id', $request->category_id],
      ['status', 1],
    ])->get();
    return response()->json($subcategories);
  }

  public function sellerSelect()
  {
    $information['sellers'] = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->select('sellers.id', 'sellers.username')
      ->get();
    $information['spaceType'] = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();
    return view('admin.space-management.space.select-seller', $information);
  }

  public function spaceType(Request $request)
  {

    // get language form session
    $language  = getAdminLanguage();
    $seller_id = $request->input('seller_id');

    $features  = [];
    if ($seller_id != 0) {

      Seller::findOrFail($seller_id);
    }

    if ($seller_id != 0) {
      $hasMembership = SellerPermissionHelper::currentPackagePermission($seller_id);
      $existFeatures = json_decode($hasMembership->package_feature, true);
      $outputFeatureArray = [];

      foreach ($existFeatures as $value) {
        if ($value == "Fixed Timeslot Rental" || $value == "Hourly Rental" || $value == "Multi Day Rental") {
          $key = strtolower(str_replace(' ', '_', $value));
          $key = str_replace('timeslot', 'time_slot', $key);
          $outputFeatureArray[$key] = __($value);
        }
      }
      if (!is_null($hasMembership)) {
        $features = json_decode($hasMembership->package_feature, true);
      }
    } else {
      $outputFeatureArray = [
        "fixed_time_slot_rental" => __("Fixed Timeslot Rental"),
        "hourly_rental" => __("Hourly Rental"),
        "multi_day_rental" => __("Multi Day Rental"),
      ];
    }

    // Return the features as a JSON response
    return response()->json([
      'seller_id' => $seller_id,
      'features' => $features,
      'outputFeatureArray' => $outputFeatureArray,
    ]);

    if ($seller_id != 0) {
      $hasMembership = SellerPermissionHelper::currentPackagePermission($seller_id);
      if (!is_null($hasMembership)) {
        $features = json_decode($hasMembership->package_feature, true);
        if (isset($features) && !empty($features)) {
          $type = null;
          foreach ($features as $feature) {
            if ($feature == 'Hourly Rental') {
              $type = 'hourly_rental';
            } elseif ($feature == 'Fixed Timeslot Rental') {
              $type = 'fixed_time_slot_rental';
            } elseif ($feature == 'Multi Day Rental') {
              $type = 'multi_day_rental';
            } else {
              session()->flash('warning', __('There have no festure in the package'));
              return redirect()->route('admin.space_management.seller_select', ['language' => $language->code]);
            }
          }

          if (count($features) == 1) {
            return redirect()->route('admin.space_management.space.create', ['seller_id' => $seller_id, 'type' => $type, 'language' => $language->code]);
          } elseif (count($features) > 1) {
            $information['outputFeatureArray'] = $outputFeatureArray;
            return view('admin.space-management.space.space-select', $information);
          }
        } else {
          session()->flash('warning', __('There have no festure in the package'));
          return redirect()->route('admin.space_management.seller_select', ['language' => $language->code]);
        }
      } else {
        session()->flash('warning', __('There have no package for the vendor'));
        return redirect()->route('admin.space_management.seller_select', ['language' => $language->code]);
      }
    }
    $information['outputFeatureArray'] = $outputFeatureArray;
    

    return view('admin.space-management.space.space-select', $information,);
  }
  public function create(Request $request)
  {
    // get language form session
    $language = getAdminLanguage();

    if ($request->input('seller_id') == 'admin') {
      $seller_id = 0;
    } elseif ($request->input('seller_id') !== 'admin') {
      $seller_id = $request->input('seller_id');
      Seller::findOrFail($seller_id);
    }
    $basicSetting = Basic::select('hourly_rental', 'fixed_time_slot_rental', 'multi_day_rental')->first();

    if ($seller_id == 0) {
      $information['maxSliderImage'] = 999999;
      $information['numberOfAmenity'] = 999999;
      $information['sellerId'] = $seller_id;
      $information['languages'] = Language::all();
      $information['categories'] = SpaceCategory::where([
        ['status', 1],
        ['language_id', Language::where('is_default', 1)->first()->id],
      ])->orderByDesc('id')->get();
      $information['currencyInfo'] = $this->getCurrencyInfo();
    } else {
      $current_package = SellerPermissionHelper::currentPackagePermission($seller_id);

      if ($current_package == null) {
        Session::flash('warning', __('This vendor is not available'));
        return redirect()->route('admin.space_management.seller_select');
      }

      $languages = Language::all();
      $currentPackage                 = SellerPermissionHelper::currentPackagePermission($seller_id);
      $information['currentPackage']  = $currentPackage;
      $information['maxSliderImage']  = $currentPackage->number_of_slider_image_per_space;
      $information['numberOfAmenity'] = $currentPackage->number_of_amenities_per_space;
      $languages->map(function ($language) {
        $language['categories'] = $language->spaceCategory()->orderByDesc('id')->get();
      });

      $information['languages'] = $languages;
      $defaultLangId = Language::where('is_default', 1)->first();
      $information['categories'] = SpaceCategory::where([
        ['status', 1],
        ['language_id', $defaultLangId->id],
      ])->orderByDesc('id')->get();
      $information['currencyInfo'] = $this->getCurrencyInfo();
      $information['sellerId']     = $seller_id;
      $information['defaultLang']  = $defaultLangId;
    }

    //get the space type and check both space type true or false
    $matched = Space::checkSpaceType($request, $seller_id, $basicSetting);

    if (!$matched['matched']) {
      session()->flash('warning', __('Please select the space type'));
      return redirect()->route('admin.space_management.seller_select', ['language' => $language->code]);
    } else {
      $information['space_type'] = $matched['space_type'];
    }
    return view('admin.space-management.space.create', $information);
  }

  /**
   * Store a new slider image in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function uploadImage(Request $request)
  {
    $rule = [
      'slider_image' => new ImageMimeTypeRule()
    ];
    $validator = Validator::make($request->all(), $rule);
    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()
      ], 400);
    }
    $imageName = UploadFile::store('./assets/img/spaces/slider-images/', $request->file('slider_image'));
    return Response::json(['uniqueName' => $imageName], 200);
  }

  /**
   * Remove a slider image from storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function removeImage(Request $request)
  {
    $img = $request['imageName'];

    try {
      @unlink('assets/img/spaces/slider-images/' . $img);
      return Response::json(['success' => __('The image has been deleted') . '.'], 200);
    } catch (Exception $e) {
      return Response::json(['error' => __('Something went wrong') . '!'], 400);
    }
  }

  /**
   * Get subcategory of selected category.
   *
   * @param int $id
   * @return JsonResponse
   */

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse|RedirectResponse
  {

    //check admin's membership available or not ?
    $admin_package_check = Membership::where('seller_id', 0)->first();
    if (!$admin_package_check) {

      $admin_pacakge = new Package();
      $admin_pacakge->id = 999999;
      $admin_pacakge->price = 0;
      $admin_pacakge->term = 'lifetime';
      $admin_pacakge->save();

      Membership::create([
        'price' => $admin_pacakge->price,
        'status' => 1,
        'package_id' => $admin_pacakge->id,
        'seller_id' => 0,
        'start_date' => Carbon::now(),
        'expire_date' => Carbon::now()->addDays(99999),
      ]);
    }
    //check dummy admin's exist or not in sellers table
    $admin_seller_check = Seller::where('id', 0)->first();
    $admin = Admin::first();
    if (empty($admin_seller_check)) {
      $admin_seller = new Seller();
      $admin_seller->id = 0;
      $admin_seller->email = $admin->email;
      $admin_seller->recipient_mail = $admin->email;
      $admin_seller->username = $admin->username;
      $admin_seller->status = 1;
      $admin_seller->save();
      $admin_seller->id = 0;
      $admin_seller->save();
    }

    $messages       = [];
    // get language form session
    $language       = getAdminLanguage();
    $languageCodes  = Language::query()->select('code')->get()->pluck('code');
    $basicSetting   = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();

    // Check if seller_id is present in the request
    if ($request->has('seller_id')) {
      if ($request->seller_id === 'admin' || $request->seller_id === null) {
        $request['seller_id'] = 0;
      } else {
        $request['seller_id'] = $request->seller_id;
      }
    }
    $request['type'] = $request->space_type;

    $matched = Space::checkSpaceType($request, $request['seller_id'], $basicSetting);

    if (!$matched['matched']) {
      session()->flash('warning', __('Space type cannot be null') . '. ' . __('Please select a valid space type') . '.');
      return Response::json(['status' => 'success'], 200);
    } else {
      $type = $matched['space_type'];
    }

    // Determine available types based on settings
    $availableTypes = null;
    if ($basicSetting->fixed_time_slot_rental == 1 && $type == 'fixed_time_slot_rental') {
      $availableTypes = 1;
    } elseif ($basicSetting->hourly_rental == 1 && $type == 'hourly_rental') {
      $availableTypes = 2;
    } elseif ($basicSetting->multi_day_rental == 1 && $type == 'multi_day_rental') {
      $availableTypes = 3;
    }

    $rules = [
      'thumbnail_image'         => 'required|',
      'latitude'                => ['nullable', 'numeric', 'between:-90,90'],
      'longitude'               => ['nullable', 'numeric', 'between:-180,180'],
      'min_guest'               => 'required|numeric',
      'max_guest'               => 'required|numeric',
      'space_size'              => 'required',
      'booking_status'          => 'required',
      'book_a_tour'             => 'required',
    ];


    if ($basicSetting->hourly_rental == 1 && $type == 'hourly_rental') {
      $rules += [
        'prepare_time'           => 'required',
        'rent_per_hour'          => 'required|numeric',
        'opening_time'           => 'required',
        'closing_time'           => 'required',
        'similar_space_quantity'  => 'required|integer|min:1'
      ];
    }

    if ($basicSetting->multi_day_rental == 1 && $type == 'multi_day_rental') {
      $rules += [
        'price_per_day'           => 'required|numeric',
        'similar_space_quantity' => 'required|integer|min:1'
      ];
    }
    if ($basicSetting->fixed_time_slot_rental == 1 && $type == 'fixed_time_slot_rental') {
      $rules += [
        'use_slot_rent'           => 'required',
      ];
    }

    if ($basicSetting->fixed_time_slot_rental == 1 && $type == 'fixed_time_slot_rental' && $request->input('use_slot_rent') != 1) {
      $rules += [
        'space_rent'              => 'required|numeric',
      ];
    }

    // Use a more concise array syntax for adding language-specific rules

    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;

    foreach ($languageCodes as $code) {
      $languageName = Language::query()->where('code', '=', $code)->firstOrFail();

      // Always require fields for the default language
      if ($code === $defaultLanguageCode) {
        $rules[$code . '_title'] = 'required|string|max:255';
        $rules[$code . '_space_category_id'] = 'required|integer';
        $rules[$code . '_address'] = 'required|string|max:255';
        $rules[$code . '_city_id'] = 'required|integer';
        $rules[$code . '_amenities'] = 'required';
        $rules[$code . '_description'] = 'required|string';

        // Additional rules based on request
        if ($request->has('booking_status') && $request->booking_status == 1) {
          $rules[$code . '_quote_form_id'] = 'required|integer';
        }

        if ($request->has('book_a_tour') && $request->book_a_tour == 1) {
          $rules[$code . '_tour_form_id'] = 'required|integer';
        }
      } else {
        // For other languages, check if any field is filled
        if (
          $request->filled($code . '_title') ||
          $request->filled($code . '_space_category_id') ||
          $request->filled($code . '_address') ||
          $request->filled($code . '_city_id') ||
          $request->filled($code . '_amenities') ||
          $request->filled($code . '_meta_keyword') ||
          $request->filled($code . '_meta_description') ||
          $request->filled($code . '_description')
        ) {

          // If any field is filled, make all fields required
          $rules[$code . '_title'] = 'required|string|max:255';
          $rules[$code . '_space_category_id'] = 'required|integer';
          $rules[$code . '_address'] = 'required|string|max:255';
          $rules[$code . '_city_id'] = 'required|integer';
          $rules[$code . '_amenities'] = 'required';
          $rules[$code . '_description'] = 'required|string';

          // Additional rules based on request
          if ($request->has('booking_status') && $request->booking_status == 1) {
            $rules[$code . '_quote_form_id'] = 'required|integer';
          }

          if ($request->has('book_a_tour') && $request->book_a_tour == 1) {
            $rules[$code . '_tour_form_id'] = 'required|integer';
          }
        }
      }

      // Dynamic country/state validation
      $country_id = $request->input($code . '_country_id');
      $state_id = $request->input($code . '_state_id');
      $lang_id = Language::where('code', $code)->first();

      // Get country data from the database to apply validation condition
      $countries = Country::where('language_id', $lang_id->id)->get();
      if ($countries->isNotEmpty()) {
        if ($code === $defaultLanguageCode || $request->filled($code . '_country_id')) {
          $rules[$code . '_country_id'] = 'required|integer';
        }
      }

      if ($country_id) {
        $statesExist = State::where([
          ['country_id', $country_id],
          ['language_id', $lang_id->id],
        ])->exists();
        if ($statesExist) {
          if ($code === $defaultLanguageCode || $request->filled($code . '_state_id')) {
            $rules[$code . '_state_id'] = 'required|integer';
          }
        }
        if ($code === $defaultLanguageCode || $request->filled($code . '_city_id')) {
          $rules[$code . '_city_id'] = 'required|integer';
        }
      }

      if ($state_id) {
        $cityExist = City::where([
          ['state_id', $state_id],
          ['language_id', $lang_id->id],
        ])->exists();
        if ($cityExist) {
          if ($code === $defaultLanguageCode || $request->filled($code . '_city_id')) {
            $rules[$code . '_city_id'] = 'required|integer';
          }
        }
      }

      // Custom error messages
      $messages[$code . '_space_category_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('space category') . ' ' . __('field is required') . '.';
      $messages[$code . '_address.required'] = __('The') . ' ' . $languageName->name . ' ' . __('address') . ' ' . __('field is required') . '.';
      $messages[$code . '_title.required'] = __('The') . ' ' . $languageName->name . ' ' . __('Title') . ' ' . __('field is required') . '.';
      $messages[$code . '_description.required'] = __('The') . ' ' . $languageName->name . ' ' . __('Description') . ' ' . __('field is required') . '.';
      $messages[$code . '_amenities.required'] = __('Please select the amenities in') . ' ' . $languageName->name . '.';
      $messages[$code . '_space_category_id.required'] = __('Please select a space category in') . ' ' . $languageName->name . '.';
      $messages[$code . '_country_id.required'] = __('Please select a country in') . ' ' . $languageName->name . '.';
      $messages[$code . '_state_id.required'] = __('Selecting a state in') . ' ' . $languageName->name . '.';
      $messages[$code . '_city_id.required'] = __('Please select a city in') . ' ' . $languageName->name . '.';
      $messages[$code . '_quote_form_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('quote form') . ' ' . __('field is required') . '.';
      $messages[$code . '_tour_form_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('tour request form') . ' ' . __('field is required') . '.';
    }


    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store thumbnail image in storage
    $thumbnailImage = UploadFile::store('./assets/img/spaces/thumbnail-images/', $request->file('thumbnail_image'));

    $sliderArr = [];
    foreach ($request['slider_images'] as $image) {
      if (file_exists(public_path('assets/img/spaces/slider-images/' . $image))) {
        $sliderArr[] = $image;
      }
    }

    $similarSpaceQuantity = $request->similar_space_quantity ?? 1;

    $admintTimezone = now()->timezoneName;

    $space = Space::query()->create($request->except('thumbnail_image', 'opening_time', 'closing_time', 'slider_images', 'seller_id', 'space_type') + [
      'thumbnail_image' => $thumbnailImage,
      'slider_images'   => json_encode($sliderArr),
      'seller_id'       => $request['seller_id'],
      'space_type'      => $availableTypes,
      'opening_time'    => Carbon::parse($request->opening_time, $admintTimezone)->format('H:i'),
      'closing_time'    => Carbon::parse($request->closing_time, $admintTimezone)->format('H:i'),
      'similar_space_quantity' => $similarSpaceQuantity,

    ]);

    foreach ($languageCodes as $code) {
      $spaceContent                       = new SpaceContent();
      $spaceContent->language_id          = Language::query()->where('code', '=', $code)->firstOrFail()->id;
      $spaceContent->space_id             = $space->id;
      $spaceContent->space_category_id    = $request[$code . '_space_category_id'];
      $spaceContent->sub_category_id      = $request[$code . '_subcategory_id'];
      $spaceContent->title                = $request[$code . '_title'];
      $spaceContent->slug                 = createSlug($request[$code . '_title']);
      $spaceContent->get_quote_form_id    = $request[$code . '_quote_form_id'];
      $spaceContent->tour_request_form_id = $request[$code . '_tour_form_id'];
      $spaceContent->address              = $request[$code . '_address'];
      $spaceContent->description          = Purifier::clean($request[$code . '_description'], 'youtube');
      $spaceContent->amenities            = json_encode($request[$code . '_amenities']);
      $spaceContent->meta_keywords        = $request[$code . '_meta_keywords'];
      $spaceContent->meta_description     = $request[$code . '_meta_description'];

      // Update or create the country, state, and city relationships if they exist
      if ($request->has($code . '_country_id')) {
        $spaceContent->country_id = $request->input($code . '_country_id');
      } else {
        $spaceContent->country_id = null;
      }
      if ($request->has($code . '_state_id')) {
        $spaceContent->state_id = $request->input($code . '_state_id');
      } else {
        $spaceContent->state_id = null;
      }
      if ($request->has($code . '_city_id')) {
        $spaceContent->city_id = $request->input($code . '_city_id');
      } else {
        $spaceContent->city_id = null;
      }
      $spaceContent->save();
    }

    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    foreach ($days as $key => $name) {
      $day       = new GlobalDay();
      $day->name = $name;
      if ($request->has('seller_id')) {
        $day->seller_id = $request->seller_id;
      } else {
        $day->seller_id = 0;
      }
      $day->space_id      = $space->id;
      $day->order         = $key;
      $day->start_of_week = $key;
      $day->save();
    }
    $request->session()->flash('success', __('New Space added successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Update the 'featured' status of a specified resource.
   *
   * @param Request $request
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateFeaturedStatus(Request $request, $id)
  {
    $service = Space::query()->find($id);

    if ($request['is_featured'] == 'yes') {
      $service->update([
        'is_featured' => 'yes'
      ]);
      $request->session()->flash('success', __('Space is now Featured Successfully') . '.');
    } else {
      $service->update([
        'is_featured' => 'no'
      ]);
      $request->session()->flash('success', __('Space Feature Deactivated Successfully') . '.');
    }
    return redirect()->back();
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return Application|Factory|View
   */
  public function edit($id)
  {
   
    $space = Space::query()->findOrFail($id);


    // Check if the authenticated user has permission to edit this space
    $user = auth()->guard('admin')->user();

    $sessionLang = getAdminLanguage();
    $information['space_title'] = SpaceContent::getSpaceTitle($id, $sessionLang);

    $information['service'] = $space;
    if ($space->seller_id == 0) {
      $information['maxSliderImage']  = 999999;
      $information['numberOfAmenity'] = 999999;
      $information['sellerId']        = $space->seller_id;
      $information['sliderImages']    = json_decode($space->slider_images);
    } else {
      $currentPackage                = SellerPermissionHelper::currentPackagePermission($space->seller_id);
      $information['currentPackage'] = $currentPackage;
      $information['sellerId']       = $space->seller_id;
      $sliderImages                  = json_decode($space->slider_images);
      $information['sliderImages']   = $sliderImages;
      if ($currentPackage) {
        if ($sliderImages) {
          $information['maxSliderImage'] = $currentPackage->number_of_slider_image_per_space - count($sliderImages);
        }
      }
    }
   
    $languages = Language::all();
    $languages->map(function ($language) use ($space) {
      $language['serviceData'] = $language->spaceContent()->where('space_id', $space->id)->first();
      // get all the forms of each language from db
      $language['forms']       = $language->form()->orderByDesc('id')->get();
    });
    $information['languages']  = $languages;
    $information['address']    = $space->address;
    $information['categories'] = SpaceCategory::where('status', 1)->orderByDesc('id')->get();

    return view('admin.space-management.space.edit', $information);
  }

  /**
   * Remove 'stored' slider image form storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function detachImage(Request $request)
  {
    $id = $request['id'];
    $key = $request['key'];
    $space = Space::query()->find($id);
    $sliderImages = json_decode($space->slider_images);

    if (count($sliderImages) == 1) {
      return Response::json(['message' => __('Sorry') . ', ' . __('the last image cannot be delete') . '.'], 400);
    } else {
      $image = $sliderImages[$key];
      @unlink(public_path('assets/img/spaces/slider-images/' . $image));
      array_splice($sliderImages, $key, 1);
      $space->update([
        'slider_images' => json_encode($sliderImages)
      ]);

      return Response::json(['message' => __('Slider image removed successfully') . '!'], 200);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(Request $request, $id)
  {

    $spaceType = Space::where('id', $id)->select('space_type')->firstOrFail();
    $messages  = [];
    $languageCodes = Language::query()->select('code')->get()->pluck('code');
    $rules = [
      'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
      'longitude'      => ['nullable', 'numeric', 'between:-180,180'],
      'min_guest'      => 'required|integer',
      'max_guest'      => 'required|integer',
      'space_size'     => 'required|numeric',
      'booking_status' => 'nullable',
      'book_a_tour'    => 'nullable',

    ];
    if (isset($spaceType) && !empty($spaceType->space_type) && $spaceType->space_type == 2) {
      $rules += [
        'prepare_time'           => 'required',
        'rent_per_hour'          => 'required|numeric',
        'opening_time'           => 'required',
        'closing_time'           => 'required',
        'similar_space_quantity'  => 'required|integer|min:1'
      ];
    }

    if (isset($spaceType) && !empty($spaceType->space_type) && $spaceType->space_type == 3) {
      $rules += [
        'price_per_day'          => 'required|numeric',
        'similar_space_quantity' => 'required|integer|min:1'
      ];
    }

    if (isset($spaceType)  && !empty($spaceType->space_type) && $spaceType->space_type == 1 && $request->input('use_slot_rent') != 1) {
      $rules += [
        'space_rent'              => 'required|numeric',
      ];
    }

    // Use a more concise array syntax for adding language-specific rules

    $langForValidation = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;
    foreach ($languageCodes as $code) {
      $languageName = Language::query()->where('code', '=', $code)->firstOrFail();

      // Always require fields for the default language
      if ($code === $defaultLanguageCode) {
        $rules[$code . '_title'] = 'required|string|max:255';
        $rules[$code . '_space_category_id'] = 'required|integer';
        $rules[$code . '_address'] = 'required|string|max:255';
        $rules[$code . '_city_id'] = 'required|integer';
        $rules[$code . '_amenities'] = 'required';
        $rules[$code . '_description'] = 'required|string';

        // Additional rules based on request
        if (
          $request->has('booking_status') && $request->booking_status == 1
        ) {
          $rules[$code . '_quote_form_id'] = 'required|integer';
        }

        if ($request->has('book_a_tour') && $request->book_a_tour == 1) {
          $rules[$code . '_tour_form_id'] = 'required|integer';
        }
      } else {
        // For other languages, check if any field is filled
        if (
          $request->filled($code . '_title') ||
          $request->filled($code . '_space_category_id') ||
          $request->filled($code . '_address') ||
          $request->filled($code . '_city_id') ||
          $request->filled($code . '_amenities') ||
          $request->filled($code . '_description')
        ) {

          // If any field is filled, make all fields required
          $rules[$code . '_title'] = 'required|string|max:255';
          $rules[$code . '_space_category_id'] = 'required|integer';
          $rules[$code . '_address'] = 'required|string|max:255';
          $rules[$code . '_city_id'] = 'required|integer';
          $rules[$code . '_amenities'] = 'required';
          $rules[$code . '_description'] = 'required|string';

          // Additional rules based on request
          if ($request->has('booking_status') && $request->booking_status == 1) {
            $rules[$code . '_quote_form_id'] = 'required|integer';
          }

          if ($request->has('book_a_tour') && $request->book_a_tour == 1) {
            $rules[$code . '_tour_form_id'] = 'required|integer';
          }
        }
      }

      // Dynamic country/state validation
      $country_id = $request->input($code . '_country_id');
      $state_id = $request->input($code . '_state_id');
      $lang_id = Language::where('code', $code)->first();

      // Get country data from the database to apply validation condition
      $countries = Country::where('language_id', $lang_id->id)->get();
      if ($countries->isNotEmpty()) {
        if ($code === $defaultLanguageCode || $request->filled($code . '_country_id')) {
          $rules[$code . '_country_id'] = 'required|integer';
        }
      }

      if ($country_id) {
        $statesExist = State::where([
          ['country_id', $country_id],
          ['language_id', $lang_id->id],
        ])->exists();
        if ($statesExist) {
          if ($code === $defaultLanguageCode || $request->filled($code . '_state_id')) {
            $rules[$code . '_state_id'] = 'required|integer';
          }
        }
        if ($code === $defaultLanguageCode || $request->filled($code . '_city_id')) {
          $rules[$code . '_city_id'] = 'required|integer';
        }
      }

      if ($state_id) {
        $cityExist = City::where([
          ['state_id', $state_id],
          ['language_id', $lang_id->id],
        ])->exists();
        if ($cityExist) {
          if ($code === $defaultLanguageCode || $request->filled($code . '_city_id')) {
            $rules[$code . '_city_id'] = 'required|integer';
          }
        }
      }

      // Custom error messages
      $messages[$code . '_space_category_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('space category') . ' ' . __('field is required') . '.';
      $messages[$code . '_address.required'] = __('The') . ' ' . $languageName->name . ' ' . __('address') . ' ' . __('field is required') . '.';
      $messages[$code . '_title.required'] = __('The') . ' ' . $languageName->name . ' ' . __('Title') . ' ' . __('field is required') . '.';
      $messages[$code . '_description.required'] = __('The') . ' ' . $languageName->name . ' ' . __('Description') . ' ' . __('field is required') . '.';
      $messages[$code . '_amenities.required'] = __('Please select the amenities in') . ' ' . $languageName->name . '.';
      $messages[$code . '_space_category_id.required'] = __('Please select a space category in') . ' ' . $languageName->name . '.';
      $messages[$code . '_country_id.required'] = __('Please select a country in') . ' ' . $languageName->name . '.';
      $messages[$code . '_state_id.required'] = __('Selecting a state in') . ' ' . $languageName->name . '.';
      $messages[$code . '_city_id.required'] = __('Please select a city in') . ' ' . $languageName->name . '.';
      $messages[$code . '_quote_form_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('quote form') . ' ' . __('field is required') . '.';
      $messages[$code . '_tour_form_id.required'] = __('The') . ' ' . $languageName->name . ' ' . __('tour request form') . ' ' . __('field is required') . '.';
    }
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $space = Space::query()->findOrFail($id);

    // merge slider images with existing images if request has new slider image
    if ($request->filled('slider_images')) {
      // Decode the existing JSON string to an array
      $prevImages = json_decode($space->slider_images, true);
      $newImages = $request['slider_images'];
      $imgArr = array_merge($prevImages, $newImages);
    } else {
      $imgArr = json_decode($space->slider_images, true);
    }

    // store thumbnail image in storage
    if ($request->hasFile('thumbnail_image')) {
      $newImage = $request->file('thumbnail_image');
      $oldImage = $space->thumbnail_image;
      $thumbnailImage = UploadFile::update('./assets/img/spaces/thumbnail-images/', $newImage, $oldImage);
    } else {
      $thumbnailImage = $space->thumbnail_image;
    }

    $similarSpaceQuantity = $request->similar_space_quantity ?? 1;
    $admintTimezone = now()->timezoneName;
    // update data in db
    $space->update($request->except('thumbnail_image', 'slider_images', 'opening_time', 'closing_time') + [
      'thumbnail_image' =>  $thumbnailImage,
      'slider_images'   =>  $imgArr,
      'opening_time'    =>  Carbon::parse($request->opening_time, $admintTimezone)->format('H:i'),
      'closing_time'    =>  Carbon::parse($request->closing_time, $admintTimezone)->format('H:i'),
      'similar_space_quantity' => $similarSpaceQuantity,

    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $serviceContent = SpaceContent::query()->where('space_id', '=', $id)
        ->where('language_id', '=', $language->id)
        ->first();
      if (empty($serviceContent)) {
        $serviceContent = new SpaceContent();
      }

      $serviceContent->language_id          = $language->id;
      $serviceContent->space_id             = $space->id;
      $serviceContent->title                = $request[$language->code . '_title'];
      $serviceContent->space_category_id    = $request[$language->code . '_space_category_id'];
      $serviceContent->sub_category_id      = $request[$language->code . '_subcategory_id'];
      $serviceContent->slug                 = createSlug($request[$language->code . '_title']);
      $serviceContent->description          = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $serviceContent->meta_keywords        = $request[$language->code . '_meta_keywords'];
      $serviceContent->amenities            = $request[$language->code . '_amenities'];
      $serviceContent->address              = $request[$language->code . '_address'];
      $serviceContent->get_quote_form_id    = $request[$language->code . '_quote_form_id'];
      $serviceContent->tour_request_form_id = $request[$language->code . '_tour_form_id'];
      $serviceContent->meta_description     = $request[$language->code . '_meta_description'];
      // Update or create the country, state, and city relationships if they exist
      $serviceContent->country_id           = $request->input($language->code . '_country_id');
      $serviceContent->state_id             = $request->input($language->code . '_state_id');
      $serviceContent->city_id              = $request->input($language->code . '_city_id');
      $serviceContent->save();
    }

    $request->session()->flash('success', __('Space updated successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    $this->deleteSpace($id);
    return redirect()->back()->with('success', __('Space deleted successfully') . '!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function bulkDestroy(Request $request)
  {

    $ids = $request->ids;
    foreach ($ids as $id) {
      $this->deleteSpace($id);
    }
    $request->session()->flash('success', __('Spaces deleted successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  // service deletion code
  public function deleteSpace($id)
  {

    $space = Space::query()
      ->with('spaceContents', 'spaceService.serviceContents', 'spaceService.subServices.subServiceContents')
      ->findOrFail($id);

    // Delete the SpaceService and its associated SpaceServiceContent and SubService records
    if ($space->spaceService) {
      foreach ($space->spaceService as $spaceService) {
        // Delete the SpaceServiceContent records
        if ($spaceService->spaceServiceContents) {
          foreach ($spaceService->spaceServiceContents as $spaceServiceContent) {
            $spaceServiceContent->delete();
          }
        }

        // Delete the SubService and its associated SubServiceContent records
        if ($spaceService->subServices) {
          foreach ($spaceService->subServices as $subService) {
            // Delete the SubServiceContent records
            if ($subService->subServiceContents) {
              foreach ($subService->subServiceContents as $subServiceContent) {
                // Delete the SubServiceContent image
                if ($subServiceContent->thumbnail_image) {
                  Storage::delete($subServiceContent->thumbnail_image);
                }
                $subServiceContent->delete();
              }
            }

            // Delete the SubService record and its associated image
            if ($subService->thumbnail_image) {
              Storage::delete($subService->thumbnail_image);
            }
            $subService->delete();
          }
        }

        // Delete the SpaceService record and its associated thumbnail and slider images
        if ($spaceService->thumbnail_image) {
          Storage::delete($spaceService->thumbnail_image);
        }

        if ($spaceService->slider_images) {
          $sliderImages = json_decode($spaceService->slider_images, true);
          foreach ($sliderImages as $sliderImage) {
            Storage::delete($sliderImage);
          }
        }
        $spaceService->delete();
      }
    }

    // Delete the SpaceContent records
    if ($space->spaceContents) {
      foreach ($space->spaceContents as $spaceContent) {
        $spaceContent->delete();
      }
    }

    //     delete all the Booking Records of this space
    $orders = $space->booking()->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        // delete zip file which has uploaded by the user
        $serviceStageInfo = json_decode($order->service_stage_info);
        if (!is_null($serviceStageInfo)) {
          foreach ($serviceStageInfo as $key => $item) {
            @unlink(public_path('./assets/img/space-service/' . $item->img));
          }
        }
        // delete order invoice
        @unlink(public_path('assets/file/invoices/service/' . $order->invoice));
        $order->delete();
      }
    }
    //     delete all the reviews of this service
    $reviews = $space->review()->get();
    if (count($reviews) > 0) {
      foreach ($reviews as $review) {
        $review->delete();
      }
    }

    // delete global days according to space
    $globalDays = GlobalDay::where('space_id', $id)->get();
    if ($globalDays->isNotEmpty()) {
      foreach ($globalDays as $globalDay) {
        $globalDay->delete();
      }
    }

    // delete time slot according to space
    $timeSlots = TimeSlot::where('space_id', $id)->get();
    if ($timeSlots->isNotEmpty()) {
      foreach ($timeSlots as $timeSlot) {
        $timeSlot->delete();
      }
    }

    // delete space feature according to vendor
    $spaceFeatures = SpaceFeature::where('space_id', $id)->get();
    if ($spaceFeatures->isNotEmpty()) {
      foreach ($spaceFeatures as $spaceFeature) {
        $spaceFeature->delete();
      }
    }

    // delete the thumbnail image
    $thumbnailImagePath = !empty($space->thumbnail_image) ? public_path('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image) : null;
    if (!empty($thumbnailImagePath) && file_exists($thumbnailImagePath)) {
      @unlink($thumbnailImagePath);
    }

    // delete the slider images
    $sliderImages = json_decode($space->slider_images);

    foreach ($sliderImages as $sliderImage) {
      $sliderImagePath = !empty($sliderImage) ? public_path('assets/img/spaces/slider-images/' . $sliderImage) : null;
      if (!empty($sliderImagePath) && file_exists($sliderImagePath)) {
        @unlink($sliderImagePath);
      }
    }

    $space->delete();
  }
  public function checkout(Request $request)
  {
  
    $language = getAdminLanguage();

    $abs = Basic::first();
    Config::set('app.timezone', $abs->timezone);
    $featuredCharge = FeatureCharge::find($request->feature_charge);
    if ($featuredCharge == null) {
      Session::flash('error', __('Feature charge not found. Please create a feature charge list') . '!');
      return redirect()->route('admin.feature_record.charge.index', ['language' => $language->code]);
    }
    $space = Space::select('seller_id')->where('id', $request['space_id'])->first();

    if ($space) {
      if ($space->seller_id == 0) {
        $seller       = Admin::select('email')->first();
        $seller['id'] = 0;
      } else {
        $seller = Seller::select('email', 'id')
          ->where('id', $space->seller_id)->first();
      }
    }
    $spaceFeature = SpaceFeature::updateOrCreate(
      ['space_id' => $request['space_id']],
      [
        'seller_id'                => $seller->id,
        'feature_charge_id'        => $featuredCharge->id ?? null,
        'booking_number'           => 'BK-' . Str::random(8),
        'seller_email'             => $seller->email,
        'total'                    => $featuredCharge->price,
        'currency_text'            => $abs->base_currency_text,
        'currency_text_position'   => $abs->base_currency_symbol_position,
        'currency_symbol'          => $abs->base_currency_symbol,
        'currency_symbol_position' => $abs->base_currency_symbol_position,
        'payment_method'           => $request["payment_method"],
        'gateway_type'             => $request["payment_method"],
        'payment_status'           => 'completed',
        'booking_status'           => 'approved',
        'attachment'               => null,
        'days'                     => $featuredCharge->day,
        'start_date'               => Carbon::now(),
        'end_date'                 => Carbon::now()->addDays($featuredCharge->day),
        'conversion_id'            => null,
      ]
    );
    Session::flash('success', __('Space is now featured') . '!');
    return redirect()->back();
  }
  public function unfeatureSpace($id)
  {
    $spaceFeature = SpaceFeature::findOrFail($id);
    $spaceFeature->delete();
    Session::flash('success', __('Space is no longer featured') . '.');
    return redirect()->back();
  }

  public function deleteAmenity(Request $request)
  {
    dd($request->all());
    $space_id   = $request->input('space_id');
    $amenity_id = $request->input('amenity_id');
    $lang_code  = $request->input('code');
    $lang       = Language::where('code', $lang_code)->first();

    $space = SpaceContent::where([
      ['space_id', $space_id],
      ['language_id', $lang->id]
    ])->first();

    if (!$space) {
      return response()->json(['message' => __('Space not found') . '.'], 404);
    }
    $amenities = json_decode($space->amenities, true);
    if (!is_array($amenities)) {
      $amenities = [];
    }
    if (($key = array_search($amenity_id, $amenities)) !== false) {
      unset($amenities[$key]);
      if (count($amenities) == 0) {
        return response()->json(['message' => __('Sorry') . ', ' . __('the last amenity cannot be deleted') . '.'], 400);
      }
      $space->update(['amenities' => json_encode(array_values($amenities))]);
      return response()->json(['message' => __('Amenity deleted successfully') . '!'], 200);
    } else {
      return response()->json(['message' => __('Amenity not found') . '.'], 404);
    }
  }
}
