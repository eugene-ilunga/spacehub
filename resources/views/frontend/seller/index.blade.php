@extends('frontend.layout')

@php
    $title = $pageHeading->vendor_page_title ?? __('Vendors');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', [
        'breadcrumb' => $breadcrumb ?? '',
        'title' => $title,
    ])
    <!-- Breadcrumb end -->
    @php
        if ($basicInfo->admin_profile == 1) {
            $totalSellers = $totalSellers;
        } else {
            $totalSellers = $totalSellers - 1;
        }
    @endphp

    <!-- Vendor-area start -->
    <div class="vendor-area pt-100 pb-75">
        <div class="container">
            <div class="product-sort-area pb-20" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h4 class="mb-20">{{ $totalSellers > 100 ? $totalSellers . '+' : $totalSellers }}
                            {{ __($totalSellers > 1 ? __('Vendor Profiles') : __('Vendor Profile')) }}
                            {{ __('Available') }}</h4>
                    </div>
                    <div class="col-lg-6">
                        <form action="{{ route('frontend.sellers') }}" method="get">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group icon-start mb-20">
                                        <span class="icon color-primary">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control border-primary"
                                            placeholder="{{ __('Enter vendor name') }}"
                                            value="{{ request()->input('name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group icon-start mb-20">
                                        <span class="icon color-primary">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <input type="text" name="location" class="form-control border-primary"
                                            value="{{ request()->input('location') }}"
                                            placeholder="{{ __('Enter location') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <div class="seller-search mb-10">
                                        <div class="form-group">
                                            <button class="btn btn-lg btn-primary radius-sm">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Products -->

            <div class="row" data-aos="fade-up">
                @if ($admin && $basicInfo->admin_profile == 1)
                    <div class="col-xl-3 col-lg-4 col-sm-6">

                        <div class="card mb-25 shadow-md radius-md">
                            <div class="card-info-area bg-primary-light p-20">
                                <div class="card-img">
                                    <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                        target="_self" title="{{ ucfirst($admin->username) }}"
                                        class="lazy-container ratio ratio-1-1 rounded-circle">
                                        @if (!is_null($admin->image))
                                            <img class="lazyload" src="{{ asset('./assets/img/admins/' . $admin->image) }}"
                                                data-src="{{ asset('./assets/img/admins/' . $admin->image) }}"
                                                alt="Vendor">
                                        @else
                                            <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                                data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="Vendor">
                                        @endif
                                    </a>
                                </div>
                                <div class="card-info">
                                    <h6 class="title mb-1">
                                        <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                            target="_self"
                                            title="Vendor">{{ strlen($admin->username) > 20 ? mb_substr($admin->username, 0, 20, 'UTF-8') . '..' : ucfirst($admin->username) }}</a>
                                    </h6>
                                    @php
                                        $space_count = \App\Models\Space::where([
                                            ['seller_id', 0],
                                            ['space_status', 1],
                                        ])->count();
                                    @endphp
                                    <div>
                                        @if ($space_count > 1)
                                            <span>{{ __('Total Spaces') . ':' }}&nbsp;</span>
                                        @else
                                            <span>{{ __('Total Space') . ':' }}&nbsp;</span>
                                        @endif
                                        <span class="color-dark">{{ @$space_count }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-details p-20">
                                <ul class="card-list list-unstyled">
                                    @if (!empty($admin->address))
                                        <li class="icon-start font-sm">
                                            <i class="fal fa-map-marker-alt"></i>
                                            <span>{{ $admin->address }} </span>
                                        </li>
                                    @endif
                                    @if (!empty($admin->phone))
                                        <li class="icon-start font-sm">
                                            <i class="fal fa-phone-plus"></i>
                                            <span><a
                                                    href="tel:+{{ @$admin->phone }}">{{ '+' . @$admin->phone }}</a></span>
                                        </li>
                                    @endif
                                    @if (!empty($admin->email))
                                        <li class="icon-start font-sm">
                                            <i class="fal fa-envelope"></i>
                                            <span><a href="mailto:{{ @$admin->email }}">{{ @$admin->email }}</a></span>
                                        </li>
                                    @endif
                                </ul>
                                <div class="btn-groups d-flex gap-3 flex-wrap mt-20">
                                    <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                        class="btn btn-md btn-primary radius-sm w-100" title="{{ __('View Profile') }}"
                                        target="_self">{{ __('View Profile') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($totalSellers > 0)
                    @foreach ($sellers as $seller)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="card mb-25 shadow-md radius-md">
                                <div class="card-info-area bg-primary-light p-20">
                                    <div class="card-img">
                                        <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                            class="lazy-container ratio ratio-1-1 rounded-circle">
                                            @if (!is_null($seller->photo))
                                                <img class="lazyload"
                                                    src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                                    data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                                    alt="Vendor">
                                            @else
                                                <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                                    data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                                    alt="Vendor">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="title mb-1">
                                            <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                                target="_self"
                                                title="Vendor">{{ strlen($seller->username) > 20 ? mb_substr($seller->username, 0, 20, 'UTF-8') . '..' : ucfirst($seller->username) }}</a>
                                        </h6>
                                        @php
                                            $spaceIds = \App\Models\Package::getSpaceIdsBySeller($seller->seller_id);

                                            $space_count = \App\Models\Space::where([
                                                ['seller_id', $seller->sellerId],
                                                ['space_status', 1],
                                            ])
                                                ->whereIn('id', $spaceIds)
                                                ->count();
                                        @endphp
                                        <div>
                                            @if ($space_count > 1)
                                                <span>{{ __('Total Spaces') . ':' }}&nbsp;</span>
                                            @else
                                                <span>{{ __('Total Space') . ':' }}&nbsp;</span>
                                            @endif
                                            <span class="color-dark">{{ @$space_count }}</span>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $sellerInfo = \App\Models\SellerInfo::select('country', 'city', 'state', 'address')
                                        ->where('seller_id', $seller->seller_id)
                                        ->first();
                                        $isBothExist = $sellerInfo->address || $sellerInfo->city || $sellerInfo->state || $sellerInfo->country;
                                @endphp
                              
                                <div class="card-details p-20">
                                    <ul class="card-list list-unstyled">
                                        @if (!empty($sellerInfo))
                                            <li class="icon-start font-sm">
                                                @if($isBothExist)
                                                <i class="fal fa-map-marker-alt"></i>
                                                @endif
                    
                                                <span>
                                                    @if ($sellerInfo->address && $sellerInfo->address != '')
                                                        {{ $sellerInfo->address }},
                                                    @endif
                                                    @if ($sellerInfo->city && $sellerInfo->city != '')
                                                        {{ $sellerInfo->city }},
                                                    @endif
                                                    @if ($sellerInfo->state && $sellerInfo->state != '')
                                                        {{ $sellerInfo->state }},
                                                    @endif
                                                    @if ($sellerInfo->country && $sellerInfo->country != '')
                                                        {{ $sellerInfo->country }}
                                                    @endif
                                                </span>
                                            </li>
                                        @endif
                                        @if (!empty($seller->phone) && $seller->show_phone_number == 1)
                                            <li class="icon-start font-sm">
                                                <i class="fal fa-phone-plus"></i>
                                                <span><a href="tel:+880123456789">{{ '+' . @$seller->phone }}</a></span>
                                            </li>
                                        @endif
                                        @if (!empty($seller->email) && $seller->show_email_addresss == 1)
                                            <li class="icon-start font-sm">
                                                <i class="fal fa-envelope"></i>
                                                <span><a
                                                        href="mailto:{{ @$seller->email }}">{{ @$seller->email }}</a></span>
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="btn-groups d-flex gap-3 flex-wrap mt-20">
                                        <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                            class="btn btn-md btn-primary radius-sm w-100"
                                            title="{{ __('View Profile') }}" target="_self">{{ __('View Profile') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <h4 class="text-center">{{ __('No Seller Found.') }}</h4>
                    </div>
                @endif
            </div>
            <nav class="pagination-nav mt-20 mb-25" data-aos="fade-up">
                <ul class="pagination justify-content-center">
                    {{ $sellers->appends([
                            'name' => request()->input('name'),
                            'location' => request()->input('location'),
                        ])->links() }}
                </ul>
            </nav>
            @if (!empty(showAd(3)))
                <div class="text-center mt-2 mb-40">
                    {!! showAd(3) !!}
                </div>
            @endif
        </div>
    </div>
    <!-- Vendor-area end -->

@endsection
