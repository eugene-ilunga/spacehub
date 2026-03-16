<?php

namespace App\Http\Controllers\Admin\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\PageHeading;
use App\Models\Language;
use App\Rules\ImageDimensions;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageHeadingController extends Controller
{
  public function pageHeadings(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;

    // then, get the page headings info of that language from db
    $information['data'] = $language->pageName()->first();

    // get all the languages from db
    $information['langs'] = Language::all();


    return view('admin.basic-settings.page-headings', $information);
  }

  public function breadcrumb(Request $request)
  {
    $data['abs'] = Basic::firstOrFail();
    return view('admin.basic-settings.breadcrumb', $data);
  }

  public function updatebreadcrumb(Request $request)
  {
    $img = $request->file('breadcrumb');

    $rules = [];

    if (is_null(DB::table('basic_settings')->where('uniqid', 12345)->value('breadcrumb'))) {

      $rules['breadcrumb'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(1600, 1920, 400, 450) 
      ];
    } elseif ($request->hasFile('breadcrumb')) {

      $rules['breadcrumb'] = [
        new ImageMimeTypeRule(),
        new ImageDimensions(1600, 1920, 400, 450)
      ];
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    if ($request->hasFile('breadcrumb')) {
      $path = './assets/img/';

      $currentBreadcrumb = DB::table('basic_settings')->where('uniqid', 12345)->value('breadcrumb');

      $filename = UploadFile::update($path, $img, $currentBreadcrumb);

      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['breadcrumb' => $filename]
      );
    }

    Session::flash('success', __('Updated Successfully'));
    return back();
  }

  public function updatePageHeadings(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();

    // then, get the page heading info of that language from db
    $heading = $language->pageName()->first();

    if (empty($heading)) {
      PageHeading::query()->create($request->except('language_id') + [
        'language_id' => $language->id
      ]);

    } else {
      $heading->update($request->all());
    }

    $request->session()->flash('success', __('Page headings updated successfully') . '!');

    return redirect()->back();
  }
}
