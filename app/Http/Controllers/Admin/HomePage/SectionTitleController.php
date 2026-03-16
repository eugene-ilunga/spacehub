<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\SectionTitle;
use App\Models\Language;
use Illuminate\Http\Request;

class SectionTitleController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->sectionTitle()->first();

    $information['langs'] = Language::all();

    $information['themeVersion'] = Basic::query()->pluck('theme_version')->first();

    return view('admin.home-page.section-titles', $information);
  }

  public function update(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    SectionTitle::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'category_section_title' => $request->category_section_title,
        'featured_space_section_title' => $request->featured_space_section_title,
        'testimonials_section_title' => $request->testimonials_section_title,
        'work_process_section_title' => $request->work_process_section_title,
        'popular_cities_section_title' => $request->popular_cities_section_title,
        'space_banner_section_title' => $request->space_banner_section_title
      ]
    );

    $request->session()->flash('success', __('Section titles updated successfully') . '!');

    return redirect()->back();
  }
}
