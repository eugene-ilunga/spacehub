<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\SpaceSettingRequest;
use App\Models\BasicSettings\Basic;
use App\Models\City;
use App\Models\Country;
use App\Models\FeatureCharge;
use App\Models\GlobalDay;
use App\Models\Language;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Models\SpaceSetting;
use App\Models\SpaceSubCategory;
use App\Models\State;
use App\Rules\ImageMimeTypeRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class VendorSpaceController extends Controller
{

  public function index(Request $request)
  {

    $title = $s_space_type = $s_category = null;

    if ($request->filled('title')) {
      $title = $request->title;
    }
    if ($request->filled('category')) {
      $s_category = $request->category;
    }
    if ($request->filled('space_type')) {
      $s_space_type = $request->space_type;
    }

    $seller_id = Auth::guard('seller')->user()->id;
    $language = getVendorLanguage();
    $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
    if ($currentPackage) {
      $information['spaceDowngrade'] = SellerPermissionHelper::spaceCount($seller_id);
    }

    // Call the combined function to retrieve space IDs
    $spaceIds = Package::getSpaceIdsBySeller($seller_id);

    $information['spaces'] = Space::query()
      ->select(
        'spaces.*',
        'space_contents.title',
        'space_contents.slug',
        'space_contents.address',
        'space_features.id as space_feature_id',
        'space_features.booking_status',
        'space_contents.space_category_id',
        'space_features.end_date'
      )
      ->leftJoin('space_contents', function ($join) use ($language) {
        $join->on('spaces.id', '=', 'space_contents.space_id')
          ->where('space_contents.language_id', '=', $language->id);
      })
      ->leftJoin('space_features', 'spaces.id', '=', 'space_features.space_id')
      ->where([
        ['spaces.seller_id', '=', $seller_id],
        ['spaces.space_status', '=', 1],
      ])
      ->when($title, function ($query) use ($title) {
        return $query->where('space_contents.title', 'like', '%' . $title . '%');
      })
      ->when($s_space_type, function ($query) use ($s_space_type) {
        return $query->where('spaces.space_type', '=', $s_space_type);
      })
      ->when($s_category, function ($query) use ($s_category) {
        return $query->where('space_contents.space_category_id', '=', $s_category);
      })
      ->whereIn('spaces.id', $spaceIds)
      ->orderByDesc('spaces.id')
      ->paginate(10);

    // Fetch the space category data and add it to the $information['spaces'] collection
    foreach ($information['spaces'] as $service) {
      $category = SpaceCategory::where('id', $service->space_category_id)
        ->where('language_id', $language->id)
        ->first();
      $service->category = $category ?? null;
    }

    $information['sellers'] = Seller::select('id', 'username')->where('id', '!=', 0)->get();
    $information['langs'] = Language::all();
    $information['featuredCharges'] = FeatureCharge::query()->get();
    $online = OnlineGateway::query()->where('status', 1)->get();
    $offline = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

    $offline = $offline->map(function ($item) {
      $item->payment_type = 'offline'; // Add payment_type attribute
      return $item;
    });

    $information['offline'] = $offline;
    $information['currentPackage'] = $currentPackage;
    $information['payment_methods'] = $online->concat($offline);
    $stripe = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe_info = json_decode($stripe->information, true);
    $information['stripe_key'] = $stripe_info['key'];

    $information['categories'] = SpaceCategory::where([
      ['language_id', $language->id],
    ])->select('name', 'id', 'slug')->orderByDesc('id')->get();

    $information = array_merge($information, [
      'stripeError' => __('Your card number is incomplete'),
      'anetCardError' => __('Please provide valid credit card number'),
      'anetYearError' => __('Please provide valid expiration year'),
      'anetMonthError' => __('Please provide valid expiration month'),
      'anetExpirationDateError' => __('Expiration date must be in the future'),
      'anetCvvInvalidError' => __('Please provide valid CVV gfgf'),
      'paymentGatewayError' => __('Payment gateway is required'),
      'firstNameError' => __('First name is required'),
      'phoneNumberError' => __('Phone number is required'),
      'emailAddressError' => __('Email address is required'),
    ]);
    
    return view('vendors.space-management.space.index', $information);
  }
  public function spaceType(Request $request)
  {

    $existFeatures = [];
    $language  = getVendorLanguage();
    $authCheck = Auth::guard('seller')->check();

    if ($authCheck) {
      $seller_id = Auth::guard('seller')->user()->id;
      Seller::findOrFail($seller_id);
    } else {
      return redirect()->route('vendor.login', ['language' => $language->code]);
    }

    if ($seller_id != 0) {

      $hasMembership = SellerPermissionHelper::currentPackagePermission($seller_id);
      if ($hasMembership != null) {
        $existFeatures = json_decode($hasMembership->package_feature, true);
      } else {
        session()->flash('warning', __('It appears that you currently do not have a membership') . '. ' . __('Please consider purchasing a plan to enjoy our services') . '.');
        return redirect()->route('vendor.plan.extend.index', ['language' => $language->code]);
      }

      $outputFeatureArray = [];

      foreach ($existFeatures as $value) {
        if ($value == "Fixed Timeslot Rental" || $value == "Hourly Rental" || $value == "Multi Day Rental") {
          $key = strtolower(str_replace(' ', '_', $value));
          $key = str_replace('timeslot', 'time_slot', $key);
          $outputFeatureArray[$key] = $value;
        }
      }
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
            }
          }

          if (count($features) == 1) {
            return redirect()->route('vendor.space_management.space.create', ['seller_id' => $seller_id, 'type' => $type, 'language' => $language->code]);
          } elseif (count($features) > 1) {
            return view('vendors.space-management.space.space-select', compact('type', 'seller_id', 'outputFeatureArray'));
          }
        } else {
          session()->flash('warning', __('There have no festure in the package') . '.');
          return redirect()->route('vendor.plan.extend.index', ['language' => $language->code]);
        }
      } else {
        session()->flash('warning', __('It appears that you currently do not have a membership') . '. ' . __('Please consider purchasing a plan to enjoy our services') . '.');
        return redirect()->route('vendor.plan.extend.index', ['language' => $language->code]);
      }
    }
  }

  public function create(Request $request)
  {
    $language = getVendorLanguage();
    $languages = Language::all();
    if (Auth::guard('seller')->check()) {
      $sellerId = Auth::guard('seller')->user()->id;
      Seller::findOrFail($sellerId);
    } else {
      return redirect()->route('vendor.login', ['language' => $language->code]);
    }
    $basicSetting = Basic::select('hourly_rental', 'fixed_time_slot_rental', 'multi_day_rental')->first();

    $currentPackage = SellerPermissionHelper::currentPackagePermission($sellerId);
    $information['currentPackage'] = $currentPackage;

    if ($currentPackage) {
      $information['maxSliderImage'] = $currentPackage->number_of_slider_image_per_space;
      if (!empty($currentPackage)) {
        $feature = json_decode($currentPackage->package_feature, true);
        $feature = is_array($feature) ? $feature : [];
      } else {
        $feature = [];
      }
    }

    $information['languages'] = $languages;
    $information['seller'] = Seller::query()->where('id', '=', Auth::guard('seller')->user()->id)->first();
    $information['currencyInfo'] = $this->getCurrencyInfo();

    //get the space type and check both space type true or false
    $matched = Space::checkSpaceType($request, $sellerId, $basicSetting);

    if (!$matched['matched']) {
      session()->flash('warning', __('Please select the space type') . '.');
      return redirect()->route('vendor.space_management.space.select_space_type', ['language' => $language->code]);
    } else {
      $information['space_type'] = $matched['space_type'];
    }
    $information['space_setting'] = $basicSetting;
    return view('vendors.space-management.space.create', $information);
  }
  public function uploadImage(Request $request)
  {

    $rule = [
      'slider_image' => [
        'required',
        'image',
        new ImageMimeTypeRule(),
        'max:5120',
        'dimensions:min_width=860,min_height=610'
      ]
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
  public function removeImage(Request $request)
  {
    $img = $request['imageName'];

    try {
      unlink('assets/img/spaces/slider-images/' . $img);

      return Response::json(['success' => __('The image has been deleted') . '.'], 200);
    } catch (Exception $e) {
      return Response::json(['error' => __('Something went wrong') . '!'], 400);
    }
  }

  public function store(Request $request): JsonResponse
  {
    $language = getVendorLanguage();

    if (Auth::guard('seller')->check()) {
      $seller   = Auth::guard('seller')->user();
      $sellerId = $seller->id;
      Seller::findOrFail($sellerId);
    } else {
      $vendorLoginUrl = route('vendor.login', ['language' => $language->code]);
      return response()->json([
        'status' => 'vendor-login-required',
        'redirect' => $vendorLoginUrl,
        'message' => __('Please login as a vendor to continue') . '.',
      ]);
    }

    $messages = [];

    $currentPackage = SellerPermissionHelper::currentPackagePermission($sellerId);
    if (!empty($currentPackage)) {
      $feature = json_decode($currentPackage->package_feature, true);
      $feature = is_array($feature) ? $feature : [];
    } else {
      $feature = [];
    }

    $languageCodes = Language::query()->select('code')->get()->pluck('code');
    $basicSetting  = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();

    $matched = Space::checkSpaceType($request, $request['seller_id'], $basicSetting);
    if (!$matched['matched']) {
      session()->flash('warning', __('Space type cannot be null') . '. ' . __('Please select a valid space type') . '.');
      return Response::json([
        'status' => 'error',
        'message' => __('Space type cannot be null') . '. ' . __('Please select a valid space type') . '.'
      ], 422);
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
      'thumbnail_image' => 'required|image|mimes:jpg,jpeg,png|max:5120|dimensions:min_width=255,min_height=255',
      'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
      'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
      'min_guest'       => 'required|numeric',
      'max_guest'       => 'required|numeric',
      'space_size'      => 'required',
      'booking_status'  => 'required',
      'book_a_tour'     => 'required',

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

    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;
    // Use a more concise array syntax for adding language-specific rules 
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
    foreach (($request['slider_images'] ?? []) as $image) {
      if (file_exists(public_path('assets/img/spaces/slider-images/' . $image))) {
        $sliderArr[] = $image;
      }
    }

    $similarSpaceQuantity = $request->similar_space_quantity ?? 1;
    $admintTimezone = now()->timezoneName;

    $space = Space::query()->create($request->except('thumbnail_image', 'slider_images', 'opening_time', 'closing_time', 'sellerId', 'space_type') + [
      'thumbnail_image' => $thumbnailImage,
      'slider_images'   => json_encode($sliderArr),
      'seller_id'       => $sellerId,
      'space_type'      => $availableTypes,
      'opening_time'    => Carbon::parse($request->opening_time, $admintTimezone)->format('H:i'),
      'closing_time'    => Carbon::parse($request->closing_time, $admintTimezone)->format('H:i'),
      'similar_space_quantity' => $similarSpaceQuantity,
    ]);

    foreach ($languageCodes as $code) {
      $spaceContent                       = new SpaceContent();
      $spaceContent->language_id          = Language::query()->where('code', '=', $code)->firstOrFail()->id;
      $spaceContent->space_id             = $space->id;
      $spaceContent->title                = $request[$code . '_title'];
      $spaceContent->slug                 = createSlug($request[$code . '_title']);
      $spaceContent->space_category_id    = $request[$code . '_space_category_id'];
      $spaceContent->sub_category_id      = $request[$code . '_subcategory_id'];
      $spaceContent->get_quote_form_id    = $request[$code . '_quote_form_id'];
      $spaceContent->tour_request_form_id = $request[$code . '_tour_form_id'];
      $spaceContent->address              = $request[$code . '_address'];
      $spaceContent->description          = Purifier::clean($request[$code . '_description'], 'youtube');
      $spaceContent->amenities            = json_encode($request[$code . '_amenities']);
      $spaceContent->country_id           = $request->input($code . '_country_id');
      $spaceContent->state_id             = $request->input($code . '_state_id');
      $spaceContent->city_id              = $request->input($code . '_city_id');
      $spaceContent->meta_keywords        = $request[$code . '_meta_keywords'];
      $spaceContent->meta_description     = $request[$code . '_meta_description'];
      $spaceContent->save();
    }

    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    foreach ($days as $key => $name) {
      $day                = new GlobalDay();
      $day->name          = $name;
      $day->seller_id     = Auth::guard('seller')->user()->id; 
      $day->space_id      = $space->id;
      $day->order         = $key;
      $day->start_of_week = $key;
      $day->save();
    }

    $request->session()->flash('success', __('New Space added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function edit($id)
  {
    // Fetch the space by ID and ensure it belongs to the authenticated seller
    $seller = Auth::guard('seller')->user();

    $space = Space::where('id', $id)->where('seller_id', $seller->id)->firstOrFail();

    $sessionLang = getVendorLanguage();
    $information['space_title'] = SpaceContent::getSpaceTitle($id, $sessionLang);
    
    $userId = Auth::guard('seller')->user()->id;
    $currentPackage = SellerPermissionHelper::currentPackagePermission($userId);
    $information['currentPackage'] = $currentPackage;
    $information['amenityDowngrade'] = SellerPermissionHelper::amenitiesCount($userId);

    $information['service'] = $space;
    $sliderImages = json_decode($space->slider_images);
    $information['sliderImages'] = $sliderImages;
    if ($currentPackage) {
      if ($sliderImages) {
        $information['maxSliderImage'] = $currentPackage->number_of_slider_image_per_space - count($sliderImages);
      }
    }

    // get all the languages from db
    $languages = Language::all();
    $languages->map(function ($language) use ($space) {
      // get service content information of each language from db
      $language['serviceData'] = $language->spaceContent()->where('space_id', $space->id)->first();
    });
    $information['seller']    = Seller::query()->where('id', '=', Auth::guard('seller')->user()->id)->first();
    $information['languages'] = $languages;
    $information['address']   = $space->address;

    return view('vendors.space-management.space.edit', $information);
  }
  public function detachImage(Request $request)
  {

    $id           = $request['id'];
    $key          = $request['key'];
    $space        = Space::query()->find($id);
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
  public function deleteAmenity(Request $request)
  {
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

  public function update(Request $request, $id)
  {

    $spaceType = Space::where('id', $id)->select('space_type')->firstOrFail();
    $messages = [];
    $languageCodes = Language::query()->select('code')->get()->pluck('code');
    $rules = [
      'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120|dimensions:min_width=255,min_height=255',
      'latitude'   => ['nullable', 'numeric', 'between:-90,90'],
      'longitude'  => ['nullable', 'numeric', 'between:-180,180'],
      'min_guest'  => 'required|integer',
      'max_guest'  => 'required|integer',
      'space_size' => 'required|numeric',
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

    $space = Space::query()->find($id);
    // merge slider images with existing images if request has new slider image
    if ($request->filled('slider_images')) {
      $prevImages = json_decode($space->slider_images);
      $newImages  = $request['slider_images'];
      $imgArr     = array_merge($prevImages, $newImages);
    } else {
      $imgArr = json_decode($space->slider_images, true);
    }

    // store thumbnail image in storage
    if ($request->hasFile('thumbnail_image')) {
      $newImage       = $request->file('thumbnail_image');
      $oldImage       = $space->thumbnail_image;
      $thumbnailImage = UploadFile::update('./assets/img/spaces/thumbnail-images/', $newImage, $oldImage);
    } else {
      $thumbnailImage = $space->thumbnail_image;
    }

    $similarSpaceQuantity = $request->similar_space_quantity ?? 1;
    $admintTimezone = now()->timezoneName;
    // update data in db
    $space->update($request->except('thumbnail_image', 'slider_images', 'opening_time', 'closing_time') + [
      'thumbnail_image' => $thumbnailImage,
      'slider_images'   => $imgArr,
      'opening_time'    => Carbon::parse($request->opening_time, $admintTimezone)->format('H:i'),
      'closing_time'    => Carbon::parse($request->closing_time, $admintTimezone)->format('H:i'),
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
      $serviceContent->slug                 = createSlug($request[$language->code . '_title']);
      $serviceContent->space_category_id    = $request[$language->code . '_space_category_id'];
      $serviceContent->sub_category_id      = $request[$language->code . '_subcategory_id'];
      $serviceContent->description          = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $serviceContent->country_id           = $request->input($language->code . '_country_id');
      $serviceContent->state_id             = $request->input($language->code . '_state_id');
      $serviceContent->city_id              = $request->input($language->code . '_city_id');
      $serviceContent->meta_keywords        = $request[$language->code . '_meta_keywords'];
      $serviceContent->amenities            = $request[$language->code . '_amenities'];
      $serviceContent->get_quote_form_id    = $request[$language->code . '_quote_form_id'];
      $serviceContent->tour_request_form_id = $request[$language->code . '_tour_form_id'];
      $serviceContent->address              = $request[$language->code . '_address'];
      $serviceContent->meta_description     = $request[$language->code . '_meta_description'];
      $serviceContent->save();
    }
    $request->session()->flash('success', __('Space updated successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }
  public function destroy($id)
  {
    $this->deleteSpace($id);
    return redirect()->back()->with('success', __('Space deleted successfully') . '!');
  }
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $this->deleteSpace($id);
    }
    $request->session()->flash('success', __('Spaces deleted successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }
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
    // Delete the SpaceContent records
    if ($space->spaceContents) {
      foreach ($space->spaceContents as $spaceContent) {
        $spaceContent->delete();
      }
    }
    // Delete the Space record and its associated images
    if ($space->thumbnail_image) {
      Storage::delete($space->thumbnail_image);
    }

    if ($space->slider_images) {
      $sliderImages = json_decode($space->slider_images, true);
      foreach ($sliderImages as $sliderImage) {
        Storage::delete($sliderImage);
      }
    }
    $space->delete();

  }
  public function getStatesByCountryForSpace(Request $request)
  {
    $states = State::where([
      ['country_id', $request->countryId],
      ['language_id', $request->language_id],
    ])
      ->where('status', 1)
      ->get();
     
    return response()->json($states);
  }

  public function getCitiesByCountryForSpace(Request $request)
  {
    $stateId = $request->input('stateId');
    $countryId = $request->input('countryId');
    $langId = $request->input('language_id');

    $cities = City::where(function ($query) use ($stateId, $countryId, $langId) {
      if ($stateId) {
        $query->where([
          ['state_id', $stateId],
          ['language_id', $langId]
        ]);
      } else {
        $query->where([
          ['country_id', $countryId],
          ['language_id', $langId]
        ]);
      }
    })
    ->where('status', 1)
    ->get();

    return response()->json($cities);
  }

  public function getSpaceSubcategories(Request $request)
  {
    $subcategories = SpaceSubCategory::where([
      ['space_category_id', $request->category_id],
      ['status', 1],
    ])->get();
    return response()->json($subcategories);
  }
}
