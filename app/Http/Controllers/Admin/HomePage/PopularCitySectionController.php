<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\PopularCitySection;
use App\Models\Language;
use Illuminate\Http\Request;


class PopularCitySectionController extends Controller
{
    public function index(Request $request){
      $language = Language::query()->where('code', '=', $request->language)->first();
      $information['language'] = $language;
      $information['data'] = PopularCitySection::where('language_id', $language->id)->first();


      $information['langs'] = Language::all();
      return view('admin.home-page.popular_city_section', $information);
    }
  public function updateInfo(Request $request)
  {

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    PopularCitySection::query()->updateOrCreate(
      ['language_id' => $language->id],
      ['title' => $request->title,
        'text' => $request->text,
        'button_name' => $request->button_name,
        ]
    );

    $request->session()->flash('success', ' Popular city  Information updated successfully!');

    return redirect()->back();
  }
}
