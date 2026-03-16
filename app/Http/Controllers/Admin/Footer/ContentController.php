<?php

namespace App\Http\Controllers\Admin\Footer;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Footer\FooterContent;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ContentController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;
    $information['footerLogo'] = DB::table('basic_settings')->select('footer_logo', 'footer_section_bg_img')->first();

    $information['data'] = $language->footerContent()->first();

    return view('admin.footer.image_content', $information);
  }

  public function update(Request $request)
  {
    $rules = [
      'footer_background_color' => 'required',
      'about_company' => 'required',
      'copyright_text' => 'required',
      'newsletter_text' => 'required',
    ];

    $data = DB::table('basic_settings')->select('footer_logo', 'footer_section_bg_img')->first();
    // background image
    if (is_null($data->footer_section_bg_img)) {
      $rules['footer_section_bg_img'] = 'required';
    }
    if ($request->hasFile('footer_section_bg_img')) {
      $rules['footer_section_bg_img'] = new ImageMimeTypeRule();
    }

    $logoName = $data->footer_section_bg_img;
    if ($request->hasFile('footer_section_bg_img')) {
      $newImage = $request->file('footer_section_bg_img');
      $footerBgImage = UploadFile::update('./assets/img/', $newImage, $data->footer_section_bg_img);
    }

    if (is_null($data->footer_logo)) {
      $rules['footer_logo'] = 'required';
    }
    if ($request->hasFile('footer_logo')) {
      $rules['footer_logo'] = new ImageMimeTypeRule();
    }

    $logoName = $data->footer_logo;
    if ($request->hasFile('footer_logo')) {
      $newLogo = $request->file('footer_logo');
      $logoName = UploadFile::update('./assets/img/', $newLogo, $data->footer_logo);
    }


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)  
        ->withInput();
    }

    // first, get the language info from db
    $language = getAdminLanguage();
 
    FooterContent::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'footer_background_color' => $request['footer_background_color'],
        'about_company' => $request['about_company'],
        'newsletter_text' => $request['newsletter_text'],
        'copyright_text' => Purifier::clean($request['copyright_text'])
      ]
    );

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'footer_logo' => $logoName,
        'footer_section_bg_img' => isset($footerBgImage) ? $footerBgImage : $data->footer_section_bg_img
        ]
    );

    $request->session()->flash('success', __('Information updated successfully') . '!');

    return redirect()->back();
  }
}
