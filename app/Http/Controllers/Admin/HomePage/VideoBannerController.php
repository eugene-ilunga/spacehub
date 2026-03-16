<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideoBannerController extends Controller
{
  public function index()
  {
    $information['info'] = DB::table('basic_settings')
      ->select('video_banner_section_image', 'video_banner_video_link', 'theme_version')
      ->first();
    $language = Language::query()->where('code', '=', request()->language)->firstOrFail();
    $information['language'] = $language;
    $information['langs'] = Language::all();
    return view('admin.home-page.video-banner.index', $information);

  }
  public function updateVideoBanner(Request $request)
  {
    $info = DB::table('basic_settings')->select('video_banner_section_image', 'theme_version')->first();

    $rules = [];

    if (empty($info->video_banner_section_image)) {
      $rules['video_banner_section_image'] = 'required';
    }
    if ($request->hasFile('video_banner_section_image')) {
      $rules['video_banner_section_image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('video_banner_section_image')) {
      $newImage = $request->file('video_banner_section_image');
      $oldImage = $info->video_banner_section_image;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);
    }

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
    //End video Link store

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'video_banner_section_image' => isset($imgName) ? $imgName : $info->video_banner_section_image,
        'video_banner_video_link' => isset($link) ? $link : null
      ]
    );

    $request->session()->flash('success', __('Information updated successfully') . '!');

    return redirect()->back();
  }
}
