<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Space;
use App\Models\SpaceContent;
use App\Models\SpaceService;
use App\Models\SpaceServiceContent;
use App\Models\SubService;
use App\Models\SubServiceContent;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class VendorSpaceServiceController extends Controller
{

  public function viewService(Request $request, $space_id)
  {
    $seller_id = Auth::guard('seller')->user()->id;

    $space = Space::where('id', $space_id)
      ->where('seller_id', $seller_id) 
      ->select('space_type')
      ->firstOrFail();

    
    $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
    if ($currentPackage) {
      $information['serviceDowngrade'] = SellerPermissionHelper::serviceCount($seller_id);
    }
    $information['langs'] = Language::all();

    $language = getVendorLanguage();

    $information['spaces'] = SpaceContent::query()
      ->where('language_id', '=', $language->id)
      ->where('space_id', $space_id)
      ->get();

    $serviceContents = SpaceService::query()->select('space_services.*', 'space_service_contents.title')
      ->join('space_service_contents', 'space_service_contents.space_service_id', '=', 'space_services.id')
      ->where([
        ['language_id', '=', $language->id],
        ['space_id', '=', $space_id],
      ])
      ->orderBy('space_services.serial_number', 'asc')
      ->paginate(10);

    $information['services'] = $serviceContents;
    $information['language'] = $language;
    $information['basic'] = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position')->first();
    return view('vendors.space-management.service.view-service', $information);
  }


  public function partialCreate(Request $request)
  {

    $languages = Language::all();
    $information['languages'] = $languages;
    $information['langs'] = Language::all();
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $spaceId = request()->id;
    $space = Space::where('id', $spaceId)->select('space_type')->firstOrFail();
    $information['space_type'] = $space->space_type;

    $information['spaces'] = SpaceContent::query()
      ->where('space_id', '=', $spaceId)
      ->where('language_id', '=', $language->id)
      ->get();
    $information['spaceId'] = $spaceId;
    $information['seller'] = Auth::guard('seller')->user();
    $sellerId = Auth::guard('seller')->user()->id;
    $currentPackage = SellerPermissionHelper::currentPackagePermission($sellerId);

    if ($currentPackage != null) {
      $information['maxNumberOfOption'] = $currentPackage->number_of_option_per_service;
      if ($currentPackage->number_of_option_per_service != 999999) {
        $information['numberOfOption'] = $currentPackage->number_of_option_per_service - 1;
      } else {
        $information['numberOfOption'] = 999999;
      }
    } else {
      $information['numberOfOption'] = 0;
    }

    return view('vendors.space-management.service.partial-create', $information);
  }

  public function store(Request $request)
  {

    $languageCodes = Language::query()->select('code')->get()->pluck('code');
    $space = Space::where('id', $request->space_id)->select('space_type')->firstOrFail();
    $messages = [];
    $rules = [
      'status'           => 'required',
      'serial_number'    => 'required|numeric',
      'has_sub_services' => 'required',
      'price_type'       => 'required',
    ];

    $subServiceStatuses = $request->input('sub_service_status') ?? [];

    // Conditional rules based on has_sub_services
    if ($request->has_sub_services == 0) {
      $rules['price'] = 'required|numeric';
    } else {
      // If has_sub_services is 1, add other rules
      $rules = array_merge($rules, [
        'subservice_selection_type' => 'required',
        'sub_service_price.*'       => 'required|numeric',
        'sub_service_status.*'        => 'required',
      ]);

      $messages['subservice_selection_type.required'] = __('The variant selection type field is required') . '.';
    

      // Generate custom messages for each index of sub_service_price
      foreach ($request->input('sub_service_price', []) as $index => $value) {
        $messages["sub_service_price.$index.required"] = __("The variant price for variation") . ' ' . ($index + 1) . ' ' . __("field is required") . '.';
        $messages["sub_service_price.$index.numeric"] = __("The variant price for variation") . ' ' . ($index + 1) . ' ' . __("must be a number") . '.';
      }

      // Add messages for sub_service_status
      foreach ($subServiceStatuses as $index => $value) {
        $messages["sub_service_status.$index.required"] = __("The variant status for variation") . ' ' . ($index + 1) . ' ' . __("field is required") . '.';
      }
    }

    if ($space->space_type == 3) {
      $rules = array_merge($rules, [
        'is_custom_day' => 'required',
      ]);
    }

    // Language-specific rules
    foreach ($languageCodes as $code) {
      $language = Language::query()->where('code', $code)->firstOrFail();

      $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
      $defaultLanguageCode = $langForValidation->code;

      // Always require fields for the default language
      if ($code === $defaultLanguageCode) {
        $rules[$code . '_title'] = 'required|string|max:255';
        // Append messages
        $messages[$code . '_title.required'] = __('The') . ' ' . $language->name . ' ' . __('Service Title') . ' ' . __('field is required') . '.';
      } else {
        // For other languages, check if any field is filled
        if (
          $request->filled($code . '_title') ||
          $request->filled($code . '_meta_keyword') ||
          $request->filled($code . '_meta_description')
        ) {
          $rules[$code . '_title'] = 'required|string|max:255';

          // Append messages
          $messages[$code . '_title.required'] = __('The') . ' ' . $language->name . ' ' . __('Service Title') . ' ' . __('field is required') . '.';
        }
      }

      // Check for sub-services
      if ($request->has('has_sub_services') && $request->has_sub_services == 1) {
        $rules[$code . '_sub_service_name.*'] = 'required|string|max:255';

        // Loop through the input to create custom messages
        foreach ($request->input($code . '_sub_service_name', []) as $index => $value) {
          $messages[$code . '_sub_service_name.' . $index . '.required'] = __("The") . ' ' . $language->name . ' ' . __("variant name for the variation") . ' ' . ($index + 1) . ' ' . __('field is required') . '.';
        }
      }
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    // store image in storage

    $spaceService = SpaceService::query()->create($request->except('image'));

    foreach ($languageCodes as $code) {
      $language_id = Language::query()->where('code', $code)->firstOrFail()->id;

      $spaceServiceContent = new SpaceServiceContent();
      $spaceServiceContent->language_id = $language_id;
      $spaceServiceContent->space_service_id = $spaceService->id;
      $spaceServiceContent->title = $request[$code . '_title'];
      $spaceServiceContent->slug = createSlug($request[$code . '_title']);
      $spaceServiceContent->meta_keywords = $request[$code . '_meta_keywords'];
      $spaceServiceContent->meta_description = $request[$code . '_meta_description'];
      $spaceServiceContent->save();
    }
    // 1. Create SubService records first
    if ($spaceService->has_sub_services == 1) {
      $subServiceNames = $request->{$code . '_sub_service_name'};
      foreach ($subServiceNames as $index => $subServiceName) {
        $subServiceData = [
          'price' => $request->sub_service_price[$index],
          'status' => $request->sub_service_status[$index],
          'service_id' => $spaceService->id,
          'space_id' => $spaceService->space_id,
        ];

        // Check if sub_service_image exists and is valid before processing it
        if (isset($request->sub_service_image[$index]) && $request->sub_service_image[$index]->isValid()) {
          $subServiceImageName = UploadFile::store('./assets/img/sub-services/thumbnail-images/', $request->sub_service_image[$index]);
          $subServiceData['image'] = $subServiceImageName;
        }
        $subService = SubService::create($subServiceData);

        // 2. Create SubServiceContent records for each language
        $languages = Language::all();
        foreach ($languages as $language) {
          $subServiceNameForLang = $request->{$language->code . '_sub_service_name'}[$index];
          $subServiceContent = new SubServiceContent([
            'language_id' => $language->id,
            'sub_service_id' => $subService->id,
            'title' => $subServiceNameForLang,
            'slug' => createSlug($subServiceNameForLang),
          ]);
          $subServiceContent->save();
        }
      }
    }


    $request->session()->flash('success', __('New service added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function edit(Request $request, $id)
  {

    $language = Language::all();
    $sellerId = Auth::guard('seller')->user()->id;
    $sessionLang = getVendorLanguage();
    $data['spaces'] = Space::select('spaces.id as space_id', 'space_contents.title', 'space_contents.slug')
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where('space_contents.language_id', '=', $sessionLang->id)
      ->get();

    $spaceService = SpaceService::where([
      ['id', '=', $id],
      ['seller_id', '=', $sellerId]
    ])->with('subServices')->firstOrFail();

    $serviceTitle = SpaceServiceContent::getSpaceServiceTitle($id, $sessionLang);
    $data['service_title'] = isset($serviceTitle) && !empty($serviceTitle->title) ? $serviceTitle->title : null;

    $space = Space::where('id', $spaceService->space_id)->select('space_type')->firstOrFail();
    $spaceTitle = SpaceContent::getSpaceTitle($spaceService->space_id, $sessionLang);


    $data['space_type'] = $space->space_type;
    $data['space_title'] = isset($spaceTitle) && !empty($spaceTitle->title) ? $spaceTitle->title : null;
    $serviceId = $spaceService->id;
    $subService = SubService::query()
      ->with('subServiceContents')
      ->where('service_id', '=', $serviceId)->get();

    $subServiceIds = $spaceService->subServices()->pluck('id')->toArray();


    $data['spaceService'] = $spaceService;
    $data['languages'] = $language;
    $data['subServices'] = $subService;

    $currentPackage = SellerPermissionHelper::currentPackagePermission($sellerId);

    if ($currentPackage != null) {
      $data['maxNumberOfOption'] = $currentPackage->number_of_option_per_service;
      if ($currentPackage->number_of_option_per_service != 999999) {
        $remainingOptions = ($currentPackage->number_of_option_per_service) - count($subService);
        if ($remainingOptions > 0) {
          $data['numberOfOption'] = $remainingOptions;
        } else {
          $data['numberOfOption'] = 0;
        }
      } else {
        $data['numberOfOption'] = 999999;
      }
    } else {
      $data['numberOfOption'] = 0;
    }
    $data['imgRmvMessage'] = __('Image removed successfully') . '!';
    $data['imgWrongMessage'] = __('Something went wrong') . '!';
    return view('vendors.space-management.service.edit', $data);
  }

  public function deleteStoredSubService(Request $request)
  {
    $id = $request->input('sub_service_id');

    // Find the subservice
    $subservice = SubService::find($id);

    if ($subservice) {
      // Unlink (delete) the subservice image file if it exists
      if ($subservice->image && file_exists('./assets/img/sub-services/thumbnail-images/' . $subservice->image)) {
        @unlink(public_path('assets/img/sub-services/thumbnail-images/') . $subservice->image);
      }

      // Delete all associated subservice content
      $subservice->subServiceContents()->delete();

      // Delete the subservice
      $subservice->delete();

      return response()->json([
        'status' => 'success',
        'message' => __('Subservice deleted successfully') . '!'
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => __('Subservice not found') . '!'
      ], 404);
    }
  }

  public function update(Request $request)
  {
    $service = SpaceService::findOrFail($request->id);
    $space = Space::where('id', $service->space_id)->select('space_type')->firstOrFail();

    $languageCodes = Language::query()->select('code')->get()->pluck('code');
    $messages = [];
    $rules = [
      'status'           => 'required|numeric',
      'serial_number'    => 'required|numeric',
      'has_sub_services' => 'required',
      'price_type'       => 'required',
    ];
    $subServiceStatuses = $request->input('sub_service_status') ?? [];

    // Conditional rules based on has_sub_services
    if ($request->has_sub_services == 0) {
      $rules['price_type'] = 'required';
    } else {
      // If has_sub_services is 1
      $rules = array_merge($rules, [
        'subservice_selection_type' => 'required',
        'sub_service_price.*'       => 'required|numeric',
        'sub_service_status.*'        => 'required',
      ]);

      $messages['subservice_selection_type.required'] = __('The variant selection type field is required') . '.';
     

      // Generate custom messages for each index of sub_service_price
      foreach ($request->input('sub_service_price', []) as $index => $value) {
        $messages["sub_service_price.$index.required"] = __("The variant price for variation") . ' ' . ($index + 1) . ' ' . __("field is required") . '.';
        $messages["sub_service_price.$index.numeric"] = __("The variant price for variation") . ' ' . ($index + 1) . ' ' . __("must be a number") . '.';
      }

      // Add messages for sub_service_status
      foreach ($subServiceStatuses as $index => $value) {
        $messages["sub_service_status.$index.required"] = __("The variant status for variation") . ' ' . ($index + 1) . ' ' . __("field is required") . '.';
      }
    }
    if ($space->space_type == 3) {
      $rules = array_merge($rules, [
        'is_custom_day' => 'required',
      ]);
    }

    // Language-specific rules
    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;
    foreach ($languageCodes as $code) {
      $language = Language::query()->where('code', $code)->firstOrFail();

      // Always require fields for the default language
      if ($code === $defaultLanguageCode) {
        $rules[$code . '_title'] = 'required|string|max:255';

        // Append messages
        $messages[$code . '_title.required'] = __('The') . ' ' . $language->name . ' ' . __('Service Title') . ' ' . __('field is required') . '.';
      } else {
        // For other languages, check if any field is filled
        if (
          $request->filled($code . '_title') ||
          $request->filled($code . '_meta_keyword') ||
          $request->filled($code . '_meta_description')
        ) {
          $rules[$code . '_title'] = 'required|string|max:255';

          // Append messages
          $messages[$code . '_title.required'] = __('The') . ' ' . $language->name . ' ' . __('Service Title') . ' ' . __('field is required') . '.';
        }
      }

      // Check for sub-services
      if ($request->has('has_sub_services') && $request->has_sub_services == 1) {
        // Always require sub-service names for the default language
        if ($code === $defaultLanguageCode) {
          $rules[$code . '_sub_service_name.*'] = 'required|string|max:255';

          // Loop through the input to create custom messages
          foreach ($request->input($code . '_sub_service_name', []) as $index => $value) {
            $messages[$code . '_sub_service_name.' . $index . '.required'] = __("The") . ' ' . $language->name . ' ' . __("variant name for the variation") . ' ' . ($index + 1) . ' ' . __('field is required') . '.';
          }
        } else {
          // For other languages, check if any sub-service names are filled
          if ($request->filled($code . '_sub_service_name')) {
            $rules[$code . '_sub_service_name.*'] = 'required|string|max:255';

            // Loop through the input to create custom messages
            foreach ($request->input($code . '_sub_service_name', []) as $index => $value) {
              $messages[$code . '_sub_service_name.' . $index . '.required'] = __("The") . ' ' . $language->name . ' ' . __("variant name for the variation") . ' ' . ($index + 1) . ' ' . __('field is required') . '.';
            }
          }
        }
      }
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json(['errors' => $validator->getMessageBag()], 400);
    }

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $service->image;
      $imgName = UploadFile::update('./assets/img/space-service/', $newImage, $oldImage);
      $service->image = $imgName;
    }

    $service->update($request->except('image') + ['image' => $service->image]);

    // Fetch all language codes
    $languageCodes = Language::pluck('code');

    foreach ($languageCodes as $code) {
      $language = Language::where('code', $code)->firstOrFail();
      $serviceCategoryContent = SpaceServiceContent::where('space_service_id', $service->id)
        ->where('language_id', $language->id)
        ->first();

      if ($serviceCategoryContent) {
        $serviceCategoryContent->update([
          'space_id' => $request->space_id,
          'title' => $request[$code . '_title'],
          'slug' => createSlug($request[$code . '_title']),
          'meta_keywords' => $request[$code . '_meta_keywords'],
          'meta_description' => $request[$code . '_meta_description'],
        ]);
      }
    }

    // Handling subservices
    $subServiceNames = $request->input($code . '_sub_service_name', []);
    $subServiceIds = $request->input('sub_service_id', []);
    $subServicePrices = $request->input('sub_service_price', []);
    $subServiceImages = $request->file('sub_service_image', []);
    $subServiceStatuses = $request->input('sub_service_status', []);

    foreach ($subServiceNames as $index => $subServiceName) {
      $subServiceId = $subServiceIds[$index] ?? null;

      if ($subServiceId) {
        // Update existing subservice
        $subService = SubService::find($subServiceId);
        if (!$subService) {
          continue;
        }
      } else {
        // Create new subservice
        $subService = new SubService();
      }

      $subService->space_id = $request->space_id;
      $subService->service_id = $service->id;
      $subService->price = $subServicePrices[$index] ?? null;

      if (isset($subServiceImages[$index]) && $subServiceImages[$index]->isValid()) {
        $newImage = $subServiceImages[$index];
        $oldImage = $subService->image ?? '';
        $subServiceImageName = UploadFile::update('./assets/img/sub-services/thumbnail-images/', $newImage, $oldImage);
        $subService->image = $subServiceImageName;
      }

      $subService->status = $subServiceStatuses[$index] ?? null;
      $subService->save();

      foreach ($languageCodes as $code) {
        $language = Language::where('code', $code)->firstOrFail();
        $subServiceNameForLang = $request->input($code . '_sub_service_name')[$index] ?? null;
        if (!$subServiceNameForLang) {
          continue;
        }

        $subServiceContent = SubServiceContent::where([
          ['language_id', '=', $language->id],
          ['sub_service_id', '=', $subService->id],
        ])->first();

        if ($subServiceContent) {
          $subServiceContent->update([
            'title' => $subServiceNameForLang,
            'slug' => createSlug($subServiceNameForLang),
          ]);
        } else {
          SubServiceContent::create([
            'sub_service_id' => $subService->id,
            'language_id' => $language->id,
            'title' => $subServiceNameForLang,
            'slug' => createSlug($subServiceNameForLang),
          ]);
        }
      }
    }

    $request->session()->flash('success', __('Service updated successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  public function removeImage(Request $request)
  {
    $id = $request->fileid;
    $subservice = SubService::find($id);


    if ($subservice) {

      // Unlink (delete) the subservice image file if it exists
      if ($subservice->image && file_exists('./assets/img/sub-services/thumbnail-images/' . $subservice->image)) {
        @unlink(public_path('assets/img/sub-services/thumbnail-images/') . $subservice->image);
      }
      // Remove the image reference from the database
      $subservice->image = null;
      $subservice->save();

      return response()->json([
        'status' => 'success',
        'message' => __('Image removed successfully') . '!'
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => __('Subservice not found') . '!'
      ], 404);
    }
  }
  public function destroy($id)
  {
    $this->deleteService($id);
    return redirect()->back()->with('success', __('Service deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteService($id);
    }

    $request->session()->flash('success', __('Services deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  // category deletion code
  public function deleteService($id)
  {

    $spaceService = SpaceService::query()->where('id', '=', $id)->firstOrFail();


    // delete all the subcategories of this category
    if (!empty($spaceService)) {
      $subServices = $spaceService->subServices()->get();
      if ($subServices->isNotEmpty()) {
        foreach ($subServices as $subService) {
          $subServiceContents = $subService->subServiceContents()->get();
          if (count($subServiceContents) > 0) {
            foreach ($subServiceContents as $subServiceContent) {
              $subServiceContent->delete();
            }
          }
          @unlink(public_path('assets/img/space-service/') . $subService->image);
          $subService->delete();
        }
      }
    }

    // delete all the service-contents of this category
    $serviceContents = $spaceService->serviceContents()->get();
    if ($serviceContents->isNotEmpty()) {
      foreach ($serviceContents as $serviceContent) {
        $serviceContent->delete(); // Delete each serviceContent
      }
    }

    // delete space Service image
    @unlink(public_path('assets/img/space-service/' . $spaceService->image));

    $spaceService->delete();
  }
}
