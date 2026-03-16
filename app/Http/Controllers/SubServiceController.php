<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Seller;
use App\Models\ServiceCategoryContent;
use App\Models\SubService;
use App\Models\SubServiceContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class SubServiceController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;
    $information['SubServices'] = SubService::query()->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
      ->where('sub_service_contents.language_id', '=', $language->id)
      ->select('sub_services.*', 'sub_service_contents.title')
      ->orderBy('sub_services.id', 'asc')
      ->get();

    $information['langs'] = Language::all();
    return view('backend.client-service.space-management.sub-service.index', $information);
  }

  public function getSubservices(Request $request){
    try {
      // Retrieve the space_id from the request
      $spaceId = $request->input('space_id');

      // Query the database to fetch subservices based on space_id
      $subservices = ServiceCategoryContent::where('space_id', $spaceId)->get();


      // Return the fetched subservices as JSON response
      return response()->json(['services' => $subservices], 200);
    } catch (Exception $e) {
      // If an exception occurs, return an error response
      return response()->json(['errorData' => 'Something went wrong!'], 400);
    }
  }

  public function create(Request $request)
  {
    $languageID = Language::query()->where('code', '=', $request->language)->firstOrfail()->id;

    $languages = Language::all();
    $information['languages'] = $languages;
    $information['spaces'] = Service::query()
      ->join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->where('space_status', '=', 1)
      ->where('language_id', '=', $languageID)
      ->select('services.id', 'service_contents.title')
      ->get();

    return view('backend.client-service.space-management.sub-service.create', $information);
  }

  public function store(Request $request)
  {
    $languageCodes = Language::select('code')->get()->pluck('code');
    $rules = [

      'price' => 'required',
      'status' => 'required',
      'space_id' => 'required',
    ];

    $messages = [];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->errors()->messages()
      ], 400);
    }

    // store thumbnail image in storage
    $thumbnailImage = UploadFile::store('./assets/img/sub-services/thumbnail-images/', $request->file('image'));

    $subService = SubService::query()->create($request->except('image') + [
        'image' => $thumbnailImage,
      ]);


    foreach ($languageCodes as $code) {
      $serviceContent = new subServiceContent();
      $serviceContent->language_id = Language::where('code', $code)->firstOrFail()->id;
      $serviceContent->sub_service_id = $subService->id;
      $serviceContent->title = $request[$code . '_title'];
      $serviceContent->slug = createSlug($request[$code . '_title']);
      $serviceContent->description = Purifier::clean($request[$code . '_description'], 'youtube');
      $serviceContent->meta_keywords = $request[$code . '_meta_keywords'];
      $serviceContent->meta_description = $request[$code . '_meta_description'];
      $serviceContent->save();
    }

    $request->session()->flash('success', 'New subservice added successfully!');

    return Response::json(['status' => 'success'], 200);

  }

  public function edit(Request $request, $id, $space_id)
  {
    $languageID = Language::query()->where('code', '=', $request->language)->firstOrfail()->id;
    $info['spaces'] = Service::query()
      ->join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->where('space_status', '=', 1)
      ->where('language_id', '=', $languageID)
      ->select('services.id', 'service_contents.title')
      ->get();
    $subService = SubService::query()->findOrFail($id);
    $languages = Language::all();
    $languages->map(function ($language) use ($subService) {
      // get sub service content information of each language from db
      $language['subServiceData'] = $language->subServiceContent()->where('sub_service_id', $subService->id)->first();
    });
    $info['languages'] = $languages;
    $info['subService'] = $subService;


    return view('backend.client-service.space-management.sub-service.edit', $info);
  }

  public function update(Request $request, $id)
  {

    $subService = SubService::query()->findOrFail($id);
    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $subService->image;
      $image = UploadFile::update('./assets/img/sub-services/thumbnail-images/', $newImage, $oldImage);
    }
    $subService->update($request->except('image') + [
        'image' => $request->hasFile('image') ? $image : $subService->image,

      ]);

    $languages = Language::all();


    foreach ($languages as $language) {

      $subServiceContent = SubServiceContent::query()->where('sub_service_id', '=', $id)
        ->where('language_id', '=', $language->id)
        ->first();

      if (empty($subServiceContent)) {
        $subServiceContent = new SubServiceContent();
      }
      $subServiceContent->language_id = $language->id;
      $subServiceContent->sub_service_id = $subService->id;
      $subServiceContent->title = $request[$language->code . '_title'];
      $subServiceContent->slug = createSlug($request[$language->code . '_title']);
      $subServiceContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $subServiceContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $subServiceContent->meta_description = $request[$language->code . '_meta_description'];
      $subServiceContent->save();
    }

    $request->session()->flash('success', 'subservice updated successfully!');

    return Response::json(['status' => 'success'], 200);

  }

  public function viewSubService(Request $request, $service_id, $space_id)
  {
    $information['langs'] = Language::all();

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    $information['spaces'] = ServiceContent::query()
      ->where('language_id', '=', $language->id)
      ->where('service_id', '=', $space_id)
      ->get()
      ->pluck('title');

    $serviceContents = ServiceCategoryContent::query()
      ->where('language_id', '=', $language->id)
      ->where('service_category_id', '=', $service_id)
      ->get()
      ->pluck('service_title');

    $information['subservices'] = SubService::query()
      ->when($space_id, function ($query) use ($space_id) {
        $query->where('space_id', $space_id);
      })
      ->when($service_id, function ($query) use ($service_id) {
        $query->where('service_id', $service_id);
      })
      ->join('sub_service_contents', 'sub_service_id', '=', 'sub_services.id')
      ->where('language_id', '=', $language->id)
      ->select('sub_services.*', 'sub_service_contents.*')
      ->get();


    $information['services'] = $serviceContents;

    $information['language'] = $language;
    return view('backend.client-service.space-management.sub-service.view-subservice', $information);
  }

  public function partialCreate(Request $request,$service_id, $space_id)
  {
    $information['languages'] = Language::all();

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    $information['spaces'] = ServiceContent::query()
      ->where('language_id', '=', $language->id)
      ->where('service_id', '=', $space_id)
      ->get();


    $serviceContents = ServiceCategoryContent::query()
      ->where('language_id', '=', $language->id)
      ->where('service_category_id', '=', $service_id)
      ->get();

    $information['services'] = $serviceContents;

    return view('backend.client-service.space-management.sub-service.partial-create', $information);
  }

  public function destroy($id)
  {
    $this->deleteSubService($id);
    return redirect()->back()->with('success', 'Subservice deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {

    $ids = $request->ids;
    foreach ($ids as $id)
      $this->deleteSubService($id);
    $request->session()->flash('success', 'Subservices deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }


  public function deleteSubService($id)
  {
    $subService = SubService::query()->find($id);

    // Check if image path exists and is not empty
    if ($subService && $subService->image && file_exists(public_path('assets/img/sub-services/thumbnail-images/' . $subService->image))) {
      // Delete the image file
      @unlink(public_path('assets/img/sub-services/thumbnail-images/' . $subService->image));
    }

    // Delete related subServiceContent records
    $subServiceContents = $subService->subServiceContent()->get();
    if (count($subServiceContents) > 0) {
      foreach ($subServiceContents as $subServiceContent) {
        $subServiceContent->delete();
      }
    }

    $subService->delete();

  }
}
