<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Section;
use App\Models\HomePage\SectionContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
  public function index()
  {
    $sectionInfo = Section::query()->first();

    return view('admin.home-page.section-customization', compact('sectionInfo'));
  }

  public function update(Request $request)
  {
    $sectionInfo = Section::query()->first();

    $sectionInfo->update($request->all());

    $request->session()->flash('success', __('Section status updated successfully') . '!');

    return redirect()->back();
  }


  public function sectionContent(){
    $themeVersion = Basic::query()->pluck('theme_version')->first();
    $information = [];

    $language = getAdminLanguage();
    $information['language'] = $language;
    $information['homePageInfo'] = SectionContent::where('language_id', $language->id)->first();
    $information['homePageImages'] = Basic::select('banner_section_bg_img', 'banner_section_foreground_img', 'work_process_background_img', 'testimonial_bg_img', 'video_banner_section_image', 'hero_section_background_img', 'hero_section_foreground_img', 'hero_section_foreground_img_theme_3', 'hero_section_foreground_img_theme_3_left')->first();

    $information['langs'] = Language::all();

    if ($themeVersion == 1 || $themeVersion == 2 || $themeVersion == 3) {
      $information['heroImgs'] = Basic::query()->select('hero_section_background_img', 'hero_section_foreground_img', 'hero_section_foreground_img_theme_3', 'hero_section_foreground_img_theme_3_left')->first();
      $information['heroInfo'] = $language->heroStatic()->first();
    } else {
      $information['bgImg'] = Basic::query()->pluck('hero_section_background_img')->first();
      $information['sliders'] = $language->heroSlider()->orderByDesc('id')->get();
    }
    return view('admin.home-page.section_content', $information);
  }
  /**
   * home page section update
   */
  public function updateContent(Request $request)
  {
    // Get the current language
    $language = getAdminLanguage();
    $language_id = $language->id;

    // Validation rules for text fields
    $rules = [
      'category_section_title' => 'max:255',
      'featured_section_title' => 'max:255',
      'banner_section_title' => 'max:255',
      'banner_section_button_text' => 'max:255',
      'workprocess_section_title' => 'max:255',
      'testimonial_title' => 'max:255',
      'video_banner_video_link' => 'max:255',
      'hero_section_title' => 'max:255',
      'hero_section_text' => 'max:255',
      'popular_city_section_title' => 'max:255',
      'popular_city_section_text' => 'max:255',
      'popular_city_section_button_name' => 'max:255',
    ];

    $link = $request->video_banner_video_link;

    if (strpos($link, '&') != 0) {
      $endPosition = strpos($link, '&');
      $link = substr($link, 0, $endPosition);
    }

    //Video Link format
    $link = null;
    if ($request->filled('video_banner_video_link')) {
      $link = $request->video_banner_video_link;
      if (strpos($link, '&') != 0) {
        $link = substr($link, 0, strpos($link, '&'));
      }
    }

    // Prepare data for saving
    $info = [
      'language_id' => $language_id,
      'category_section_title' => $request->category_section_title,
      'featured_section_title' => $request->featured_section_title,
      'banner_section_title' => $request->banner_section_title,
      'banner_section_button_text' => $request->banner_section_button_text,
      'workprocess_section_title' => $request->workprocess_section_title,
      'testimonial_title' => $request->testimonial_title,
      'video_banner_video_link' => $link,
      'hero_section_title' => $request->hero_section_title,
      'hero_section_text' => $request->hero_section_text,
      'popular_city_section_title' => $request->popular_city_section_title,
      'popular_city_section_text' => $request->popular_city_section_text,
      'popular_city_section_button_name' => $request->popular_city_section_button_name,
    ];

    // Fetch existing images
    $homePageImages = Basic::select('banner_section_bg_img', 'work_process_background_img', 'testimonial_bg_img', 'video_banner_section_image', 'hero_section_background_img', 'hero_section_foreground_img', 'hero_section_foreground_img_theme_3', 'hero_section_foreground_img_theme_3_left')->first();

    // Define image fields and corresponding rules/messages
    $imageFields = [
      'banner_section_bg_img',
      'banner_section_foreground_img',
      'work_process_background_img',
      'testimonial_bg_img',
      'video_banner_section_image',
      'hero_section_background_img',
      'hero_section_foreground_img',
      'hero_section_foreground_img_theme_3',
      'hero_section_foreground_img_theme_3_left',
    ];

    foreach ($imageFields as $field) {
      // Check if a new file is uploaded
      if ($request->hasFile($field)) {
        $rules[$field] = new ImageMimeTypeRule(); 
      }
    }

    // Validate image fields
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }
    
    // Update or create images
    foreach ($imageFields as $field ) {
      
      if ($request->hasFile($field)) {
        $newImage = $request->file($field);
        $oldImage = $homePageImages->$field;

        // Use the default upload location for other images
        $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);

        // Update or create the record in the database
        Basic::query()->updateOrCreate(
          ['uniqid' => 12345], // Use the appropriate condition for your model
          [$field => $imgName]
        );
      }
    }

    // Update or create section content
    SectionContent::updateOrCreate(
      ['language_id' => $language_id],
      $info
    );

    // Flash success message and redirect
    Session::flash('success', __('Image and Content updated successfully'). '!');
    return redirect()->back();
  }

}
