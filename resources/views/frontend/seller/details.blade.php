@extends('frontend.layout')

@php
    $title = $seller->username ?? '';
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />
@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;

@endphp

@section('content')
    <!-- Breadcrumb start -->
    <div class="breadcrumb-area bg-img bg-cover z-1 header-next" data-bg-img="{{ asset('assets/img/' . @$breadcrumb) }}">
        <div class="overlay opacity-75"></div>
        <div class="container">
            <div class="content">
                @if (request()->input('admin') != true)
                    <div class="vendor mb-15">
                        <figure class="vendor-img">
                            <a href="javaScript:void(0)" class="lazy-container radius-md ratio ratio-1-1">
                                @if (!is_null($seller->photo))
                                    <img class="lazyload"
                                        src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                        data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                        alt="{{ ucfirst($seller->username) }}">
                                @else
                                    <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                        data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="{{ ucfirst($seller->username) }}">
                                @endif
                            </a>
                        </figure>
                        <div class="vendor-info">
                            <h4 class="mb-2 color-white">{{ ucfirst($seller->username) }}</h4>
                            <span class="text-light">{{ __('Member Since') . ' ' }}
                                {{ \Carbon\Carbon::parse($seller->created_at)->format('F Y') }} </span>
                            <div class="ratings mt-2">
                                <div class="rate bg-img" data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                    <div class="rating-icon bg-img" style="width: {{ SellerAvgRating($seller->id) * 20 }}%;"
                                        data-bg-img={{ asset('assets/frontend/images/rate-star.png') }}></div>
                                </div>
                                <span class="ratings-total color-white">{{ SellerAvgRating($seller->id) }}
                                    ({{ SellerRatingCount($seller->id) }}@if (SellerRatingCount($seller->id) == 1 || SellerRatingCount($seller->id) == 0)
                                        {{ __('Review') }})
                                    @else
                                        {{ __('Reviews') }})
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $admin = \App\Models\Admin::first();
                    @endphp
               
               
                    <div class="vendor mb-15">
                        <figure class="vendor-img">
                            <a href="javaScript:void(0)" class="lazy-container radius-md ratio ratio-1-1">
                                @if (!is_null($admin->image))
                               
                                    <img class="lazyload" src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                        data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="{{ ucfirst($admin->username) }}">
                                @else
                               
                                    <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                        data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="{{ ucfirst($admin->username) }}">
                                @endif
                            </a>
                        </figure>
                        <div class="vendor-info">
                            <h4 class="mb-2 color-white">{{ ucfirst($admin->username) }}</h4>
                            <div class="ratings mt-2">
                                <div class="rate bg-img" data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                    <div class="rating-icon bg-img" style="width: {{ SellerAvgRating(0) * 20 }}%;"
                                        data-bg-img={{ asset('assets/frontend/images/rate-star.png') }}>

                                    </div>
                                </div>
                                <span class="ratings-total color-white"> {{ SellerAvgRating(0) }}
                                    ({{ SellerRatingCount(0) }} @if (SellerRatingCount(0) == 1 || SellerRatingCount(0) == 0)
                                        {{ __('Review') }})
                                    @else
                                        {{ __('Reviews') }})
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-start">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($title) ?? __('Vendor Details') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <!-- Vendor-area start -->
    <div class="vendor-area pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">
                <div class="col-lg-8">
                    <h4 class="title mb-20">{{ __('All Spaces') }}</h4>
                    <div class="tabs-navigation tabs-navigation_v2 mb-20">
                        <ul class="nav nav-tabs" data-hover="fancyHover">
                            <li class="nav-item active">
                                <button class="nav-link hover-effect btn-lg active" data-bs-toggle="tab"
                                    data-bs-target="#all" type="button">{{ __('All') }}
                                </button>
                            </li>
                            @foreach ($categories as $category)
                                <li class="nav-item">
                                    <button class="nav-link hover-effect btn-lg" data-bs-toggle="tab"
                                        data-bs-target="#tab{{ $category->id }}" type="button">
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-content" data-aos="fade-up">
                        <div class="tab-pane fade show active" id="all">
                            @if ($spaces->isNotEmpty())
                                <div class="row" id="wishlist-div">
                                    @foreach ($spaces as $space)
                                        <x-space.vendor_details :space="$space" :position="$position" :symbol="$symbol" />
                                    @endforeach
                                </div>
                            @else
                                <div class="row">
                                    <div class="col">
                                        <h3 class="text-center mt-5">{{ __('No Space Found!') }}</h3>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @foreach ($categories as $category)
                            <div class="tab-pane fade" id="tab{{ $category->id }}">
                                @php
                                    if (request()->input('admin') == true) {
                                        $seller_id = 0;
                                    } else {
                                        $seller_id = $seller->id;
                                    }

                                    $spaces = \App\Models\Space::query()
                                        ->select(
                                            'spaces.id as space_id',
                                            'spaces.space_rent',
                                            'spaces.rent_per_hour',
                                            'spaces.price_per_day',
                                            'spaces.latitude',
                                            'spaces.longitude',
                                            'spaces.use_slot_rent',
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
                                            ['spaces.seller_id', $seller_id],
                                            ['space_contents.space_category_id', $category->id],
                                        ])
                                        ->whereIn('spaces.id', $spaceIds)
                                        ->orderBy('spaces.id', 'desc')
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

                                            $listedSpace = $space->wishlist()->where('user_id', $authUser->id)->first();

                                            if (empty($listedSpace)) {
                                                $space['wishlisted'] = false;
                                            } else {
                                                $space['wishlisted'] = true;
                                            }
                                        });
                                    }
                                @endphp
                                @if (count($spaces) > 0)
                                    <div class="row">
                                        @foreach ($spaces as $space)
                                            <x-space.vendor_details :space="$space" :position="$position" :symbol="$symbol" />
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @includeIf('frontend.seller.seller-bio')
            </div>
        </div>
    </div>
    <!-- Vendor-area end -->

    <!-- Contact Modal -->
    @if($seller->show_contact_form == 1)
    @includeIf('frontend.seller.contact-form')
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        var spaceUrl = "{{ route('space.index') }}";
    </script>
@endsection
