<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Blog\Post;
use App\Models\City;
use App\Models\HomePage\AdditionalSection;
use App\Models\HomePage\PopularCitySection;
use App\Models\HomePage\Section;
use App\Models\HomePage\SectionContent;
use App\Models\MenuBuilder;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SpaceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $secInfo = Section::query()->first();

    $queryResult['secInfo'] = $secInfo;

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['language'] = $language;


    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home as meta_keywords', 'meta_description_home as meta_description')->first();

    if ($themeVersion == 1 || $themeVersion == 2 || $themeVersion == 3) {
      $queryResult['heroImg'] = Basic::query()->select('hero_section_foreground_img', 'hero_section_foreground_img_theme_3', 'hero_section_foreground_img_theme_3_left')->first();
      $queryResult['heroInfo'] = $language->heroStatic()->first();
      $queryResult['heroBgImg'] = Basic::query()->pluck('hero_section_background_img')->first();
      $queryResult['videoBannerImage'] = Basic::query()->pluck('video_banner_section_image')->first();
      $queryResult['videoBannerUrl'] = Basic::query()->pluck('video_banner_video_link')->first();
    } else {
      $queryResult['heroInfo'] = $language->heroStatic()->first();
    }

    // Call the getSpaceIds method to retrieve space IDs according to package features of vendor
    $sellerInfo = Seller::select('id')->get();

    //  stores the fesatured space IDs for all spaces that include the package feature, categorized by vendor and contains only space ids
    $allFeaturedSpaceIds = [];

    foreach ($sellerInfo as $sellerId) {
      $spaceIds = Package::getFeaturedSpaceIdsBySeller($sellerId->id);
      $allFeaturedSpaceIds = array_merge($allFeaturedSpaceIds, $spaceIds);
    }

    //  stores the space IDs for all spaces that include the package feature, categorized by vendor and contains only space ids
    $allSpaceIds = [];

    foreach ($sellerInfo as $sellerId) {
      $spaceIds = Package::getSpaceIdsBySeller($sellerId->id);
      $allSpaceIds = array_merge($allSpaceIds, $spaceIds);
    }

    // Retrieve unique category_ids from space content table
    $categoryIds = SpaceContent::leftJoin('spaces', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', $language->id],
        ['spaces.space_status', 1],
      ])
      ->whereIn('spaces.id', $allSpaceIds)
      ->distinct()
      ->pluck('space_category_id')
      ->toArray();


    // Retrieve category information for space  
    $queryResult['categories'] = $language->spaceCategory()
      ->where([
        ['status', 1],
        ['is_featured', 1],
      ])
      ->whereIn('id', $categoryIds)
      ->orderBy('serial_number', 'asc')
      ->get();


    // Retrieve unique featured category_ids from space content table 
    $featureCategoryIds = SpaceContent::leftJoin('spaces', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', $language->id],
        ['spaces.space_status', 1],
      ])
      ->whereIn('spaces.id', $allFeaturedSpaceIds)
      ->distinct()
      ->pluck('space_category_id')
      ->toArray();

    // Retrieve unique featured categories information according to featured category ids
    $queryResult['featuredCategories'] = $language->spaceCategory()
      ->whereIn('id', $featureCategoryIds)
      ->where([
        ['status', 1],
        ['is_featured', 1],
      ])->orderBy('serial_number', 'asc')->get();

    // this $allSpaceIds for category section 
    $queryResult['allSpaceIds'] = $allSpaceIds;

    // this $allFeaturedSpaceIds for featured space section according to featured categories 
    $queryResult['allFeaturedSpaceIds'] = $allFeaturedSpaceIds;

    if ($secInfo->about_section_status == 1) {
      $queryResult['aboutInfo'] = DB::table('basic_settings')->select('about_section_image', 'logo', 'about_section_video_link')->first();

      $queryResult['aboutData'] = $language->aboutSection()->first();
    }

    $queryResult['secTitle'] = $language->sectionTitle()->first();

    if ($secInfo->features_section_status == 1) {
      $queryResult['featureBgImg'] = Basic::query()->pluck('work_process_background_img')->first();
      $queryResult['allFeature'] = $language->workProcessSection()->orderBy('number')->get();
    }


    $queryResult['cities']  = City::query()
      ->select('cities.id as city_id', 'cities.name as city_name', 'cities.image', 'countries.name as country_name')
      ->leftJoin('countries', function ($join) use ($language) {
        $join->on('cities.country_id', '=', 'countries.id')
          ->where('countries.language_id', $language->id);
      })
      ->where([
        ['cities.status', 1],
        ['cities.is_featured', 1],
        ['cities.language_id', $language->id],
        ['countries.language_id', $language->id]
      ])
      ->inRandomOrder()
      ->get();

    $queryResult['citySectionInfo'] = PopularCitySection::where('language_id', $language->id)->select('text', 'button_name', 'title')->first();

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    if ($secInfo->testimonials_section_status == 1) {
      $queryResult['testimonialBgImg'] = Basic::query()->pluck('testimonial_bg_img')->first();
    }
    $queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
    $queryResult['testimonialClientImage1'] = $language->testimonial()->select('image')->orderByDesc('id')->first();
    $queryResult['testimonialClientImage2'] = $language->testimonial()->select('image')->first();

    if ($secInfo->blog_section_status == 1) {
      $queryResult['posts'] = Post::query()->join('post_informations', 'posts.id', '=', 'post_informations.post_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
        ->where('post_informations.language_id', '=', $language->id)
        ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.slug', 'post_informations.author', 'post_informations.content', 'posts.created_at')
        ->orderBy('posts.created_at', 'desc')
        ->inRandomOrder()
        ->limit(3)
        ->get();
    }
    // home section title information
    $queryResult['homeSectionInfo'] = SectionContent::where('language_id', $language->id)->first();

    if ($secInfo->space_banner_section_status == 1) {

      $queryResult['spaceBannerBgImg'] = Basic::query()->pluck('banner_section_bg_img')->first();
      $queryResult['spaceBannerForegroundImg'] = Basic::query()->pluck('banner_section_foreground_img')->first();
    }

    $queryResult['menuInfos'] = optional(MenuBuilder::where('language_id', $language->id)->first())->menus ?? json_encode([]);

   

    $queryResult['additionalSections'] = AdditionalSection::with(['contents' => function ($query) use ($language) {
      $query->where('language_id', $language->id);
    }])->get();



    if ($themeVersion == 1) {
      return view('frontend.home.index-v1', $queryResult);
    } else if ($themeVersion == 2) {
      return view('frontend.home.index-v2', $queryResult);
    } else if ($themeVersion == 3) {
      return view('frontend.home.index-v3', $queryResult);
    }
  }

  public function pricing()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $data['currencyInfo'] = $this->getCurrencyInfo();

    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_pricing as meta_keywords', 'meta_description_pricing as meta_description')->first();

    $data['pageHeading'] = $misc->getPageHeading($language);

    $data['breadcrumb'] = $misc->getBreadcrumb();
    $data['monthly_packages'] = Package::where([
      ['status', '1'],
      ['term', 'monthly']
    ])
      ->get();

    $data['yearly_packages'] = Package::where([
      ['status', '1'],
      ['term', 'yearly']
    ])
      ->get();

    $data['lifetime_packages'] = Package::where([
      ['status', '1'],
      ['term', 'lifetime']
    ])
      ->where('id', '<>', 999999)
      ->get();

    $packageFeature = Basic::query()->select('package_features')->first();
    $data['allPfeatures'] = json_decode($packageFeature->package_features, true);

    return view('frontend.pricing', $data);
  }
}
