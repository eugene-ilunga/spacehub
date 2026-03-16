<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Section;
use Illuminate\Support\Facades\DB;

class AboutUsController extends Controller
{
      public function index()
      {

            $misc = new MiscellaneousController();

            $language = $misc->getLanguage();

            $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_aboutus as meta_keywords', 'meta_description_aboutus as meta_description')->first();
            $queryResult['secInfo'] = Section::query()->first();
            $queryResult['secTitle'] = $language->sectionTitle()->first();
            $queryResult['pageHeading'] = $misc->getPageHeading($language);
            $queryResult['breadcrumb'] = $misc->getBreadcrumb();
            $queryResult['testimonialBgImg'] = Basic::query()->pluck('testimonial_bg_img')->first();
            $queryResult['aboutInfo'] = DB::table('basic_settings')->select('about_section_image', 'about_section_video_link')->first();

            $queryResult['aboutData'] = $language->aboutSection()->first();
            $queryResult['aboutContent'] = $language->aboutContent()->get();
            $queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
            $queryResult['allFeature'] = $language->workProcessSection()->orderBy('number')->get();
            $queryResult['testimonialClientImage1'] = $language->testimonial()->select('image')->orderByDesc('id')->first();
            $queryResult['testimonialClientImage2'] = $language->testimonial()->select('image')->first();

            return view('frontend.aboutus', $queryResult);
      }
}
