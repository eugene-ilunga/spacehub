<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\AboutContent;
use App\Models\BasicSettings\Basic;
use App\Models\Blog\Post;
use App\Models\Blog\PostInformation;
use App\Models\City;
use App\Models\ContactContent;
use App\Models\Country;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\FeatureCharge;
use App\Models\GlobalDay;
use App\Models\HomePage\PopularCitySection;
use App\Models\HomePage\SectionTitle;
use App\Models\Language;
use App\Models\MenuBuilder;
use App\Models\Space;
use App\Models\SpaceAmenity;
use App\Models\SpaceBooking;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use App\Models\SpaceService;
use App\Models\SpaceServiceContent;
use App\Models\State;
use App\Models\SubService;
use App\Models\SubServiceContent;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
  public function settings(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $language_settings = Basic::select('is_language')->first();
    return view('admin.language.settings', compact('language', 'language_settings'));
  }

  public function settingsUpdate(Request $request)
  {
    $language_settings = Basic::first();
    $language_settings->update([
      'is_language' => $request->is_language,
    ]);
    Session::flash('success', __('Language settings update successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $languages = Language::all();

    return view('admin.language.index', compact('languages'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // get all the keywords from the default file of language
    $data = file_get_contents(resource_path('lang/') . 'default.json');
    $adminData = file_get_contents(resource_path('lang/') . 'admin_default.json');
   
    // make a new json file for the new language
    $fileName = strtolower($request->code) . '.json';
    $AdminFileName = 'admin_' . strtolower($request->code) . '.json';

    // create the path where the new language json file will be stored
    $fileLocated = resource_path('lang/') . $fileName;
    $AdminFileLocated = resource_path('lang/') . $AdminFileName;

    // finally, put the keywords in the new json file and store the file in lang folder
    file_put_contents($fileLocated, $data);
    file_put_contents($AdminFileLocated, $adminData);

    // then, store data in db
    $language = Language::query()->create($request->all());
    MenuBuilder::create([
      'language_id' => $language->id,
      'menus' => '[{"text":"Home","href":"","icon":"empty","target":"_self","title":"","type":"home"},
      {"text":"Spaces","href":"","icon":"empty","target":"_self","title":"","type":"spaces"},
      {"text":"Pricing","href":"","icon":"empty","target":"_self","title":"","type":"pricing"},
      {"text":"Vendors","href":"","icon":"empty","target":"_self","title":"","type":"vendors"},
      {"text":"Blog","href":"","icon":"empty","target":"_self","title":"","type":"blog"},
      {"text":"FAQ","href":"","icon":"empty","target":"_self","title":"","type":"faq"},
      {"text":"Contact","href":"","icon":"empty","target":"_self","title":"","type":"contact"}]'
    ]);

    // define the path for the language folder
    $langFolderPath = resource_path('lang/' . $language->code);
    if (!file_exists($langFolderPath)) {
      mkdir($langFolderPath, 0755, true);
    }
    // define the source path for the existing language files
    $sourcePath = resource_path('lang/admin_' . $language->code);
    // Check if the source directory exists
    if (is_dir($sourcePath)) {
      $files = scandir($sourcePath);
      foreach ($files as $file) {
        // Skip the current and parent directory indicators
        if ($file !== '.' && $file !== '..') {
          // Copy each file to the new language folder
          $sourceFilePath = $sourcePath . '/' . $file;
          $destinationFilePath = $langFolderPath . '/' . $file;

          copy($sourceFilePath, $destinationFilePath);
        }
      }
    }
    //update attributes with current keyword values
    $validationFilePath = resource_path('lang/admin_' . $language->code . '/validation.php');
    //update existing keywords for validation attributes
    $newKeys = Language::dashboardAttribute();
    $this->updateValidationAttribute($newKeys, $adminData, $validationFilePath);

    $request->session()->flash('success', __('Language added successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Make a default language for this system.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function makeDefault($id)
  {
    // first, make other languages non-default
    Language::where('is_default', 1)->update(['is_default' => 0]);

    // second, make the selected language default
    $language = Language::findOrFail($id);
    $language->update(['is_default' => 1]);

    // update session & app locale
    session(['admin_lang' => 'admin_' . $language->code]);
    app()->setLocale($language->code);

    // Get the translation for current locale
    $filePath = resource_path("lang/admin_{$language->code}.json");
    $translations = json_decode(file_get_contents($filePath), true);

    $value = $translations['is set as default language'] ?? 'is set as default language';

    // Redirect to admin.language_management with updated language query
    return redirect()->route('admin.language_management', ['language' => $language->code])
      ->with('success', $language->name . ' ' . $value . '.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request)
  {
   
    $language = Language::query()->find($request->id);

    if ($language->code !== $request->code) {
      // get all the keywords from the previous language code file
      $data = file_get_contents(resource_path('lang/') . $language->code . '.json');
      $adminData = file_get_contents(resource_path('lang/') . 'admin_' . $language->code . '.json');

      // make a new json file for the new language (code)
      $fileName = strtolower($language->code) . '.json';
      $adminFileName = 'admin_' . strtolower($request->code) . '.json';

      // Create the path where the new language (code) JSON file will be stored
      $fileLocated = resource_path('lang/') . $fileName;
      $adminFileLocated = resource_path('lang/') . $adminFileName;

      // Put the keywords in the new JSON file and store the file in the lang folder
      file_put_contents($fileLocated, $data);
      file_put_contents($adminFileLocated, $adminData);

      // now, delete the previous language code file
      @unlink(resource_path('lang/') . $language->code . '.json');
      @unlink(resource_path('lang/') . 'admin_' . $language->code . '.json');

      // define the path for the language folder
      $langFolderPath = resource_path('lang/' . $request->code);
      if (!file_exists($langFolderPath)) {
        mkdir($langFolderPath,
          0755,
          true
        );
      }
      // define the source path for the existing language files
      $sourcePath = resource_path('lang/admin_' . $request->code);

      // Check if the source directory exists
      if (is_dir($sourcePath)) {
        $files = scandir($sourcePath);
        foreach ($files as $file) {
          // Skip the current and parent directory indicators
          if ($file !== '.' && $file !== '..') {
            // Copy each file to the new language folder
            copy($sourcePath . '/' . $file, $langFolderPath . '/' . $file);
          }
        }
      }
      // Delete language folder and its contents
      $dir = resource_path('lang/') . $language->code;
      if (is_dir($dir)) {
        $this->deleteDirectory($dir);
      }
      // Load validation attributes
      $validationFilePath = resource_path('lang/admin_' . $request->code . '/validation.php');
      //update existing keywords for validation attributes
      $newKeys = Language::dashboardAttribute();
      $this->updateValidationAttribute($newKeys, $adminData, $validationFilePath);

      // finally, update the info in db
      $language->update($request->except('code'));
    } 

     $language->update($request->except('code'));
     $request->session()->flash('success', __('Language updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Display all the keywords of specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editKeyword($id)
  {
    $language = Language::query()->findOrFail($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData);


    return view('admin.language.edit-keyword', compact('language', 'keywords'));
  }
  public function editAdminKeyword($id)
  {
    $language = Language::query()->findOrFail($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'admin_' . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData);

    return view('admin.language.edit-admin-keyword', compact('language', 'keywords'));
  }

  /**
   * Update the keywords of specified resource in respective json file.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function updateKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        $request->session()->flash('warning', __('Value is required for') . ' ' . $key . ' ' .__('key'). '.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::query()->find($id);
    // Load validation attributes
    $validationFilePath = resource_path('lang/' . $language->code . '/validation.php');
    //update existing attributes
    $newKeys = Language::frontAttribute();
    $this->updateValidationAttribute($newKeys, $jsonData, $validationFilePath);

    $fileLocated = resource_path('lang/') . $language->code . '.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    $request->session()->flash('success', $language->name . ' ' .__('language\'s keywords updated successfully') .'.');

    return redirect()->back();
  }

  public function updateAdminKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];


    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        $request->session()->flash('warning', __('Value is required for'). ' ' . $key. ' ' . __('key') . '.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::query()->find($id);

    $fileLocated = resource_path('lang/') . 'admin_'. $language->code . '.json';

    // Load existing keywords from file
    $existingData = [];
    if (file_exists($fileLocated)) {
      $existingData = json_decode(file_get_contents($fileLocated), true) ?? [];
    }

    // Merge existing keywords with new ones (new keys overwrite old ones)
    $mergedData = array_merge($existingData, $arrData);

    //update attributes with current keyword values
    $validationFilePath = resource_path('lang/admin_' . $language->code . '/validation.php');
    //update existing keywords for validation attributes
    $newKeys = Language::dashboardAttribute();
    $this->updateValidationAttribute($newKeys, json_encode($arrData), $validationFilePath);

    // Save the updated data
    file_put_contents($fileLocated, json_encode($mergedData));

    $request->session()->flash('success', $language->name . ' ' . __("language\'s keywords updated successfully") . '!');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    $language = Language::query()->find($id);
   

    if ($language->is_default == 1) {
      return redirect()->back()->with('error', __('Default language cannot be delete') . '.');
    } else {

      /**
       * delete about-section info
       */
      $aboutSecInfo = $language->aboutSection()->first();


      if (!empty($aboutSecInfo)) {
        $aboutSecInfo->delete();
      }

      /**
       * delete about-content info
       */
      $aboutContents = AboutContent::where('language_id', $language->id)->get();

      if (count($aboutContents) > 0) {
        foreach ($aboutContents as $aboutContent) {
          $aboutContent->delete();
        }
      }


      /**
       * delete blog category infos
       */
      $blogCategoryInfos = $language->blogCategory()->get();
   

      if (count($blogCategoryInfos) > 0) {
        foreach ($blogCategoryInfos as $blogCategory) {
          // delete all the post-informations of this category
          $postInformations = $blogCategory->postInfo()->get();

          if (count($postInformations) > 0) {
            foreach ($postInformations as $postData) {
              $postInformation = $postData;
              $postData->delete();

              // delete the post if, this post does not contain any other post-informations in any other category
              $otherPostInformations = PostInformation::query()->where('blog_category_id', '<>', $blogCategory->id)
                ->where('post_id', '=', $postInformation->post_id)
                ->get();

              if (count($otherPostInformations) == 0) {
                $post = Post::query()->find($postInformation->post_id);

                // delete post image
                @unlink(public_path('assets/img/posts/' . $post->image));

                $post->delete();
              }
            }
          }

          $blogCategory->delete();
        }
      }

      /**
       * delete cookie-alert info
       */
      $cookieAlertInfo = $language->cookieAlertInfo()->first();

      if (!empty($cookieAlertInfo)) {
        $cookieAlertInfo->delete();
      }

      /**
       * delete faq infos
       */
      $faqs = $language->faq()->get();

      if (count($faqs) > 0) {
        foreach ($faqs as $faq) {
          $faq->delete();
        }
      }

      /**
       * delete work process section infos
       */
      $workProcesses = $language->workProcessSection()->get();

      if (count($workProcesses) > 0) {
        foreach ($workProcesses as $workProcess) {
          $workProcess->delete();
        }
      }
      /**
       * delete popular city section infos
       */
      $popularCities = PopularCitySection::where('language_id', $language->id)->get();

      if (count($popularCities) > 0) {
        foreach ($popularCities as $popularCity) {
          $popularCity->delete();
        }
      }

      /**
       * delete footer-content info
       */
      $footerContentInfo = $language->footerContent()->first();

      if (!empty($footerContentInfo)) {
        $footerContentInfo->delete();
      }


      /**
       * delete hero-slider infos
       */
      $sliders = $language->heroSlider()->get();

      if (count($sliders) > 0) {
        foreach ($sliders as $slider) {
          @unlink(public_path('assets/img/hero-sliders/' . $slider->image));

          $slider->delete();
        }
      }

      /**
       * delete hero-static info
       */
      $heroInfo = $language->heroStatic()->first();

      if (!empty($heroInfo)) {
        $heroInfo->delete();
      }

      /**
       * delete website-menu info
       */
      $websiteMenuInfo = $language->menuInfo()->first();

      if (!empty($websiteMenuInfo)) {
        $websiteMenuInfo->delete();
      }

      /**
       * delete custom-page infos
       */
      $customPageInfos = $language->customPageInfo()->get();

      if (count($customPageInfos) > 0) {
        foreach ($customPageInfos as $customPageData) {
          $customPageInfo = $customPageData;
          $customPageData->delete();

          // delete the custom-page if, this page does not contain any other page-content in any other language
          $otherPageContents = PageContent::query()->where('language_id', '<>', $language->id)
            ->where('page_id', '=', $customPageInfo->page_id)
            ->get();

          if (count($otherPageContents) == 0) {
            $page = Page::query()->find($customPageInfo->page_id);
            $page->delete();
          }
        }
      }

      /**
       * delete page-heading info
       */
      $pageHeadingInfo = $language->pageName()->first();
   

      if (!empty($pageHeadingInfo)) {
        $pageHeadingInfo->delete();
      }

      /**
       * delete popup infos
       */
      $popups = $language->announcementPopup()->get();

      if (count($popups) > 0) {
        foreach ($popups as $popup) {
          @unlink(public_path('assets/img/popups/' . $popup->image));
          $popup->delete();
        }
      }

      /**
       * delete footer-quick-links
       */
      $quickLinks = $language->footerQuickLink()->get();

      if (count($quickLinks) > 0) {
        foreach ($quickLinks as $quickLink) {
          $quickLink->delete();
        }
      }

      /**
       * delete section-title info
       */
      $sectionTitle = $language->sectionTitle()->first();

      if (!empty($sectionTitle)) {
        $sectionTitle->delete();
      }

      /**
       * delete space banner section
       */

      $contactContents = ContactContent::where('language_id', $language->id)->get();

      if (count($contactContents) > 0) {
        foreach ($contactContents as $contactContent) {
          $contactContent->delete();
        }
      }

      /**
       * delete seo info
       */
      $seoInfo = $language->seoInfo()->first();

      if (!empty($seoInfo)) {
        $seoInfo->delete();
      }

      /**
       * delete space Amenity
       */
      $amenities = SpaceAmenity::where('language_id', $language->id)->get();

      if (count($amenities) > 0) {
        foreach ($amenities as $amenity) {
          $amenity->delete();
        }
      }

      /**
       * delete country
       */
      $countries = Country::where('language_id', $language->id)->get();

      if (count($countries) > 0) {
        foreach ($countries as $country) {
          $country->delete();
        }
      }
      /**
       * delete state
       */

      $states = State::where('language_id', $language->id)->get();

      if (count($states) > 0) {
        foreach ($states as $state) {
          $state->delete();
        }
      }

      /**
       * delete city
       */

      $cities  = City::where('language_id', $language->id)->get();

      // Loop through each city to delete associated images
      foreach ($cities as $city) {
        if (!empty($city->image)) {
          $imagePath = public_path('assets/img/city/' . $city->image);

          // Check if the image file exists and delete it
          if (file_exists($imagePath)) {
            @unlink($imagePath);
          }
        }

        $city->delete();
      }
      /**
       * delete space sub service
       */
      $subservices = SubServiceContent::where('language_id', $language->language_id)->get();
      if (count($subservices) > 0) {
        foreach ($subservices as $subserviceData) {
          $subserviceContent = $subserviceData;
          $subserviceData->delete();

          // delete the blog if, this blog does not contain any other service content in any other language
          $otherSubservices = SubServiceContent::query()->where('language_id', '<>', $language->id)->where('sub_service_id', '=', $subserviceContent->sub_service_id)->get();

          if (count($otherSubservices) == 0) {
            $subservice = SubService::query()->find($subserviceContent->sub_service_id);
            @unlink(public_path('assets/img/sub-services/thumbnail-images/') . $subservice->image);
            $subservice->delete();
          }
        }
      }

      /**
       * delete space service
       */
      $services = SpaceServiceContent::where('language_id', $language->language_id)->get();
      if (count($services) > 0) {
        foreach ($services as $serviceData) {
          $serviceContent = $serviceData;
          $serviceData->delete();

          // delete the blog if, this blog does not contain any other service content in any other language
          $otherServices = SpaceServiceContent::query()->where('language_id', '<>', $language->id)->where('space_service_id', '=', $serviceContent->space_service_id)->get();

          if (count($otherServices) == 0) {
            $service = SpaceService::query()->find($serviceContent->space_service_id);
            @unlink(public_path('assets/img/space-service/') . $service->image);
            $service->delete();
          }
        }
      }


      /**
       * delete space category
       */
      $spaceCategories = $language->spaceCategory()->get();
      if(count($spaceCategories) > 0)
      {
        foreach ($spaceCategories as $spaceCategory)
        {
          $subcategories = $spaceCategory->subcategory()->get();
          if(count($subcategories) > 0)
          {
            foreach ($subcategories as $subcategory)
            {
              $subcategory->delete();
            }
          }

          // delete space content
          $spaceContents = $spaceCategory->spaceContents()->get();
          if(count($spaceContents) > 0)
          {
            foreach ($spaceContents as $spaceData)
            {
              $spaceContent = $spaceData;
              $spaceData->delete();
              // delete the service if, this service does not contain any other service-contents in any other category
              $otherSpaceContents = SpaceContent::query()->where('space_category_id', '<>', $spaceCategory->id)
                ->where('space_id', '=', $spaceContent->space_id)
                ->get();
              if (count($otherSpaceContents) == 0) {
                // delete space Booking
                $space = Space::query()->find($spaceContent->space_id);
                $bookings = SpaceBooking::where('space_id', $space->id)->get();
                if (count($bookings) > 0) {
                  foreach ($bookings as $booking) {
                    $serviceStageInfo = json_decode($booking->service_stage_info);
                    if (!is_null($serviceStageInfo)) {
                      foreach ($serviceStageInfo as $information) {
                        @unlink(public_path('assets/img/space-service/' . $information->img));

                      }
                    }

                    // delete booking receipt
                    @unlink(public_path('assets/img/attachments/space/' . $booking->receipt));

                    // delete booking invoice
                    @unlink(public_path('assets/file/invoices/space/' . $booking->invoice));
                    $booking->delete();
                  }
                }
                // delete all the reviews of this service
                $reviews = $space->review()->get();

                if (count($reviews) > 0) {
                  foreach ($reviews as $review) {
                    $review->delete();
                  }
                }

                // delete wishlist records of this service
                $records = $space->wishlist()->get();

                if (count($records) > 0) {
                  foreach ($records as $record) {
                    $record->delete();
                  }
                }

                // delete global days according to space
                $globalDays = GlobalDay::where('space_id', $space->id)->get();
                if($globalDays->isNotEmpty())
                {
                  foreach ($globalDays as $globalDay)
                  {
                    $globalDay->delete();
                  }
                }

                // delete time slot according to space
                $timeSlots = TimeSlot::where('space_id', $space->id)->get();
                if($timeSlots->isNotEmpty())
                {
                  foreach ($timeSlots as $timeSlot)
                  {
                    $timeSlot->delete();
                  }
                }
                // delete space feature according to space
                $spaceFeatures = SpaceFeature::where('space_id', $space->id)->get();
                if($spaceFeatures->isNotEmpty())
                {
                  foreach ($spaceFeatures as $spaceFeature)
                  {
                    $spaceFeature->delete();
                  }
                }

                // delete the thumbnail image
                @unlink(public_path('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image));

                // delete the slider images
                $sliderImages = json_decode($space->slider_images);

                foreach ($sliderImages as $sliderImage) {
                  @unlink(public_path('assets/img/spaces/slider-images/' . $sliderImage));
                }
          
                $space->delete();

              }
            }
          }

        }

      }

      /**
       * delete feature charge
       */

      $featureCharges = FeatureCharge::where('language_id', $language->id)->get();

      if (count($featureCharges) > 0) {
        foreach ($featureCharges as $featureCharge) {
          $featureCharge->delete();
        }
      }

        /**
         * delete section title
         */
      $sectionTitles = SectionTitle::where('language_id', $language->id)->get();

      if (count($sectionTitles) > 0) {
        foreach ($sectionTitles as $sectionTitle) {
          $sectionTitle->delete();
        }
      }

      /**
       * delete testimonial infos
       */
      $testimonials = $language->testimonial()->get();

      if (count($testimonials) > 0) {
        foreach ($testimonials as $testimonial) {
          $clientImg = $testimonial->image;

          @unlink(public_path('assets/img/clients/' . $clientImg));
          $testimonial->delete();
        }
      }

      /**
       * delete the language json file
       */
      @unlink(resource_path('lang/') . $language->code . '.json');
      @unlink(resource_path('lang/') . 'admin_' .$language->code . '.json');
      $dir = resource_path('lang/') . $language->code;
      if (is_dir($dir)) {
        $this->deleteDirectory($dir);
      }

      /**
       * finally, delete the language info from db
       */
      session()->forget('currentLocaleCode');
      session()->forget('admin_lang');
      session()->forget('vendor_lang');

      $language->delete();

      return redirect()->back()->with('success', __('Language deleted successfully') . '!');
    }
  }

  /**
   * Check the specified language is RTL or not.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function checkRTL($id)
  {
    if (!is_null($id)) {
      $direction = Language::query()->where('id', '=', $id)
        ->pluck('direction')
        ->first();

      return response()->json(['successData' => $direction], 200);
    } else {
      return response()->json(['errorData' => __('Sorry') . ', '. __('an error has occured') . '!'], 400);
    }
  }
  public function addKeyword(Request $request)
  {
    $rules = [
      'keyword' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $languages = Language::get();

    foreach ($languages as $language) {
      if (file_exists(resource_path('lang/') . $language->code . '.json')) {
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
          $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . $language->code . '.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);
      }
    }

    //for default json
    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);
    $datas = [];
    $datas[$request->keyword] = $request->keyword;

    foreach ($keywords as $key => $keyword) {
      $datas[$key] = $keyword;
    }
    //put data
    $jsonData = json_encode($datas);

    $fileLocated = resource_path('lang/') . 'default.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', __('A new keyword add successfully') . '.');

    return response()->json(['status' => 'success'], 200);
  }
  public function addAdminKeyword(Request $request)
  {
    $rules = [
      'keyword' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $languages = Language::get();

    foreach ($languages as $language) {
      if (file_exists(resource_path('lang/') . 'admin_' .$language->code . '.json')) {
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . 'admin_' . $language->code . '.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
          $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . 'admin_' . $language->code . '.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);
      }
    }

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'admin_default.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);
    $datas = [];
    $datas[$request->keyword] = $request->keyword;

    foreach ($keywords as $key => $keyword) {
      $datas[$key] = $keyword;
    }
    //put data
    $jsonData = json_encode($datas);

    $fileLocated = resource_path('lang/') . 'admin_default.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', __('A new keyword add successfully for admin panel') . '.');

    return response()->json(['status' => 'success'], 200);
  }

  //delete a directory recursively
  private function deleteDirectory($dir)
  {
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
      $filePath = "$dir/$file";
      if (is_dir($filePath)) {
        $this->deleteDirectory($filePath);
      } else {
        @unlink($filePath);
      }
    }
    rmdir($dir);
  }

  public  function updateValidationAttribute($newKeys, $content, $validationFilePath)
  {
    try {
      // Load the existing validation array
      $validation = include($validationFilePath);

      // Ensure 'attributes' key exists
      if (!isset($validation['attributes']) || !is_array($validation['attributes'])) {
        $validation['attributes'] = [];
      }
    } catch (\Exception $e) {
      session()->flash('warning', __('Please provide a valid language code') . '!');
      return;
    }


    //update existing keys
    foreach ($newKeys as $key => $value) {
      if (!array_key_exists($key, $validation['attributes'])) {
        $validation['attributes'][$key] = $value;
      }
    }

    // update values which matching keys with new values
    $decodedContent = json_decode($content, true);
    if (is_array($decodedContent)) {
      foreach ($decodedContent as $key => $value) {
        if (array_key_exists($key, $validation['attributes'])) {
          $validation['attributes'][$key] = $value;
        }
      }
    }

    //save the changes in validation attributes array
    $validationContent = "<?php\n\nreturn " . var_export($validation, true) . ";\n";
    file_put_contents($validationFilePath, $validationContent);
  }
}
