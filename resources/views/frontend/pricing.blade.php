@extends('frontend.layout')

@php
    $title = $pageHeading->pricing_page_title ?? __('Pricing');
@endphp
{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;

@endphp

@section('content')

    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title ?? ''])
    <!-- Breadcrumb end -->

    <!-- Pricing-area Start -->
    <section class="pricing-area pricing-area_v1 pt-100 pb-70">
        <div class="container">

            <div class="row">
                @if ($monthly_packages->count() > 0 || $yearly_packages->count() > 0 || $lifetime_packages->count() > 0)
                    <div class="col-12">
                        <div class="section-title title-center mb-50" data-aos="fade-up">
                            <div class="tabs-navigation tabs-navigation_v3">
                                <ul class="nav nav-tabs radius-md" data-hover="fancyHover">
                                    <li class="nav-item active">
                                        <button class="nav-link hover-effect active btn-md radius-sm" data-bs-toggle="tab"
                                            data-bs-target="#monthly" type="button">{{ __('Monthly') }}</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link hover-effect btn-md radius-sm" data-bs-toggle="tab"
                                            data-bs-target="#yearly" type="button">{{ __('Yearly') }}</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link hover-effect btn-md radius-sm" data-bs-toggle="tab"
                                            data-bs-target="#lifeTime" type="button">{{ __('Lifetime') }}</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content" data-aos="fade-up">
                            <div class="tab-pane slide active show" id="monthly">
                                <div class="row justify-content-center">
                                    @foreach ($monthly_packages as $package)
                                        <div class="col-md-6 col-lg-4 item">
                                            <div
                                                class="card p-30 mb-30 radius-lg border
                                                {{ $package->recommended == 1 ? 'active' : '' }}">
                                                <div class="card_top">
                                                    <div class="card_icon">
                                                        <i class="{{ $package->icon }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="card_title mb-2">{{ $package->price == 0 ? __('Free') : __($package->title) }}</h3>
                                                        @if ($package->recommended == 1)
                                                            <h4
                                                                class="card_title mb-0 badge rounded-pill text-bg-light p-10">
                                                                {{ __('Popular') }}</h4>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="card_text mt-15">
                                                    {{ __("What's Included") }}
                                                </p>
                                                <div class="card_subtitle mt-15">
                                                    <h4 class="mb-0">
                                                        {{ $package->price == 0 ? __('Free') : format_price($package->price) }}
                                                        <span class="period">/ {{ __('Monthly') }}
                                                    </h4>
                                                </div>

                                                <ul class="card_list toggle-list list-unstyled mt-25"
                                                    data-toggle-list="pricingToggle" data-toggle-show="5">
                                                    @foreach ($allPfeatures as $feature)
                                                        @if ($feature == 'spaces')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_space == 1 ? __('Space Included') . ':' : __('Spaces Included') . ':' }}</span>
                                                                <span>{{ $package->number_of_space == 999999 ? __('Unlimited') : $package->number_of_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'slider_images_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_slider_image_per_space == 1
                                                                            ? __('Slider Image Per Space') . ':'
                                                                            : __('Slider Images Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_slider_image_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_slider_image_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'services_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_service_per_space == 1 ? __('Service Per Space') . ':' : __('Services Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_service_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_service_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'variants_per_service')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_option_per_service == 1
                                                                            ? __('Variant Per Service') . ':'
                                                                            : __('Variants Per Service') . ':' }}</span>
                                                                <span>{{ $package->number_of_option_per_service == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_option_per_service }}</span>
                                                            </li>
                                                        @elseif ($feature == 'amenities_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_amenities_per_space == 1
                                                                            ? __('Amenity Per Space') . ':'
                                                                            : __('Amenities Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_amenities_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_amenities_per_space }}</span>
                                                            </li>
                                                            @php
                                                                $features = json_decode(
                                                                    $package->package_feature,
                                                                    true,
                                                                );
                                                            @endphp
                                                        @elseif ($feature == 'support_tickets')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Support Tickets', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Support Tickets') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'add_booking')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Add Booking', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Manual Booking Entry from Dashboard') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'fixed_timeslot_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Fixed Timeslot Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Fixed Timeslot Rental') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'hourly_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Hourly Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Hourly Rental') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'multi_day_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Multi Day Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Multi-day Rental') }}
                                                                </span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    
                                                    @if ($package->custom_features != '')
                                                        @php
                                                            $features = explode("\n", $package->custom_features);
                                                        @endphp
                                                        @if (count($features) > 0)
                                                            @foreach ($features as $key => $value)
                                                                <li>
                                                                    <span><i
                                                                            class="fal fa-check"></i>{{ __($value) }}</span>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </ul>

                                                @php
                                                    $showMoreText = __('Show More') . ' +';
                                                    $showLessText = __('Show Less') . ' -';
                                                @endphp
                                                <span class="show-more mt-15" data-toggle-btn="toggleListBtn"
                                                    data-show-more="{{ $showMoreText }}"
                                                    data-show-less="{{ $showLessText }}">
                                                    {{ __('Show More') . '+' }}
                                                </span>
                                                <div class="card_action mt-25">
                                                    @guest('seller')
                                                        <a href="{{ route('vendor.login', ['redirect' => 'buy_plan']) }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title="{{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endguest
                                                    @auth('seller')
                                                        <a href="{{ route('vendor.plan.extend.index') }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title=" {{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane slide" id="yearly">
                                <div class="row justify-content-center">
                                    @foreach ($yearly_packages as $package)
                                        <div class="col-md-6 col-lg-4 item">
                                            <div
                                                class="card p-30 mb-30 radius-lg border {{ $package->recommended == 1 ? 'active' : '' }}">
                                                <div class="card_top">
                                                    <div class="card_icon">
                                                        <i class="{{ $package->icon }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="card_title mb-2">{{ $package->price == 0 ? __('Free') : __($package->title) }}</h3>
                                                        @if ($package->recommended == 1)
                                                            <h4
                                                                class="card_title mb-0 badge rounded-pill text-bg-light p-10">
                                                                {{ __('Popular') }}</h4>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="card_text mt-15">
                                                    {{ __("What's Included") }}
                                                </p>
                                                <div class="card_subtitle mt-15">
                                                    <h4 class="mb-0">
                                                        {{ $package->price == 0 ? __('Free') : format_price($package->price) }}
                                                        <span class="period">/ {{ __('Yearly') }}</span>
                                                    </h4>
                                                </div>

                                                <ul class="card_list toggle-list list-unstyled mt-25"
                                                    data-toggle-list="pricingToggle" data-toggle-show="5">
                                                    @foreach ($allPfeatures as $feature)
                                                        @if ($feature == 'spaces')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_space == 1 ? __('Space Included') . ':' : __('Spaces Included') . ':' }}</span>
                                                                <span>{{ $package->number_of_space == 999999 ? __('Unlimited') : $package->number_of_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'slider_images_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_slider_image_per_space == 1
                                                                            ? __('Slider Image Per Space') . ':'
                                                                            : __('Slider Images Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_slider_image_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_slider_image_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'services_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_service_per_space == 1 ? __('Service Per Space') . ':' : __('Services Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_service_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_service_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'variants_per_service')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_option_per_service == 1
                                                                            ? __('Variant Per Service') . ':'
                                                                            : __('Variants Per Service') . ':' }}</span>
                                                                <span>{{ $package->number_of_option_per_service == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_option_per_service }}</span>
                                                            </li>
                                                        @elseif ($feature == 'amenities_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_amenities_per_space == 1
                                                                            ? __('Amenity Per Space') . ':'
                                                                            : __('Amenities Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_amenities_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_amenities_per_space }}</span>
                                                            </li>
                                                            @php
                                                                $features = json_decode(
                                                                    $package->package_feature,
                                                                    true,
                                                                );
                                                            @endphp
                                                        @elseif ($feature == 'support_tickets')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Support Tickets', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Support Tickets') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'add_booking')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Add Booking', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Manual Booking Entry from Dashboard') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'fixed_timeslot_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Fixed Timeslot Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Fixed Timeslot Rental') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'hourly_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Hourly Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Hourly Rental') }}
                                                                </span>
                                                            </li>
                                                            @elseif ($feature == 'multi_day_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Multi Day Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Multi-day Rental') }}
                                                                </span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    @if ($package->custom_features != '')
                                                        @php
                                                            $features = explode("\n", $package->custom_features);
                                                        @endphp
                                                        @if (count($features) > 0)
                                                            @foreach ($features as $key => $value)
                                                                <li>
                                                                    <span><i
                                                                            class="fal fa-check"></i>{{ __($value) }}</span>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </ul>

                                                <span class="show-more mt-15" 
                                                data-toggle-btn="toggleListBtn" 
                                                data-show-more="{{ $showMoreText }}" 
                                                data-show-less="{{ $showLessText }}">
                                                    {{ __('Show More') . '+' }}
                                                </span>
                                                <div class="card_action mt-25">
                                                    @guest('seller')
                                                        <a href="{{ route('vendor.login', ['redirect' => 'buy_plan']) }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title="{{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endguest
                                                    @auth('seller')
                                                        <a href="{{ route('vendor.plan.extend.index') }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title="{{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane slide" id="lifeTime">
                                <div class="row justify-content-center">
                                    @foreach ($lifetime_packages as $package)
                                        <div class="col-md-6 col-lg-4 item">
                                            <div
                                                class="card p-30 mb-30 radius-lg border {{ $package->recommended == 1 ? 'active' : '' }}">
                                                <div class="card_top">
                                                    <div class="card_icon">
                                                        <i class="{{ $package->icon }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="card_title mb-2">{{$package->price == 0 ? __('Free') : __($package->title) }}</h3>
                                                        @if ($package->recommended == 1)
                                                            <h4
                                                                class="card_title mb-0 badge rounded-pill text-bg-light p-10">
                                                                {{ __('Popular') }}</h4>
                                                        @endif
                                                    </div>
                                                </div>

                                                <p class="card_text mt-15">
                                                    {{ __("What's Included") }}
                                                </p>
                                                <div class="card_subtitle mt-15">
                                                    <h4 class="mb-0">
                                                        {{ $package->price == 0 ? __('Free') : format_price($package->price) }}
                                                        <span class="period">/ {{ __('Lifetime') }}</span>
                                                    </h4>
                                                </div>

                                                <ul class="card_list toggle-list list-unstyled mt-25"
                                                    data-toggle-list="pricingToggle" data-toggle-show="5">
                                                    @foreach ($allPfeatures as $feature)
                                                        @if ($feature == 'spaces')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_space == 1 ? __('Space Included') . ':' : __('Spaces Included') . ':' }}</span>
                                                                <span>{{ $package->number_of_space == 999999 ? __('Unlimited') : $package->number_of_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'slider_images_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_slider_image_per_space == 1
                                                                            ? __('Slider Image Per Space') . ':'
                                                                            : __('Slider Images Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_slider_image_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_slider_image_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'services_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_service_per_space == 1 ? __('Service Per Space') . ':' : __('Services Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_service_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_service_per_space }}</span>
                                                            </li>
                                                        @elseif ($feature == 'variants_per_service')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_option_per_service == 1
                                                                            ? __('Variant Per Service') . ':'
                                                                            : __('Variants Per Service') . ':' }}</span>
                                                                <span>{{ $package->number_of_option_per_service == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_option_per_service }}</span>
                                                            </li>
                                                        @elseif ($feature == 'amenities_per_space')
                                                            <li>
                                                                <span><i
                                                                        class="fal fa-check"></i>{{ $package->number_of_amenities_per_space == 1
                                                                            ? __('Amenity Per Space') . ':'
                                                                            : __('Amenities Per Space') . ':' }}</span>
                                                                <span>{{ $package->number_of_amenities_per_space == 999999
                                                                    ? __('Unlimited')
                                                                    : __('Up to') . ' ' . $package->number_of_amenities_per_space }}</span>
                                                            </li>
                                                            @php
                                                                $features = json_decode(
                                                                    $package->package_feature,
                                                                    true,
                                                                );
                                                            @endphp
                                                        @elseif ($feature == 'support_tickets')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Support Tickets', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Support Tickets') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'add_booking')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Add Booking', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Manual Booking Entry from Dashboard') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'fixed_timeslot_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Fixed Timeslot Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Fixed Timeslot Rental') }}
                                                                </span>
                                                            </li>
                                                        @elseif ($feature == 'hourly_rental')
                                                            <li>
                                                                <span>
                                                                    <i
                                                                        class="fal @if (is_array($features) && in_array('Hourly Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Hourly Rental') }}
                                                                </span>
                                                            </li>
                                                        
                                                        @elseif ($feature == 'multi_day_rental')
                                                        <li>
                                                            <span>
                                                                <i
                                                                class="fal @if (is_array($features) && in_array('Multi Day Rental', $features)) fa-check @else fa-times @endif"></i>
                                                                    {{ __('Multi-day Rental') }}
                                                            </span>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                    @if ($package->custom_features != '')
                                                        @php
                                                            $features = explode("\n", $package->custom_features);
                                                        @endphp
                                                        @if (count($features) > 0)
                                                            @foreach ($features as $key => $value)
                                                                <li>
                                                                    <span><i
                                                                            class="fal fa-check"></i>{{ __($value) }}</span>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </ul>

                                                <span class="show-more mt-15" 
                                                data-toggle-btn="toggleListBtn" 
                                                data-show-more="{{ $showMoreText }}" 
                                                data-show-less="{{ $showLessText }}">
                                                    {{ __('Show More') . '+' }}
                                                </span>
                                                <div class="card_action mt-25">
                                                    @guest('seller')
                                                        <a href="{{ route('vendor.login', ['redirect' => 'buy_plan']) }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title="{{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endguest
                                                    @auth('seller')
                                                        <a href="{{ route('vendor.plan.extend.index') }}"
                                                            class="btn btn-lg btn-primary radius-sm w-100"
                                                            title="{{ __('Purchase') }}"
                                                            target="_self">{{ __('Purchase') }}</a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            {{ __('No package found') . '!' }}
                        </div>
                @endif
            </div>
        </div>

    </section>
    <!-- Pricing-area End -->

@endsection
