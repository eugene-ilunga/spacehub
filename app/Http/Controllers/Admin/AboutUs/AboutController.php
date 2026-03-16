<?php

namespace App\Http\Controllers\Admin\AboutUs;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\AboutSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class AboutController extends Controller
{
  public function heading(Request $request)
  {

    $information['info'] = DB::table('basic_settings')
      ->select('about_section_image', 'about_section_video_link', 'theme_version')
      ->first();

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;
    $information['aboutContents'] = $language->aboutContent()->orderByDesc('id')->get();


    $information['data'] = $language->aboutSection()->first();

    $information['langs'] = Language::all();

    return view('admin.about-us.heading', $information);
  }

  public function updateImage(Request $request)
  {

    $info = DB::table('basic_settings')->select('about_section_image', 'theme_version')->first();

    $rules = [];

    if (empty($info->about_section_image)) {
      $rules['about_section_image'] = 'required';
    }
    if ($request->hasFile('about_section_image')) {
      $rules['about_section_image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('about_section_image')) {
      $newImage = $request->file('about_section_image');
      $oldImage = $info->about_section_image;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);
    }


    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'about_section_image' => isset($imgName) ? $imgName : $info->about_section_image,
      ]
    );

    $request->session()->flash('success', __('Information updated successfully') . '!');

    return redirect()->back();
  }


  public function updateInfo(Request $request)
  {

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    AboutSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'title' => $request->title,
        'text' => Purifier::clean($request->text, 'youtube'),
      ]
    );

    $request->session()->flash('success', __('Information updated successfully') . '!');

    return redirect()->back();
  }
}
