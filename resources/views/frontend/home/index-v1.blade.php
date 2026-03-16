@extends('frontend.layout')

@php
    $title = __('Home');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;

@endphp

@section('content')

    <!-- Home-area start-->
    <section class="hero-banner hero-banner_v1 header-next">
        <div class="hero-wrapper bg-img bg-cover"
            @if (!empty($heroBgImg)) data-bg-img="{{ asset('assets/img/' . $heroBgImg) }}"> @endif <div
            class="container">
            <div class="banner-content">
                @if (!empty($homeSectionInfo->hero_section_title))
                    <h1 class="title mb-20">
                        {{ $homeSectionInfo->hero_section_title }}
                    </h1>
                @endif
                @if (!empty($homeSectionInfo->hero_section_text))
                    <p class="text">{{ $homeSectionInfo->hero_section_text }}</p>
                @endif
            </div>
        </div>
        </div>
        <div class="hero-bottom">
            <div class="container">
                <div class="banner-filter-form">
                    <div class="form-wrapper p-20 border radius-md">
                        <form action="{{ route('space.index') }}" method="GET">
                            <input type="hidden" name="search_from_home" value="home">
                            <div class="grid">
                                <div class="item">
                                    <div class="form-group">
                                        <label for="guest">{{ __('Title') }}</label>
                                        <div class="form-block">
                                            <div class="icon">
                                                <i class="far fa-search"></i>
                                            </div>
                                            <input type="text" name="keyword" class="form-control guest-capacity-search"
                                                value="" autocomplete="off"
                                                placeholder="{{ __('Enter Space Title') . '...' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="form-group">
                                        <label for="type">{{ __('All Categories') }}</label>
                                        <div class="form-block">
                                            <div class="icon">
                                                <i class="far fa-clock"></i>
                                            </div>
                                            <select class="niceselect wide" id="category" name="category">
                                                @if (count($categories) > 0)
                                                    <option value="" selected>{{ __('Select Category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ @$category->slug }}">{{ @$category->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="form-group">
                                        <label for="place">{{ __('Location') }}</label>
                                        <div class="form-block">
                                            <div class="icon">
                                                <i class="far fa-map-marker-alt"></i>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" name="location" id="locationInput"
                                                    class="form-control input-search search-address"
                                                    placeholder="{{ __('Enter location') . '...' }}">
                                                    @if ($websiteInfo->google_map_api_key_status == 1)
                                                        <button type="button" class="btn btn-sm current-location"
                                                            id="currentLocationButton">
                                                            <i class="fas fa-crosshairs"></i>
                                                        </button>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item text-md-end">
                                    <button class="btn-icon radius-sm" type="submit" aria-label="button">
                                        <i class="far fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Home-area end -->
    <x-additional-section :sections="$additionalSections" position="hero_section" />

    <!-- Category-area home-1 start -->
    @if ($isActiveSection->space_category_section_status == 1)
        <section class="category-area category-area_v1 ptb-100">

            <div class="container">
                <div class="row">
                    <div class="col-12" data-aos="fade-up">
                        <div class="section-title title-center mb-50">
                            @if (!empty($homeSectionInfo->category_section_title))
                                <h2 class="title">{{ $homeSectionInfo->category_section_title }}</h2>
                            @else
                                <h2 class="title">{{ __('Our Popular Categories') }}</h2>
                            @endif
                        </div>
                    </div>
                    <div class="col-12" data-aos="fade-up">
                        <div class="row g-0">
                            @if (count($categories) > 0)
                                @foreach ($categories as $category)
                                    <div class="col-lg-3 col-md-6 item">
                                        <div class="card p-25 border">
                                            <div class="card_icon mb-25">
                                                <a href="{{ route('space.index', ['search_from_home' => 'home', 'category' => $category->slug]) }}"
                                                    title="{{ @$category->name }}" target="_self"
                                                    data-category_id="{{ @$category->id }}"
                                                    data-category_slug="{{ @$category->slug }}">
                                                    <img class="lazyload"
                                                        src="{{ asset('./assets/img/space-categories/' . $category->icon_image) }}"
                                                        data-src="{{ asset('./assets/img/space-categories/' . $category->icon_image) }}"
                                                        alt="Image">
                                                </a>
                                            </div>
                                            <div class="card_content">
                                                <h5 class="card_title mb-15">
                                                    <a href="{{ route('space.index', ['search_from_home' => 'home', 'category' => $category->slug]) }}"
                                                        target="_self" title="{{ @$category->name }}">
                                                        {{ @$category->name }}
                                                        <i class="far fa-arrow-right"></i>
                                                    </a>
                                                </h5>

                                                <div class="card_text click-show">
                                                    <div class="show-content">
                                                        <p>{{ @$category->category_description }}</p>
                                                    </div>
                                                    <div class="read-more-btn">
                                                        <span>{{ __('Read more') }}</span>
                                                        <span>{{ __('Read less') }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col">
                                        <h3 class="text-center mt-5">{{ __('Oops! No Categories Available Right Now.') }}
                                        </h3>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Category-area end -->
    <x-additional-section :sections="$additionalSections" position="space_category_section_status" />
    <!-- Space-area start -->
    @if ($isActiveSection->features_section_status == 1)
        <section class="space-area featured-space pb-75">
            <div class="container">
                <div class="section-title title-center row mb-50" data-aos="fade-up">
                    <div class="col-lg-12">     
                        @if (!empty($homeSectionInfo->featured_section_title))
                            <h2 class="title mb-30">{{ $homeSectionInfo->featured_section_title }}</h2>
                        @else
                            <h2 class="title mb-30">{{ __('Our Featured Spaces') }}</h2>
                        @endif
                    </div>
                    <div class="col-lg-12">  
                        <div class="tabs-navigation">
                            <ul class="nav nav-tabs" data-hover="fancyHover">
    
                                @if (count($featuredCategories) > 0)
                                    @foreach ($featuredCategories as $key => $category)
                                        <li class="nav-item @if ($key === 0) active @endif ">
                                            <button
                                                class="nav-link hover-effect @if ($key === 0) active @endif btn-md radius-sm"
                                                data-bs-toggle="tab" data-bs-target="#tab-{{ $category->id }}"
                                                type="button">{{ @$category->name }}</button>
                                        </li>
                                    @endforeach
                                @else
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="text-center mt-5">{{ __('No Features to Highlight Yet!') }}</h3>
                                        </div>
                                    </div>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="tab-content" data-aos="fade-up">
                    @foreach ($featuredCategories as $key => $category)
                        <div class="tab-pane slide show  @if ($key === 0) active @endif"
                            id="tab-{{ $category->id }}">
                            @php
                                $spaces = \App\Models\Space::query()
                                    ->select(
                                        'spaces.id as space_id',
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
                                        'spaces.space_status as status',
                                        'space_contents.id as space_content_id',
                                        'space_contents.title',
                                        'space_contents.slug',
                                        'space_contents.space_category_id',
                                        'space_contents.address',
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
                                        'spaces.use_slot_rent',
                                    )

                                    ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
                                    ->leftJoin(
                                        'space_categories',
                                        'space_contents.space_category_id',
                                        '=',
                                        'space_categories.id',
                                    )
                                    ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
                                    ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
                                    ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
                                    ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
                                    ->where([
                                        ['spaces.space_status', '=', 1],
                                        ['space_contents.language_id', '=', $language->id],
                                        ['space_contents.space_category_id', $category->id],
                                    ])
                                    ->whereIn('spaces.id', $allFeaturedSpaceIds)
                                    ->inRandomOrder()
                                    ->limit(4)
                                    ->get();
                                // review
                                $spaces->map(function ($space) {
                                    $space['reviewCount'] = \App\Models\SpaceReview::where(
                                        'space_id',
                                        $space->space_id,
                                    )->count();
                                });
                                // wishlist
                                if (Auth::guard('web')->check() == true) {
                                    $spaces->map(function ($space) {
                                        $authUser = Auth::guard('web')->user();

                                        $listedSpace = \App\Models\SpaceWishlist::query()
                                            ->where([['user_id', $authUser->id], ['space_id', $space->space_id]])
                                            ->first();

                                        if (empty($listedSpace)) {
                                            $space['wishlisted'] = false;
                                        } else {
                                            $space['wishlisted'] = true;
                                        }
                                    });
                                }
                            @endphp
                            @if ($spaces->isNotEmpty())
                                <div class="row">
                                    @foreach ($spaces as $space)
                                        <x-home.space-card :space="$space" :position="$position" :symbol="$symbol" />
                                    @endforeach

                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif
    <!-- Space-area end -->
    <x-additional-section :sections="$additionalSections" position="features_section_status" />

    <!-- Video-banner-area home-1 start -->
    <x-home.video-banner :status="$isActiveSection->video_banner_section_status" :image="$videoBannerImage" :video-link="$homeSectionInfo?->video_banner_video_link" variant="home-1" />
    <!-- Video-banner-area end -->
    <x-additional-section :sections="$additionalSections" position="video_banner_section_status" />

    <!-- Works-area start -->
    @if ($isActiveSection->work_process_section_status == 1)
        <section class="works-area works-area_v1 pt-100 pb-100" data-aos="fade-up">
            <div class="container">
                <div class="section-title title-center mb-20">
                    @if (!empty($homeSectionInfo->workprocess_section_title))
                        <h2 class="title">{{ $homeSectionInfo->workprocess_section_title }}</h2>
                    @else
                        <h2 class="title">{{ __('How Spacekoi Platform Work Perfectly') }}</h2>
                    @endif
                </div>
                @if (count($allFeature) > 0)
                    <div class="row">
                        @foreach ($allFeature as $feature)
                            <div class="col-lg-4 col-md-6 item mt-30">
                                <div class="card text-center border radius-md p-25">
                                    <div class=" large-icon card_icon mx-auto mb-25">
                                        <i class="{{ $feature->icon }}"></i>
                                    </div>
                                    <div class="card_content">
                                        <h5 class="card_title lc-1 mb-15">{{ @$feature->title }}</h5>
                                        <p class="card_text lc-2">
                                            {{ @$feature->description }}
                                        </p>
                                        <span
                                            class="card_number border radius-sm h3 mx-auto mt-20">{{ $feature->number }}</span>
                                    </div>
                                </div>
                          
                                @if (!$loop->last)
                                    <div class="arrow {{ $currentLanguageInfo->direction  == 1 ? 'rtl-flip' : '' }}">
                                        <img class="lazyload"
                                            src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                            data-src="{{ asset('assets/frontend/images/icon/') }}{{ $feature->number % 2 == 0 ? 'arrow-down.png' : 'arrow-up.png' }}"

                                            alt="Image">
                                    </div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="row">
                        <div class="col">
                            <h3 class="text-center mt-5">{{ __('No Processes to Display Yet!') }}</h3>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif
    <!-- Works-area end -->
    <x-additional-section :sections="$additionalSections" position="work_process_section_status" />

    <!-- City-area start -->
    @if ($isActiveSection->popular_city_section_status == 1)
        <section class="city-area city-area_v1 pb-60">
            <div class="container-fluid pe-0">
                <div class="row align-items-center">
                    <div class="col-xl-5" data-aos="fade-up">
                        <div class="fluid-left">
                            <div class="content-title mb-40">
                                @if (!empty($homeSectionInfo->popular_city_section_title))
                                    <h2 class="title 25">
                                        {{ @$homeSectionInfo->popular_city_section_title }}
                                    </h2>
                                @else
                                    <h2 class="title 25">{{ __('Stay tuned! We’ll have popular cities soon!') }}</h2>
                                @endif

                                <p class="text">
                                    {!! @$homeSectionInfo->popular_city_section_text !!}
                                </p>
                                @if (!empty($homeSectionInfo->popular_city_section_button_name))
                                    <a href="{{ route('space.index') }}" class="btn btn-md btn-primary radius-sm"
                                        title="{{ __('Explore More Cities') }}"
                                        target="_self">{{ @$homeSectionInfo->popular_city_section_button_name }}</a>
                                @endif
                                <div class="swiper-pagination mt-30" id="city-slider-1-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7" data-aos="fade-up">
                        <!-- Slider main container -->
                        @if (count($cities) > 0)
                            <div class=" swiper mb-40" id="city-slider-1">

                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <!-- Slides -->
                                    @foreach ($cities->take(4) as $city)
                                        @php
                                            $s_space_contents = [];

                                            $s_space_contents = \App\Models\SpaceContent::where(
                                                'language_id',
                                                $language->id,
                                            )
                                                ->where('space_contents.city_id', $city->city_id)
                                                ->leftJoin('spaces', 'space_contents.space_id', '=', 'spaces.id')
                                                ->leftJoin(
                                                    'memberships',
                                                    'spaces.seller_id',
                                                    '=',
                                                    'memberships.seller_id',
                                                )
                                                ->where(function ($query) {
                                                    $query
                                                        ->where([
                                                            ['memberships.status', '=', 1],
                                                            ['memberships.start_date', '<=', now()->format('Y-m-d')],
                                                            ['memberships.expire_date', '>=', now()->format('Y-m-d')],
                                                        ])
                                                        ->orWhere('spaces.seller_id', '=', 0);
                                                })
                                                ->get();

                                            $s_spaceIds = [];
                                            $s_spaceIdsForCity = [];

                                            foreach ($s_space_contents as $s_space_content) {
                                                if (!in_array($s_space_content->space_id, $s_spaceIds)) {
                                                    $s_spaceIdsForCity[] = $s_space_content->space_id;
                                                }
                                            }

                                        @endphp
                                        <div class="swiper-slide">
                                            <div class="card radius-md">
                                                <div class="card_img">
                                                    <a href="{{ route('space.index', ['search_from_home' => 'home', 'city' => $city->city_id]) }}"
                                                        title="{{ @$city->city_name }}" target="_self"
                                                        class="lazy-container ratio ratio-1-2">
                                                        <img class="lazyload"
                                                            src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                                            data-src="{{ asset('./assets/img/city/' . $city->image) }}"
                                                            alt="{{ @$city->city_name }}">
                                                    </a>
                                                </div>
                                                <div class="card_content p-20">
                                                    <div class="wrapper">
                                                        <div class="card_title mb-0">
                                                            <span class="subtitle color-white">

                                                                @if (count($s_spaceIdsForCity) > 50)
                                                                    50+
                                                                @else
                                                                    {{ count($s_spaceIdsForCity) }}
                                                                @endif
                                                            </span>
                                                            <h6 class="title color-white mb-0">
                                                                <a href="{{ route('space.index', ['city' => $city->city_id]) }}"
                                                                    target="_self"
                                                                    title="{{ @$city->city_name }}">{{ @$city->city_name . ' , ' . @$city->country_name }}</a>
                                                            </h6>
                                                        </div>
                                                        <a href="{{ route('space.index', ['search_from_home' => 'home', 'city' => $city->city_id]) }}"
                                                            class="btn-icon rounded-circle"
                                                            title="{{ @$city->city_name }}" target="_self">
                                                            @if ($language->direction == 1)
                                                                <i class="fal fa-long-arrow-left"></i>
                                                            @else
                                                                <i class="fal fa-long-arrow-right"></i>
                                                            @endif
                                                            
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @else
                            <h4 class="title">{{ __('Oops! No popular cities found at this moment.') }}</h4>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- City-area end -->
    <x-additional-section :sections="$additionalSections" position="popular_city_section_status" />

    <!-- Testimonial-area start -->
    @if ($isActiveSection->testimonials_section_status == 1)
        <!-- Testimonial-area start -->
        <section class="testimonial-area testimonial-area_v1 pb-100" data-aos="fade-up">
            <div class="container">
                <div class="wrapper radius-md p-30 bg-img bg-cover"
                       data-bg-img="{{ isset($testimonialBgImg) ? asset('assets/img/' . $testimonialBgImg) : '' }}">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title title-center mb-30">
                                @if (!empty($homeSectionInfo->testimonial_title))
                                    <h2 class="title">{{ @$homeSectionInfo->testimonial_title }}</h2>
                                @else
                                    <h2 class="title">{{ __('What Our Trusted Clients Say About Us') }}</h2>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">

                                    <div class="swiper"
                                        @if (count($testimonials) > 0) id="testimonial-slider-1" @endif>
                                        @if (count($testimonials) == 0)
                                            <div class="row">
                                                <div class="col">
                                                    <h3 class="text-center mt-5">{{ __('No Testimonial Found') . '!' }}
                                                    </h3>
                                                </div>
                                            </div>
                                        @else
                                            <div class="swiper-wrapper">
                                                @foreach ($testimonials as $testimonial)
                                                    <div class="swiper-slide">
                                                        <div class="slider-item text-center">
                                                            <div class="quote mb-20">
                                                                <p class="text">
                                                                    {{ @$testimonial->comment }}
                                                                </p>
                                                            </div>
                                                            <div class="client-info">
                                                                <div class="client-img mb-15 mx-auto">
                                                                    <div
                                                                        class="lazy-container ratio ratio-1-1 rounded-circle">
                                                                        <img class="lazyload"
                                                                            src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                                                            data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                                                            alt="Person Image">
                                                                    </div>
                                                                </div>
                                                                <h6 class="name"> {{ @$testimonial->name }}</h6>
                                                                <span class="designation">
                                                                    {{ @$testimonial->occupation }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        @endif
                                        <div class="swiper-pagination mt-20" id="testimonial-slider-1-pagination"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="avatar">
                        @foreach ($testimonials->take(4) as $testimonial)
                            <img class="lazyload avatar-1 rounded-pill"
                                src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}" alt="Person Image">
                        @endforeach
                    </div>

                </div>
            </div>
        </section>
    @endif

    <!-- Testimonial-area end -->
    <x-additional-section :sections="$additionalSections" position="testimonials_section_status" />
    <!-- Newsletter-area start -->
    <x-home.space-banner :isActiveSection="$isActiveSection" :homeSectionInfo="$homeSectionInfo" :spaceBannerBgImg="$spaceBannerBgImg" :spaceBannerForegroundImg="$spaceBannerForegroundImg" version="1" />
    <!-- Newsletter-area end -->
    <x-additional-section :sections="$additionalSections" position="space_banner_section_status" />


@endsection
@section('custom-script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ $basicInfo->google_map_api_key }}&libraries=places&callback=initMap"
        async defer></script>
        <script type="text/javascript">
        var google_map_api_key = '{{ $basicInfo->google_map_api_key }}';
        </script>
@endsection

