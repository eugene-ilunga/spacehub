<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\City;
use App\Models\Country;
use App\Models\Form;
use App\Models\FormInput;
use App\Models\GlobalDay;
use App\Models\Package;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Models\SpaceHoliday;
use App\Models\SpaceReview;
use App\Models\SpaceService;
use App\Models\SpaceSubCategory;
use App\Models\SpaceWishlist;
use App\Models\State;
use App\Models\SubService;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class SpaceController extends Controller
{

  public function index(Request $request)
  {

    $spaceCategorySlug = $city = $guestCapacity = $eventDate = $location = $keyword = $searchFromHome = null;

    $sellerInfo = Seller::select('id')->get();
    $allSpaceIds = [];

    foreach ($sellerInfo as $sellerId) {
      $spaceIds = Package::getSpaceIdsBySeller($sellerId->id);
      $allSpaceIds = array_merge($allSpaceIds, $spaceIds);
    }

    $s_spaceIds = [];
    $s_spaceIdsForCity = [];
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_spaces as meta_keywords', 'meta_description_spaces as meta_description')->first();

    $data['pageHeading'] = $misc->getPageHeading($language);
    $data['currencyInfo'] = $this->getCurrencyInfo();

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
    $categories = $language->spaceCategory()
      ->where('status', 1)
      ->whereIn('id', $categoryIds)
      ->orderBy('serial_number', 'asc')
      ->get();

    // Retrieve unique sub_category_ids from space content table
    $subCategoryIds = SpaceContent::leftJoin('spaces', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', $language->id],
        ['spaces.space_status', 1],
        ['space_contents.sub_category_id', '!=', null],
      ])
      ->whereIn('spaces.id', $allSpaceIds)
      ->distinct()
      ->pluck('sub_category_id')
      ->toArray();

    // Map subcategories to categories
    $categories->map(function ($spaceCategory) use ($subCategoryIds) {
      $spaceCategory->subcategories = $spaceCategory->subcategory()
        ->where('status', 1)
        ->whereIn('id', $subCategoryIds)
        ->orderBy('serial_number', 'asc')
        ->get();
    });


    $data['categories'] = $categories;

    $data['sellers'] = Seller::select('sellers.username', 'sellers.id')
      ->leftJoin('memberships', 'sellers.id', '=', 'memberships.seller_id')
      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('sellers.id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })->orWhere('sellers.id', 0);
      })
      ->where('sellers.username', '!=', 'admin')
      ->where('sellers.status', 1)
      ->get();

    $data['countries'] = Country::select('id', 'name')
      ->where([
        ['language_id', $language->id],
        ['status', 1],
      ])->get();

    $data['states'] = State::select('id', 'name')
      ->where([
        ['language_id', $language->id],
        ['status', 1],
      ])->get();

    $data['cities'] = City::select('id', 'name')
      ->where([
        ['language_id', $language->id],
        ['status', 1],
      ])->get();

    // for title
    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }


    if ($request->filled('category')) {
      $spaceCategorySlug = $request['category'];
    }

    // this input come from home searching form to apply condition in the space-filter js file
    if ($request->filled('search_from_home')) {
      $searchFromHome = $request['search_from_home'];
    }


    $location_spaceIds = [];
    $bs = Basic::select('google_map_api_key_status', 'google_map_radius')->first();
    $radius = $bs->google_map_status == 1 ? $bs->google_map_radius : 5000;

    if ($request->filled('location')) {
      $location = $request->input('location');

      $location_spaceIds = SpaceContent::query()
        ->where('space_contents.language_id', $language->id)
        ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
        ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
        ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
        ->where(function ($query) use ($location) {
          $query->where('space_contents.address', 'like', '%' . $location . '%')
            ->orWhere('countries.name', 'like', '%' . $location . '%')
            ->orWhere('states.name', 'like', '%' . $location . '%')
            ->orWhere('cities.name', 'like', '%' . $location . '%');
        })
        ->distinct()
        ->pluck('space_contents.space_id')
        ->toArray();
    }

    if ($request->filled('guest_capacity')) {
      $guestCapacity = $request['guest_capacity'];
    }

    if ($request->filled('city')) {
      $city = $request['city'];
    }
    if ($request->filled('city')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.city_id', $city)
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $s_spaceIds)) {
          array_push($s_spaceIdsForCity, $s_space_content->space_id);
        }
      }
    }

    $space_spaceIds = [];
    if ($request->filled('keyword')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.title', 'like', '%' . $keyword . '%')
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $space_spaceIds)) {
          array_push($space_spaceIds, $s_space_content->space_id);
        }
      }
    }

    // Retrieve the space that is displaying the feature section on the space page of the website.
    $featuredSpaces = Space::query()->select(
      'spaces.id as space_id',
      'spaces.space_rent',
      'spaces.rent_per_hour',
      'spaces.price_per_day',
      'spaces.space_type',
      'spaces.latitude',
      'spaces.longitude',
      'spaces.average_rating',
      'spaces.seller_id',
      'spaces.thumbnail_image as image',
      'spaces.max_guest',
      'spaces.min_guest',
      'spaces.use_slot_rent',
      'spaces.space_status as status',
      'space_contents.id as space_content_id',
      'space_contents.title',
      'space_contents.slug',
      'space_contents.space_category_id',
      'space_contents.address',
      'space_categories.id as category_id',
      'space_categories.icon as category_icon',
      'space_categories.name as category_title',
      'space_categories.slug as category_slug',
      'countries.id as country_id',
      'countries.name as country_name',
      'cities.id as city_id',
      'cities.name as city_name',
      'states.id as state_id',
      'states.name as state_name',
      'sellers.id as seller_id',
      'sellers.photo as seller_image',
      'sellers.username',
      'space_features.id as space_feature_id',
      'space_features.booking_status as space_feature_status',
      'space_features.end_date as feature_end_date'
    )

      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')
      ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
      ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
      ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
      ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
      ->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')
      ->leftJoin('space_features', function ($join) {
        $join->on('spaces.id', '=', 'space_features.space_id')
          ->where(function ($query) {
            $query->where('space_features.end_date', '>', Carbon::now())
              ->orWhereNull('space_features.end_date');
          })
          ->where('space_features.booking_status', '=', 'approved');
      })
      ->whereIn('spaces.id', $allSpaceIds)
      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('spaces.seller_id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })
          ->orWhere('spaces.seller_id', '=', 0);
      })
      ->where([
        ['spaces.space_status', '=', 1],
        ['space_contents.language_id', '=', $language->id],
        ['space_categories.status', '=', 1],
      ])
      ->when($city, function (Builder $query) use ($s_spaceIdsForCity) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCity);
      })
      ->when($keyword, function (Builder $query) use ($space_spaceIds) {
        return $query->whereIn('spaces.id', $space_spaceIds);
      })

      ->when($location, function ($query) use ($location_spaceIds, $radius) {
        if (empty($location_spaceIds)) {
          return $query->whereRaw('1=0');
        }

        return $query->whereIn('spaces.id', $location_spaceIds)
          ->whereRaw("
            (6371000 * acos(
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.longitude) - radians(spaces.longitude)) +
                sin(radians(spaces.latitude)) *
                sin(radians(spaces.latitude))
            )) < ?
        ", [$radius]);
      })

      ->when($keyword, function (Builder $query) use ($keyword) {
        return $query->where('space_contents.address', 'LIKE', '%' . $keyword . '%');
      })

      ->when($spaceCategorySlug, function (Builder $query, $spaceCategorySlug) {
        $category = SpaceCategory::query()->where([
          ['slug', '=', $spaceCategorySlug],
          ['status', '=', 1],
        ])->first();

        return $query->where('space_contents.space_category_id', '=', $category->id);
      })
      ->whereNotNull('space_features.id')
      ->inRandomOrder()
      ->take(3)
      ->get();


    $numOfFeaturedSpaces = $featuredSpaces->count();
    $numRegularSpaces = max(0, 12 - $numOfFeaturedSpaces);

    $featuredSpaceIds = $featuredSpaces->pluck('space_id')->toArray();

    // Retrieve the space that is displaying the non-feature section on the space page of the website.
    $spaces = Space::query()->select(
      'spaces.id as space_id',
      'spaces.space_rent',
      'spaces.rent_per_hour',
      'spaces.price_per_day',
      'spaces.space_type',
      'spaces.latitude',
      'spaces.longitude',
      'spaces.average_rating',
      'spaces.seller_id',
      'spaces.thumbnail_image as image',
      'spaces.max_guest',
      'spaces.use_slot_rent',
      'spaces.min_guest',
      'spaces.space_status as status',
      'space_contents.id as space_content_id',
      'space_contents.title',
      'space_contents.slug',
      'space_contents.space_category_id',
      'space_contents.address',
      'space_categories.id as category_id',
      'space_categories.icon as category_icon',
      'space_categories.name as category_title',
      'space_categories.slug as category_slug',
      'countries.id as country_id',
      'countries.name as country_name',
      'cities.id as city_id',
      'cities.name as city_name',
      'states.id as state_id',
      'states.name as state_name',
      'sellers.id as seller_id',
      'sellers.photo as seller_image',
      'sellers.username'
    )

      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')
      ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
      ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
      ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
      ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
      ->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')
      ->where([
        ['spaces.space_status', '=', 1],
        ['space_contents.language_id', '=', $language->id],
        ['space_categories.status', '=', 1],
      ])
      ->whereIn('spaces.id', $allSpaceIds)
      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('spaces.seller_id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })
          ->orWhere('spaces.seller_id', '=', 0);
      })
      ->when($guestCapacity, function (Builder $query) use ($guestCapacity) {
        return $query->where(function ($subquery) use ($guestCapacity) {
          $subquery->where('spaces.max_guest', '>=', $guestCapacity)
            ->Where('spaces.min_guest', '<=', $guestCapacity);
        });
      })

      ->when($spaceCategorySlug, function (Builder $query, $spaceCategorySlug) {
        $category = SpaceCategory::query()->where('slug', '=', $spaceCategorySlug)->first();

        return $query->where('space_contents.space_category_id', '=', $category->id);
      })
      ->when($city, function (Builder $query) use ($s_spaceIdsForCity) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCity);
      })
      ->when($keyword, function (Builder $query) use ($space_spaceIds) {
        return $query->whereIn('spaces.id', $space_spaceIds);
      })

      ->when($location, function ($query) use ($location_spaceIds, $radius) {
        if (empty($location_spaceIds)) {
          return $query->whereRaw('1=0');
        }

        return $query->whereIn('spaces.id', $location_spaceIds)
          ->whereRaw("
            (6371000 * acos(
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.longitude) - radians(spaces.longitude)) +
                sin(radians(spaces.latitude)) *
                sin(radians(spaces.latitude))
            )) < ?
        ", [$radius]);
      })

      ->whereNotIn('spaces.id', $featuredSpaceIds)
      ->orderBy('spaces.id', 'desc')
      ->paginate($numRegularSpaces);

    //total service count

    $featuredSpaceCount = $featuredSpaces->count();
    $spaceCount = $spaces->count();
    $data['total_spaces'] = $featuredSpaceCount + $spaceCount;

    // review count for without featured space 
    $spaces->map(function ($space) {
      $space['reviewCount'] = SpaceReview::where('space_id', $space->space_id)->count();
    });

    // review count for featured space
    $featuredSpaces->map(function ($space) {
      $space['reviewCount'] = SpaceReview::where('space_id', $space->space_id)->count();
    });

    // wishlist
    if (Auth::guard('web')->check() == true) {
      $spaces->map(function ($space) {
        $authUser = Auth::guard('web')->user();

        $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();
        if (!$listedSpace) {
          $space['wishlisted'] = false;
        } else {
          $space['wishlisted'] = true;
        }
        return $space;
      });

      // for featured space
      $featuredSpaces->map(function ($space) {
        $authUser = Auth::guard('web')->user();

        $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();
        if (!$listedSpace) {
          $space['wishlisted'] = false;
        } else {
          $space['wishlisted'] = true;
        }
        return $space;
      });
    }

    $data['spaces'] = $spaces;
    $data['featuredSpaces'] = $featuredSpaces;

    $data['language'] = $language;
    $data['spaceBookings'] = SpaceBooking::select('start_time', 'end_time', 'booking_type', 'booking_status', 'booking_date', 'space_id')->get();

    $priceRanges = collect([
      Space::min('space_rent'),
      Space::max('space_rent'),
      Space::min('rent_per_hour'),
      Space::max('rent_per_hour'),
      Space::min('price_per_day'),
      Space::max('price_per_day'),
      TimeSlot::min('time_slot_rent'),
      TimeSlot::max('time_slot_rent')
    ])->filter();

    $data['overallMin'] = $priceRanges->min();
    $data['overallMax'] = $priceRanges->max();
    $data['searchFromHome'] = $searchFromHome;


    return view('frontend.space.index', $data);
  }

  public function search_space(Request $request)
  {
    $allSpaceIds = [];
    $s_spaceIds = [];
    $s_spaceIdsForCountry = [];
    $s_spaceIdsForState = [];
    $s_spaceIdsForCity = [];
    $availableSpaceIdsFortimeSlot = [];
    $availableSpaceIdsForHourlySpace = [];
    $unavailableSpaceIdsForMultidaySpace = [];

    $keyword = $spaceCategorySlug = $spaceSubcategorySlug = $rating = $min = $max = $sorting = $city = $state = $country  = $guestCapacity = $spaceRent = $location  =  $spaceType = $inputStartTime  = $datetimeString = $dateRangeForMultiday = $startDateForMultiday = $endDateForMultiday =  null;


    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_spaces', 'meta_description_spaces')->first();
    $sellerInfo = Seller::select('id')->get();

    foreach ($sellerInfo as $sellerId) {
      $spaceIds = Package::getSpaceIdsBySeller($sellerId->id);
      $allSpaceIds = array_merge($allSpaceIds, $spaceIds);
    }

    $data['currencyInfo'] = $this->getCurrencyInfo();

    if ($request->filled('space_type')) {
      $spaceType = $request['space_type'];
    }

    // this code for space type 1
    if ($request->filled('event_date_and_time_for_fixed_time_slot')) {
      $inputStartTime = $request->input('event_date_and_time_for_fixed_time_slot');

      $bookingDate = date('Y-m-d', strtotime($inputStartTime));
      $startTime = date('H:i:s', strtotime($inputStartTime));
      $allSpaces = Space::where('space_status', 1)
        ->where('space_type', 1)
        ->get();

      foreach ($allSpaces as $space) {
        $availableSlots = $this->getAvailableTimeSlots($bookingDate, $startTime, $space->id);
        // If yes, collect this space ID
        if ($availableSlots->isNotEmpty()) {
          $availableSpaceIdsFortimeSlot[] = $space->id;
        }
      }
    }
    // this code for space type 2
    if ($request->filled('event_date_and_time_for_hourly_rental')) {

      $datetimeString = $request->input('event_date_and_time_for_hourly_rental');
      $customHour = $request->input('custom_hour', 1);

      $timeData = $this->getDateStartAndEndTimeFromDatetime($datetimeString, $customHour);
      $bookingDate = $timeData['date'];
      $startTime = $timeData['start_time'];
      $endTime = $timeData['end_time'];

      $allSpacesForHourly = Space::where('space_status', 1)
        ->where('space_type', 2)
        ->get();

      foreach ($allSpacesForHourly as $space) {
        $availableHourlySpace = $this->getAvailableHourlySpace($bookingDate, $startTime, $endTime, $space->id);
        // If yes, collect this space ID
        if ($availableHourlySpace) {
          $availableSpaceIdsForHourlySpace[] = $space->id;
        }
      }
    }

    //this code for space type 3
    $dateRangeForMultiday = $request->filled('start_date_for_multiday') && $request->filled('end_date_for_multiday');

    if ($dateRangeForMultiday) {

      $timezone = now()->timezoneName ?? config('app.timezone');

      $startDateForMultiday = Carbon::parse($request->input('start_date_for_multiday'))->timezone($timezone)->format('Y-m-d');
      $endDateForMultiday = Carbon::parse($request->input('end_date_for_multiday'))->timezone($timezone)->format('Y-m-d');

      $allSpacesForMultiday = Space::where('space_status', 1)
        ->where('space_type', 3)
        ->get();

      foreach ($allSpacesForMultiday as $space) {
        $availableHourlySpace = $this->getAvailableMultidaySpace($startDateForMultiday, $endDateForMultiday, $space->id, $space->similar_space_quantity);
        // If yes, collect this space ID
        if ($availableHourlySpace) {
          $unavailableSpaceIdsForMultidaySpace[] = $space->id;
        }
      }
    }


    if ($request->filled('space_rent')) {
      $spaceRent = $request['space_rent'];
    }

    // for category
    if ($request->filled('category')) {
      $spaceCategorySlug = $request['category'];
    }

    // for subcategory
    if ($request->filled('subcategory')) {
      $spaceSubcategorySlug = $request['subcategory'];
    }

    // for title
    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    // for guest capacity
    if ($request->filled('guest_capacity')) {
      $guestCapacity = $request['guest_capacity'];
    }

    //search by location
    $location_spaceIds = [];
    $bs = Basic::select('google_map_api_key_status', 'google_map_radius')->first();
    $radius = $bs->google_map_status == 1 ? $bs->google_map_radius : 5000;

    if ($request->filled('location')) {
      $location = $request->input('location');

      $location_spaceIds  = SpaceContent::query()
        ->where('space_contents.language_id', $language->id)
        ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
        ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
        ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
        ->where(function ($query) use ($location) {
          $query->where('space_contents.address', 'like', '%' . $location . '%')
            ->orWhere('countries.name', 'like', '%' . $location . '%')
            ->orWhere('states.name', 'like', '%' . $location . '%')
            ->orWhere('cities.name', 'like', '%' . $location . '%');
        })
        ->distinct()
        ->pluck('space_contents.space_id')
        ->toArray();
    }


    // for country
    if ($request->filled('country')) {
      $country = $request['country'];
    }

    if ($request->filled('country')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.country_id', $country)
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $s_spaceIds)) {
          array_push($s_spaceIdsForCountry, $s_space_content->space_id);
        }
      }
    }

    //for state
    if ($request->filled('state')) {
      $state = $request['state'];
    }

    if ($request->filled('state')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.state_id', $state)
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $s_spaceIds)) {
          array_push($s_spaceIdsForState, $s_space_content->space_id);
        }
      }
    }

    //for city
    if ($request->filled('city')) {
      $city = $request['city'];
    }

    if ($request->filled('city')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.city_id', $city)
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $s_spaceIds)) {
          array_push($s_spaceIdsForCity, $s_space_content->space_id);
        }
      }
    }

    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }

    if ($request->filled('rating')) {
      $rating = floatval($request['rating']);
    }
    if ($request->filled('sort')) {
      $sorting = $request['sort'];
    }

    if ($request->filled('keyword')) {
      $s_space_contents = SpaceContent::where('language_id', $language->id)
        ->where('space_contents.title', 'like', '%' . $keyword . '%')
        ->get();

      foreach ($s_space_contents as $s_space_content) {
        if (!in_array($s_space_content->space_id, $s_spaceIds)) {
          array_push($s_spaceIds, $s_space_content->space_id);
        }
      }
    }

    // Retrieve the space that is displaying the feature section on the space page of the website,using the searching parameter.
    $featuredSpaces = Space::query()->select(
      'spaces.id as space_id',
      'spaces.space_type',
      'spaces.space_rent',
      'spaces.rent_per_hour',
      'spaces.price_per_day',
      'spaces.latitude',
      'spaces.longitude',
      'spaces.average_rating',
      'spaces.seller_id',
      'spaces.thumbnail_image as image',
      'spaces.max_guest',
      'spaces.min_guest',
      'spaces.use_slot_rent',
      'spaces.space_status as status',
      'space_contents.id as space_content_id',
      'space_contents.title',
      'space_contents.slug',
      'space_contents.space_category_id',
      'space_contents.language_id',
      'space_contents.address',
      'space_contents.country_id as country_id',
      'space_categories.icon as category_icon',
      'space_categories.id as category_id',
      'space_categories.name as category_title',
      'space_categories.slug as category_slug',
      'countries.id as country_id',
      'countries.name as country_name',
      'cities.id as city_id',
      'cities.name as city_name',
      'states.id as state_id',
      'states.name as state_name',
      'sellers.id as seller_id',
      'sellers.photo as seller_image',
      'sellers.username',
      'space_features.id as space_feature_id',
      'space_features.booking_status as space_feature_status',
      'space_features.end_date as feature_end_date',
      'time_slot_min_rent.min_rent as min_rent'
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')
      ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
      ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
      ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
      ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
      ->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')
      ->leftJoin(DB::raw("(
              SELECT space_id, MIN(time_slot_rent) AS min_rent
              FROM time_slots
              GROUP BY space_id
          ) AS time_slot_min_rent"), 'spaces.id', '=', 'time_slot_min_rent.space_id')
      ->leftJoin('space_features', function ($join) {
        $join->on('spaces.id', '=', 'space_features.space_id')
          ->where(function ($query) {
            $query->where('space_features.end_date', '>', Carbon::now())
              ->orWhereNull('space_features.end_date');
          })
          ->where('space_features.booking_status', '=', 'approved');
      })
      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('spaces.seller_id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })
          ->orWhere('spaces.seller_id', '=', 0);
      })
      ->where([
        ['spaces.space_status', '=', 1],
        ['space_contents.language_id', '=', $language->id],
        ['space_categories.status', '=', 1],
      ])
      ->when($keyword, function (Builder $query) use ($s_spaceIds) {
        return $query->whereIn('spaces.id', $s_spaceIds);
      })
      ->whereIn('spaces.id', $allSpaceIds)
      ->when($inputStartTime, function (Builder $query) use ($availableSpaceIdsFortimeSlot) {
        return $query->whereIn('spaces.id', $availableSpaceIdsFortimeSlot);
      })
      ->when($datetimeString, function (Builder $query) use ($availableSpaceIdsForHourlySpace) {
        return $query->whereIn('spaces.id', $availableSpaceIdsForHourlySpace);
      })
      ->when($dateRangeForMultiday, function (Builder $query) use ($unavailableSpaceIdsForMultidaySpace) {
        return $query->whereNotIn('spaces.id', $unavailableSpaceIdsForMultidaySpace);
      })
      ->when($guestCapacity, function (Builder $query) use ($guestCapacity) {
        return $query->where(function ($subquery) use ($guestCapacity) {
          $subquery->where('spaces.max_guest', '>=', $guestCapacity)
            ->Where('spaces.min_guest', '<=', $guestCapacity);
        });
      })
      ->when($spaceCategorySlug, function (Builder $query, $spaceCategorySlug) {
        $category = SpaceCategory::query()->where('slug', '=', $spaceCategorySlug)->first();

        return $query->where('space_contents.space_category_id', '=', $category->id);
      })
      ->when($location, function ($query) use ($location_spaceIds, $radius) {
        if (empty($location_spaceIds)) {
          return $query->whereRaw('1=0');
        }

        return $query->whereIn('spaces.id', $location_spaceIds)
          ->whereRaw("
            (6371000 * acos(
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.longitude) - radians(spaces.longitude)) +
                sin(radians(spaces.latitude)) *
                sin(radians(spaces.latitude))
            )) < ?
        ", [$radius]);
      })
      ->when($spaceSubcategorySlug, function (Builder $query, $spaceSubcategorySlug) {
        $subcategory = SpaceSubCategory::query()->where('slug', '=', $spaceSubcategorySlug)->first();
        return $query->where('space_contents.sub_category_id', '=', $subcategory->id);
      })
      ->when($country, function (Builder $query) use ($s_spaceIdsForCountry) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCountry);
      })
      ->when($state, function (Builder $query) use ($s_spaceIdsForState) {
        return $query->whereIn('spaces.id', $s_spaceIdsForState);
      })
      ->when($city, function (Builder $query) use ($s_spaceIdsForCity) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCity);
      })

      ->when($request->filled('space_rent'), function ($query) use ($spaceRent) {
        if ($spaceRent == 'explore-all') {
          return $query;
        } elseif ($spaceRent == 'rentable_spaces') {
          return $query->where('spaces.booking_status', 0);
        } elseif ($spaceRent == 'negotiable') {
          return $query->where('spaces.booking_status', 1);
        }
      })

      ->when($rating, function (Builder $query, $rating) {
        return $query->where('spaces.average_rating', '>=', $rating);
      })

      ->when($spaceType, function (Builder $query, $spaceType) {
        return $query->where('spaces.space_type', '=', $spaceType)
          ->where('spaces.booking_status', '=', 0);
      })

      ->when($min && $max, function (Builder $query) use ($min, $max) {
        $query->whereRaw("
        CASE
            WHEN spaces.use_slot_rent = 1 THEN time_slot_min_rent.min_rent
            ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
        END BETWEEN ? AND ?
    ", [$min, $max]);
      })
      ->when($sorting, function (Builder $query, $sorting) {
        if ($sorting == 'new') {
          return $query->orderBy('spaces.created_at', 'desc');
        } elseif ($sorting == 'old') {
          return $query->orderBy('spaces.created_at', 'asc');
        } elseif ($sorting == 'lowToHigh') {
          return $query->orderByRaw('
            CASE 
              WHEN spaces.use_slot_rent = 1 THEN (
                SELECT MIN(time_slot_rent)
                FROM time_slots
                WHERE time_slots.space_id = spaces.id
                  AND time_slots.time_slot_rent IS NOT NULL
              )
              ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
            END ASC
        ');
        } elseif ($sorting == 'highToLow') {
          return $query->orderByRaw('
            CASE 
              WHEN spaces.use_slot_rent = 1 THEN (
                SELECT MIN(time_slot_rent)
                FROM time_slots
                WHERE time_slots.space_id = spaces.id
                  AND time_slots.time_slot_rent IS NOT NULL
              )
              ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
            END DESC
        ');
        } else {
          return $query->orderByDesc('spaces.id');
        }
      })
      ->whereNotNull('space_features.id')
      ->inRandomOrder()
      ->take(3)
      ->get();

    $numOfFeaturedSpaces = $featuredSpaces->count();
    $numRegularSpaces = max(0, 12 - $numOfFeaturedSpaces);

    $featuredSpaceIds = $featuredSpaces->pluck('space_id')->toArray();

    // Retrieve the space that is displaying the non-feature section on the space page of the website,using the searching parameter.
    $spaces = Space::query()->select(
      'spaces.id as space_id',
      'spaces.space_type',
      'spaces.space_rent',
      'spaces.rent_per_hour',
      'spaces.price_per_day',
      'spaces.latitude',
      'spaces.longitude',
      'spaces.average_rating',
      'spaces.seller_id',
      'spaces.thumbnail_image as image',
      'spaces.max_guest',
      'spaces.min_guest',
      'spaces.use_slot_rent',
      'spaces.space_status as status',
      'space_contents.id as space_content_id',
      'space_contents.title',
      'space_contents.language_id',
      'space_contents.slug',
      'space_contents.space_category_id',
      'space_contents.address',
      'space_contents.country_id as country_id',
      'space_categories.id as category_id',
      'space_categories.icon as category_icon',
      'space_categories.name as category_title',
      'space_categories.slug as category_slug',
      'countries.id as country_id',
      'countries.name as country_name',
      'cities.id as city_id',
      'cities.name as city_name',
      'states.id as state_id',
      'states.name as state_name',
      'sellers.id as seller_id',
      'sellers.photo as seller_image',
      'sellers.username',
      'time_slot_min_rent.min_rent as min_rent'
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')
      ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
      ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
      ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
      ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
      ->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')
      ->leftJoin(DB::raw("(
                SELECT space_id, MIN(time_slot_rent) AS min_rent
                FROM time_slots
                GROUP BY space_id
            ) AS time_slot_min_rent"), 'spaces.id', '=', 'time_slot_min_rent.space_id')
      ->where([
        ['spaces.space_status', '=', 1],
        ['space_contents.language_id', '=', $language->id],
        ['space_categories.status', '=', 1],
      ])
      ->when($keyword, function (Builder $query) use ($s_spaceIds) {
        return $query->whereIn('spaces.id', $s_spaceIds);
      })
      ->when($guestCapacity, function (Builder $query) use ($guestCapacity) {
        return $query->where(function ($subquery) use ($guestCapacity) {
          $subquery->where('spaces.max_guest', '>=', $guestCapacity)
            ->Where('spaces.min_guest', '<=', $guestCapacity);
        });
      })

      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('spaces.seller_id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })
          ->orWhere('spaces.seller_id', '=', 0);
      })
      ->when($spaceCategorySlug, function (Builder $query, $spaceCategorySlug) {
        $category = SpaceCategory::query()->where('slug', '=', $spaceCategorySlug)->first();

        return $query->where('space_contents.space_category_id', '=', $category->id);
      })
      ->when($spaceSubcategorySlug, function (Builder $query, $spaceSubcategorySlug) {
        $subcategory = SpaceSubCategory::query()->where('slug', '=', $spaceSubcategorySlug)->first();
        return $query->where('space_contents.sub_category_id', '=', $subcategory->id);
      })
      ->whereIn('spaces.id', $allSpaceIds)
      ->when($country, function (Builder $query) use ($s_spaceIdsForCountry) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCountry);
      })
      ->when($state, function (Builder $query) use ($s_spaceIdsForState) {
        return $query->whereIn('spaces.id', $s_spaceIdsForState);
      })
      ->when($city, function (Builder $query) use ($s_spaceIdsForCity) {
        return $query->whereIn('spaces.id', $s_spaceIdsForCity);
      })

      ->when($location, function ($query) use ($location_spaceIds, $radius) {
        if (empty($location_spaceIds)) {
          return $query->whereRaw('1=0');
        }

        return $query->whereIn('spaces.id', $location_spaceIds)
          ->whereRaw("
            (6371000 * acos(
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.latitude)) *
                cos(radians(spaces.longitude) - radians(spaces.longitude)) +
                sin(radians(spaces.latitude)) *
                sin(radians(spaces.latitude))
            )) < ?
        ", [$radius]);
      })

      ->when($request->filled('space_rent'), function ($query) use ($spaceRent) {
        if ($spaceRent == 'explore-all') {
          return $query;
        } elseif ($spaceRent == 'rentable_spaces') {
          return $query->where('spaces.booking_status', 0);
        } elseif ($spaceRent == 'negotiable') {
          return $query->where('spaces.booking_status', 1);
        }
      })


      ->when($rating, function (Builder $query, $rating) {
        return $query->where('spaces.average_rating', '>=', $rating);
      })

      ->when($spaceType, function (Builder $query, $spaceType) {
        return $query->where('spaces.space_type', '=', $spaceType)
          ->where('spaces.booking_status', '=', 0);
      })

      ->when($inputStartTime, function (Builder $query) use ($availableSpaceIdsFortimeSlot) {
        return $query->whereIn('spaces.id', $availableSpaceIdsFortimeSlot);
      })
      ->when($datetimeString, function (Builder $query) use ($availableSpaceIdsForHourlySpace) {
        return $query->whereIn('spaces.id', $availableSpaceIdsForHourlySpace);
      })
      ->when($dateRangeForMultiday, function (Builder $query) use ($unavailableSpaceIdsForMultidaySpace) {
        return $query->whereNotIn('spaces.id', $unavailableSpaceIdsForMultidaySpace);
      })
      ->when($min && $max, function (Builder $query) use ($min, $max) {
        $query->whereRaw("
        CASE
            WHEN spaces.use_slot_rent = 1 THEN time_slot_min_rent.min_rent
            ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
        END BETWEEN ? AND ?
    ", [$min, $max]);
      })
      ->when($sorting, function (Builder $query, $sorting) {
        if ($sorting == 'new') {
          return $query->orderBy('spaces.created_at', 'desc');
        } elseif ($sorting == 'old') {
          return $query->orderBy('spaces.created_at', 'asc');
        } elseif ($sorting == 'lowToHigh') {
          return $query->orderByRaw('
            CASE 
              WHEN spaces.use_slot_rent = 1 THEN (
                SELECT MIN(time_slot_rent)
                FROM time_slots
                WHERE time_slots.space_id = spaces.id
                  AND time_slots.time_slot_rent IS NOT NULL
              )
              ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
            END ASC
        ');
        } elseif ($sorting == 'highToLow') {
          return $query->orderByRaw('
            CASE 
              WHEN spaces.use_slot_rent = 1 THEN (
                SELECT MIN(time_slot_rent)
                FROM time_slots
                WHERE time_slots.space_id = spaces.id
                  AND time_slots.time_slot_rent IS NOT NULL
              )
              ELSE COALESCE(spaces.space_rent, spaces.rent_per_hour, spaces.price_per_day)
            END DESC
        ');
        } else {
          return $query->orderByDesc('spaces.id');
        }
      })

      ->whereNotIn('spaces.id', $featuredSpaceIds)
      ->orderBy('spaces.id', 'desc')
      ->paginate($numRegularSpaces);

    //total service count
    $featuredSpaceCount = $featuredSpaces->count();
    $spaceCount = $spaces->count();

    $spaces->map(function ($space) {
      $space['reviewCount'] = SpaceReview::where('space_id', $space->space_id)->count();
    });

    // review count for featured space
    $featuredSpaces->map(function ($space) {
      $space['reviewCount'] = SpaceReview::where('space_id', $space->space_id)->count();
    });

    // wishlist
    if (Auth::guard('web')->check() == true) {
      $spaces->map(function ($space) {
        $authUser = Auth::guard('web')->user();

        $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();
        if (!$listedSpace) {
          $space['wishlisted'] = false;
        } else {
          $space['wishlisted'] = true;
        }
        return $space;
      });

      // for featured space 
      $featuredSpaces->map(function ($space) {
        $authUser = Auth::guard('web')->user();

        $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();
        if (!$listedSpace) {
          $space['wishlisted'] = false;
        } else {
          $space['wishlisted'] = true;
        }
        return $space;
      });
    }

    $data['spaces'] = $spaces;
    $data['featuredSpaces'] = $featuredSpaces;
    $data['total_spaces'] = $featuredSpaceCount + $spaceCount;

    $renderedHtml = view('frontend.space.search-result', $data)->render();

    return response()->json([
      'render' => $renderedHtml,
      'spaces' => $data['spaces'],
      'featuredSpaces' => $data['featuredSpaces']
    ]);
  }

  public function updateWishlist(Request $request, $id)
  {
    Space::query()->where('id', $id)->firstOrFail();

    if (Auth::guard('web')->check() == false) {
      $request->session()->put('redirectTo', url()->previous());
      return response()->json([
        'user_login_route' => route('user.login')
      ]);
    } else {
      $user = Auth::guard('web')->user();
      $spaceId = $id;
      $spaceWishlist = SpaceWishlist::where([
        ['user_id', $user->id],
        ['space_id', $spaceId],
      ])->first();

      if (empty($spaceWishlist)) {
        SpaceWishlist::query()->create([
          'user_id' => $user->id,
          'space_id' => $spaceId,
        ]);
        return response()->json([
          'message' => __('Space added to wishlist') . '.',
          'status' => 'Added',
          'space_id' => $spaceId,
        ]);
      } else {
        $spaceWishlist->delete();

        return response()->json([
          'message' => __('Space removed from wishlist') . '.',
          'status' => 'Removed'
        ]);
      }
    }
  }

  public function spaceDetails($slug, $id)
  {

    $space = Space::where('id', $id)->firstOrFail();
    $data['holiday_date'] = SpaceHoliday::where('seller_id', $space->seller_id)->get();

    $spaceIds = Package::getSpaceIdsBySeller($space->seller_id);

    if (!in_array($id, $spaceIds)) {
      return view('errors.404');
    }


    if ($space->space_type == 3) {

      $quantity = $space->similar_space_quantity ?? 0;

      $data['bookedMultidaySpace'] = $this->getBookedDateForMultiDay($id, $quantity);
    } else {
      $data['bookedMultidaySpace'] = [];
    }


    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $data['currencyInfo'] = $this->getCurrencyInfo();
    $data['pageHeading'] = $misc->getPageHeading($language);
    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_space_details', 'meta_description_space_details')->first();

    $spaceContent = Space::query()->select(
      'spaces.id as space_id',
      'spaces.seller_id',
      'spaces.space_size',
      'spaces.space_rent',
      'spaces.rent_per_hour',
      'spaces.price_per_day',
      'spaces.slider_images',
      'spaces.similar_space_quantity',
      'spaces.address',
      'spaces.space_type',
      'spaces.price_per_day',
      'spaces.rent_per_hour',
      'spaces.opening_time',
      'spaces.closing_time',
      'spaces.prepare_time',
      'spaces.thumbnail_image as image',
      'spaces.space_status as status',
      'spaces.max_guest',
      'spaces.min_guest',
      'spaces.use_slot_rent',
      'space_contents.id as space_content_id',
      'space_contents.title',
      'space_contents.slug',
      'space_contents.address',
      'space_contents.description',
      'space_contents.amenities',
      'space_contents.space_category_id',
      'space_contents.sub_category_id',
      'space_contents.get_quote_form_id',
      'space_contents.tour_request_form_id',
      'space_contents.meta_keywords',
      'space_contents.meta_description',
      'countries.id as country_id',
      'countries.name as country_name',
      'cities.id as city_id',
      'cities.name as city_name',
      'states.id as state_id',
      'states.name as state_name'
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
      ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
      ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
      ->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')

      ->where(function ($query) {
        $query->where(function ($q) {
          $q->where('spaces.seller_id', '!=', 0)
            ->where('memberships.status', 1)
            ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
        })
          ->orWhere('spaces.seller_id', '=', 0);
      })
      ->where([
        ['spaces.space_status', '=', 1],
        ['space_contents.language_id', '=', $language->id],
        ['space_contents.space_id', '=', $id],
      ])
      ->firstOrFail();

    if (!empty($spaceContent) && $spaceContent->space_type == 3) {
      $spaceBookings = SpaceBooking::where('space_id', $id)->select('start_date', 'end_date', 'booking_type', 'booking_status', 'booking_date')->get();
    }
    if (!empty($spaceContent) && $spaceContent->space_type == 2) {
      $spaceBookings = SpaceBooking::where('space_id', $id)->select('start_time', 'end_time',  'booking_type', 'booking_status', 'booking_date')->get();
    }
    if (!empty($spaceContent) && $spaceContent->space_type == 1) {
      $spaceBookings = SpaceBooking::where('space_id', $id)->select('start_time', 'end_time', 'booking_type', 'booking_status', 'booking_date')->get();
    }

    if (!empty($spaceContent)) {
      $data['serviceContentsWithSubservice'] = SpaceService::query()
        ->select(
          'space_services.*',
          'space_service_contents.title as service_title',
          'space_service_contents.slug'
        )
        ->join('space_service_contents', 'space_services.id', '=', 'space_service_contents.space_service_id')
        ->with(['subServices' => function ($query) use ($language) {
          $query->select('sub_services.*', 'sub_service_contents.title as sub_service_title')
            ->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
            ->where('sub_service_contents.language_id', $language->id)
            ->where('sub_services.status', 1);
        }])
        ->where([
          ['space_services.status', '=', 1],
          ['space_services.space_id', '=', $id],
          ['space_services.has_sub_services', '=', 1],
          ['space_service_contents.language_id', '=', $language->id],
        ])
        ->orderBy('serial_number', 'desc')
        ->get();

      $data['serviceContentsWithoutSubservice'] = SpaceService::query()->select(
        'space_services.*',
        'space_service_contents.title as title_without_subservice',
        'space_service_contents.slug'
      )
        ->join('space_service_contents', 'space_services.id', '=', 'space_service_contents.space_service_id')
        ->where([
          ['space_services.status', '=', 1],
          ['space_services.seller_id', '=', $spaceContent->seller_id],
          ['space_services.space_id', '=', $id],
          ['space_services.has_sub_services', '=', 0],
          ['space_service_contents.language_id', '=', $language->id],
        ])
        ->orderBy('serial_number', 'desc')
        ->get();

      // Fetch subservice data separately
      if (!empty($data['serviceContentsWithSubservice'])) {
        $servicesIds = $data['serviceContentsWithSubservice']->pluck('id');
        $data['subservices'] = SubService::query()
          ->select(
            'sub_services.*',
            'sub_service_contents.title as subservice_title',
            'sub_service_contents.slug',
            'sub_service_contents.language_id'
          )
          ->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
          ->whereIn('sub_services.service_id', $servicesIds)
          ->where('sub_service_contents.language_id', '=', $language->id)
          ->where('sub_services.status', '=', 1)
          ->orderBy('sub_services.id', 'desc')
          ->get();

        $reviews = $space->review()->orderByDesc('id')->get();
        $data['reviewCount'] = $reviews->count();
        $data['averageRating'] = $reviews->avg('rating');
        $reviews->map(function ($review) {
          $review['user'] = $review->user()->first();
        });
        $weekendDays = GlobalDay::query()
          ->select('order', 'is_weekend', 'name')
          ->where([
            ['space_id', '=', $spaceContent->space_id],
            ['is_weekend', '=', 1],
          ])
          ->get();


        if (isset($spaceContent) && !empty($spaceContent)) {
          $form = Form::query()->where('id', $spaceContent->get_quote_form_id)->first();
        }
        if ($form) {
          $data['inputFields'] = FormInput::query()->where('form_id', $form->id)->orderBy('order_no', 'asc')->get();
        } else {
          $data['inputFields'] = [];
        }
        //get tour request form inputs
        if (isset($spaceContent) && !empty($spaceContent)) {
          $form = Form::query()->where('id', $spaceContent->tour_request_form_id)->first();
        }
        if ($form) {
          $data['tourInputFields'] = FormInput::query()->where('form_id', $form->id)->orderBy('order_no', 'asc')->get();
        } else {
          $data['tourInputFields'] = [];
        }

        $data['spaceContent'] = $spaceContent ?? null;

        $data['reviews'] = $reviews ?? null;
        $data['space'] = $space ?? null;
        $data['weekendDays'] = $weekendDays ?? null;
        $data['spaceUnit'] = Basic::select('space_units')->first();
        $data['spaceBooking'] = $spaceBookings ?? null;
        $data['quantity']  = $spaceContent->similar_space_quantity ?? 1;
        $data['sliderImages']  = json_decode($spaceContent->slider_images) ?? null;

        $bs = Basic::select('time_format')->first();
        $adminTimeFormat = $bs->time_format;
        $data['opening_time'] = formatTimeBasedOnAdminPreference($spaceContent->opening_time, $adminTimeFormat);
        $data['closing_time'] = formatTimeBasedOnAdminPreference($spaceContent->closing_time, $adminTimeFormat);


        // this code fetch space related data in space details space

        $relatedSpaces = DB::table('space_contents')
          ->join('spaces', 'space_contents.space_id', '=', 'spaces.id')
          ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
          ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
          ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
          ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')

          ->leftJoin('memberships', function ($join) {
            $join->on('spaces.seller_id', '=', 'memberships.seller_id')
              ->where('spaces.seller_id', '!=', 0);
          })
          ->select(
            'space_contents.*',
            'spaces.*',
            'space_categories.slug as category_slug',
            'space_categories.name as category_title',
            'countries.id as country_id',
            'countries.name as country_name',
            'cities.id as city_id',
            'cities.name as city_name',
            'sellers.id as seller_id',
            'sellers.photo as seller_image',
            'sellers.username'
          )
          ->where('space_contents.language_id', $language->id)
          ->where('spaces.space_status', 1)
          ->where('spaces.space_type', $spaceContent->space_type)
          ->where('space_contents.space_id', '<>', $spaceContent->space_id)
          ->where('space_contents.space_category_id', $spaceContent->space_category_id)
          ->where(function ($query) {
            $query->where(function ($q) {
              $q->where('spaces.seller_id', '!=', 0)
                ->where('memberships.status', 1)
                ->whereDate('memberships.start_date', '<=', now()->format('Y-m-d'))
                ->whereDate('memberships.expire_date', '>=', now()->format('Y-m-d'));
            })
              ->orWhere('spaces.seller_id', '=', 0);
          })
          ->orderBy('space_contents.space_id', 'desc')
          ->distinct()
          ->get();

        $relatedSpaces->map(function ($space) {
          $space->reviewCount = SpaceReview::where('space_id', $space->space_id)->count();
        });


        // wishlist
        if (Auth::guard('web')->check() == true) {
          $relatedSpaces->map(function ($space) {
            $authUser = Auth::guard('web')->user();

            $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();
            if (!$listedSpace) {
              $space->wishlisted = false;
            } else {
              $space->wishlisted = true;
            }
            return $space;
          });
        }
        $data['relatedSpaces'] = $relatedSpaces;


        return view('frontend.space.details', $data);
      }
    }
  }

  public function storeReview(Request $request, $id)
  {
    $rule = [
      'rating' => 'required'
    ];
    $validator = Validator::make($request->all(), $rule);
    if ($validator->fails()) {
      return redirect()->back()
        ->with('error', __('The rating field is required for service review') . '.')
        ->withInput();
    }

    $user = Auth::guard('web')->user();

    // Check if the user has already provided a review for this service
    $existingReview = SpaceReview::where('user_id', $user->id)
      ->where('space_id', $id)
      ->first();

    if ($existingReview) {
      // Update the existing review
      $existingReview->update([
        'rating' => $request->rating,
        'comment' => $request->comment
      ]);
      $request->session()->flash('success', __('Your review has been updated successfully') . '.');
    } else {
      // Create a new review
      $review = SpaceReview::create([
        'user_id' => $user->id,
        'space_id' => $id,
        'rating' => $request->rating,
        'comment' => $request->comment
      ]);
      $request->session()->flash('success', __('Your review has been submitted successfully') . '.');
    }

    // Update the average rating of the service
    $avgRating = SpaceReview::where('space_id', $id)->avg('rating');
    $service = Space::find($id);
    $service->update([
      'average_rating' => $avgRating
    ]);

    return redirect()->back();
  }

  public function getStatesByCountry(Request $request)
  {
   
    $countryId = $request->input('country_id');
    $languageId = $request->input('language_id');

    if ($countryId === null) {
      // Get all states
      $states = State::select('id', 'name')
        ->where('language_id', $languageId)
        ->get();
    } else {
      $states = State::select('id', 'name')
        ->where('country_id', $countryId)
        ->get();
    }
   
    if ($states->isEmpty()) {
      return response()->json([
        'states' => [],
        'error' => __('No states found for the selected country') . '.'
      ], 200);
    }

    return response()->json([
      'states' => $states
    ], 200);
  }


  public function getCitiesByState(Request $request)
  {

    $stateId = $request->input('state_id');
    $countryId = $request->input('country_id');
    $languageId = $request->input('language_id');

    // Default empty collection
    $cities = collect();

    if (!empty($stateId)) {
      // Filter by state_id and language_id
      $cities = City::select('id', 'name')
        ->where('state_id', $stateId)
        ->where('language_id', $languageId)
        ->get();
    } elseif (!empty($countryId)) {
      // Filter by country_id and language_id (if state_id is not given)
      $cities = City::select('id', 'name')
        ->where('country_id', $countryId)
        ->where('language_id', $languageId)
        ->get();
    }

    return response()->json([
      'cities' => $cities
    ]);
  }

  //this function fetches space data based on the space type for filtering 
  public function fetchSpaceData(Request $request)
  {
    $spaceType = $request->get('spaceType');

    // Initialize the query
    $query = SpaceBooking::leftJoin('spaces', 'spaces.id', '=', 'space_bookings.space_id')
      ->leftJoin('time_slots', 'time_slots.id', '=', 'space_bookings.time_slot_id')
      ->where('space_bookings.booking_type', '=', $spaceType);

    // Conditionally apply whereNotNull based on spaceType
    if ($spaceType == 2) {
      $query->whereNotNull('space_bookings.start_time')
        ->whereNotNull('space_bookings.end_time')
        ->select(
          'space_bookings.space_id',
          'spaces.space_type',
          'spaces.booking_status',
          'spaces.similar_space_quantity as quantity',
          'space_bookings.booking_date',
          'space_bookings.booking_status',
          'space_bookings.start_time',
          'space_bookings.end_time',
          'space_bookings.booking_type'
        );
    } elseif ($spaceType == 3) {
      $query->whereNotNull('space_bookings.start_date')
        ->whereNotNull('space_bookings.end_date')
        ->select(
          'space_bookings.space_id',
          'spaces.space_type',
          'spaces.booking_status',
          'spaces.similar_space_quantity as quantity',
          'space_bookings.booking_date',
          'space_bookings.booking_status',
          'space_bookings.start_date',
          'space_bookings.end_date',
          'space_bookings.booking_type'
        );
    } elseif ($spaceType == 1) {
      $query->whereNotNull('space_bookings.booking_date')
        ->whereNotNull('space_bookings.time_slot_id')
        ->whereNotNull('space_bookings.start_time')
        ->select(
          'space_bookings.space_id',
          'spaces.space_type',
          'spaces.booking_status',
          'time_slots.number_of_booking as quantity',
          'space_bookings.time_slot_id',
          'space_bookings.booking_date',
          'space_bookings.booking_status',
          'space_bookings.start_time',
          'space_bookings.end_time',
          'space_bookings.booking_type'
        );
    }

    // Execute the query and return the results
    $spaces = $query->get();
    $timeSlotInfo = TimeSlot::get();

    return response()->json([
      'space' => $spaces,
      'timeSlot' => $timeSlotInfo
    ]);
  }


  // this function fetches available time slots for a given booking date, start time, and space ID
  private function getAvailableTimeSlots($bookingDate, $startTime, $space_id)
  {

    $timezone = now()->timezoneName ?? config('app.timezone');
    $settings = Basic::select('time_format')->first();
    $timeFormat = $settings && $settings->time_format === '12h' ? 'h:i A' : 'H:i';

    // Convert $startTime (assumed to be "H:i:s" in UTC perhaps) to admin timezone format string
    $startTimeFormatted = null;
    if ($startTime) {
      // Create Carbon instance from "H:i:s" string assuming UTC first 
      $startTimeFormatted = Carbon::createFromFormat('H:i:s', $startTime, $timezone)
        ->format($timeFormat);
    }

    $selectedDate = Carbon::parse($bookingDate, $timezone);
    $parsedBookingDate = $selectedDate->format('Y-m-d');
    $dayOfWeek = $selectedDate->dayOfWeek;

    $day = GlobalDay::select('id', 'name', 'start_of_week')
      ->where('start_of_week', $dayOfWeek)
      ->where('space_id', $space_id)
      ->first();

    if (!$day) {
      return collect();
    }

    $bookedTimeSlots = SpaceBooking::query()
      ->select('time_slot_id', DB::raw('COUNT(*) as booking_count'))
      ->where('space_id', $space_id)
      ->where('booking_date', $parsedBookingDate)
      ->where('start_time', $startTimeFormatted)
      ->where('booking_status', '!=', 'rejected')
      ->groupBy('time_slot_id')
      ->get();

    // Convert the start time to UTC for querying
    if ($startTime) {
      $startTimeFormattedUtc = Carbon::createFromFormat('H:i:s', $startTime)
        ->setTimezone('UTC')
        ->format('H:i:s');
    }

    $allTimeSlots = TimeSlot::query()
      ->select('id as time_slot_id', 'start_time', 'end_time', 'number_of_booking', 'time_slot_rent', 'global_day_id')
      ->where('space_id', $space_id)
      ->where('start_time', $startTimeFormattedUtc)
      ->where('global_day_id', $day->id)
      ->get();

    $arrayBookedId = [];
    foreach ($bookedTimeSlots as $bookedSlot) {
      $slot = $allTimeSlots->firstWhere('time_slot_id', $bookedSlot->time_slot_id);
      if ($slot && $bookedSlot->booking_count >= $slot->number_of_booking) {
        $arrayBookedId[] = $bookedSlot->time_slot_id;
      }
    }

    $now = now($timezone);
    $isToday = $parsedBookingDate === $now->format('Y-m-d');

    $availableTimeSlots = $allTimeSlots->filter(function ($slot) use ($arrayBookedId, $isToday, $timezone, $now) {
      if (in_array($slot->time_slot_id, $arrayBookedId)) {
        return false;
      }

      if ($isToday) {
        try {
          $slotStartTime = Carbon::createFromFormat('H:i:s', $slot->start_time, 'UTC')->setTimezone($timezone);
          if ($slotStartTime->lessThanOrEqualTo($now)) {
            return false;
          }
        } catch (\Exception $e) {
          return false;
        }
      }

      return true;
    })->values();
    return $availableTimeSlots;
  }

  // This function is designed to extract the end time (end_time) from a given datetime string by adding the date (date), start time (start_time), and custom hour for space type 2.
  public function getDateStartAndEndTimeFromDatetime($datetimeString, $customHour)
  {
    $timezone = now()->timezoneName ?? config('app.timezone');
    $startDateTime = Carbon::parse($datetimeString, $timezone)->setTimezone($timezone);

    return [
      'date' => $startDateTime->toDateString(),
      'start_time' => $startDateTime->format('H:i'),
      'end_time' => $startDateTime->copy()->addHours($customHour)->format('H:i'),
    ];
  }

  private function getAvailableHourlySpace($bookingDate, $startTime, $endTime, $spaceId)
  {

    $bookedSpaces = SpaceBooking::query()
      ->select('space_id', DB::raw('COUNT(*) as booking_count'))
      ->where('space_id', $spaceId)
      ->where('booking_date', $bookingDate)
      ->where('booking_status', '!=', 'rejected')
      ->where(function ($query) use ($startTime, $endTime) {
        $query->where('start_time', '<=', $endTime)
          ->where('end_time', '>=', $startTime);
      })
      ->groupBy('space_id')
      ->first();

    // Get the space's quantity
    $space = Space::where('id', $spaceId)
      ->where('space_type', 2)
      ->select('similar_space_quantity')
      ->first();

    $bookingCount = $bookedSpaces ? $bookedSpaces->booking_count : 0;
    $similarSpaceQuantity = $space ? $space->similar_space_quantity : 0;

    if ($similarSpaceQuantity <= $bookingCount) {
      return false;
    }

    return true;
  }

  public function getAvailableMultidaySpace($startDate, $endDate, $spaceId, $quantity = 0)
  {

    $start = $startDate;
    $end = $endDate;

    // Count overlapping bookings for the same space
    $count = SpaceBooking::where([
      ['space_id', $spaceId],
      ['booking_status', '!=', 'rejected']
    ])
      ->where(function ($query) use ($start, $end) {
        $query->whereBetween('start_date', [$start, $end])
          ->orWhereBetween('end_date', [$start, $end])
          ->orWhere(function ($q) use ($start, $end) {
            $q->where('start_date', '<=', $start)
              ->where('end_date', '>=', $end);
          });
      })
      ->count();

    if ($count >= $quantity) {
      return true;
    }

    return false;
  }


  // This function retrieves booked dates for multi-day spaces based on the space ID and a specified quantity to disable calendar date.
  public function getBookedDateForMultiDay($spaceId, $quantity = 0)
  {
    $adminTimezone = now()->timezoneName ?? config('app.timezone');
    $today = now()->setTimezone($adminTimezone);

    $bookings = SpaceBooking::where('space_id', $spaceId)
      ->where('end_date', '>=', $today)
      ->where('booking_status', '!=', 'rejected')
      ->select('start_date', 'end_date')
      ->get();

    $bookingCounts = [];

    foreach ($bookings as $booking) {
      $start = $booking->start_date;
      $end = $booking->end_date;

      // Count overlapping bookings for the same space
      $count = SpaceBooking::where([
        ['space_id', $spaceId],
        ['booking_status', '!=', 'rejected']
      ])
        ->where(function ($query) use ($start, $end) {
          $query->whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->orWhere(function ($q) use ($start, $end) {
              $q->where('start_date', '<=', $start)
                ->where('end_date', '>=', $end);
            });
        })
        ->count();

      if ($count >= $quantity) {
        $bookingCounts[] = [
          'bookingStartDate' => $start,
          'bookingEndDate'   => $end,
        ];
      }
    }

    return $bookingCounts;
  }
}
