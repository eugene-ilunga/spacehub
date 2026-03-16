@extends('frontend.layout')

<x-meta-tags :meta-tag-content="$seoInfo" />

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/shop.css') }}">
@endsection

@section('content')

    @includeIf('frontend.partials.breadcrumb', [
        'breadcrumb' => $breadcrumb ?? '',
        'title' => !empty($pageHeading) ? $pageHeading->shop_page_title : __('Products'),
    ])


    <!-- Shop-area start -->
    <div class="shop-area pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">

                @includeIf('frontend.shop.side-bar')

                <div class="col-xl-9">
                    <div class="sort-area product-sort-area" data-aos="fade-up">
                        <div class="row align-items-center">
                            <div class="col-xl-6">
                                <h4 class="mb-20">{{ $total_products_in_page }}
                                    {{ $total_products_in_page > 1 ? __('Products') : __('Product') }}
                                    {{ __('Found') }}</h4>
                            </div>
                            <div class="col-4 d-xl-none">
                                <button class="btn btn-sm btn-outline icon-end mb-20" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas"
                                    aria-controls="widgetOffcanvas">
                                    {{ __('Filter') }} <i class="fal fa-filter"></i>
                                </button>
                            </div>
                            <div class="col-8 col-xl-6">
                                <ul class="product-sort-list list-unstyled mb-20">
                                    <li class="item">
                                        <div class="sort-item d-flex align-items-center justify-content-end">
                                            <label class="me-2 font-sm">{{ __('Sort By') }}:</label>
                                            <form action="{{ route('shop.products') }}" method="get" id="SortForm">
                                                @if (!empty(request()->input('category')))
                                                    <input type="hidden" name="category"
                                                        value="{{ request()->input('category') }}">
                                                @endif

                                                @if (!empty(request()->input('min')))
                                                    <input type="hidden" id="min-id" name="min"
                                                        value="{{ request()->input('min') }}">
                                                @endif
                                                @if (!empty(request()->input('max')))
                                                    <input type="hidden" id="max-id" name="max"
                                                        value="{{ request()->input('max') }}">
                                                @endif
                                                <select name="sort" class="niceselect right color-dark"
                                                    onchange="document.getElementById('SortForm').submit()">
                                                    <option {{ request()->input('newest') == 'default' ? 'selected' : '' }}
                                                        value="newest">
                                                        {{ __('Newest first') }}
                                                    </option>
                                                    <option {{ request()->input('sort') == 'oldest' ? 'selected' : '' }}
                                                        value="oldest">
                                                        {{ __('Oldest first') }}
                                                    </option>
                                                    <option
                                                        {{ request()->input('sort') == 'high-to-low' ? 'selected' : '' }}
                                                        value="high-to-low">
                                                        {{ __('Price') . ' : ' . __('High to low') }}</option>
                                                    <option
                                                        {{ request()->input('sort') == 'low-to-high' ? 'selected' : '' }}
                                                        value="low-to-high">
                                                        {{  __('Price') . ' : ' . __('Low to high') }}</option>
                                                </select>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if (count($products) > 0)
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-xl-4 col-sm-6" data-aos="fade-up">
                                    <div class="product-default shadow-none text-center mb-25">
                                        <figure class="product-img mb-15">
                                            <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                                                class="lazy-container ratio ratio-1-1">
                                                <img class="lazyload"
                                                    src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                                    data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                                                    alt="{{ $product->title }}">
                                            </a>
                                            <div class="product-overlay">
                                                <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                                                    target="_self" title="{{ __('View Details') }}" class="icon">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('shop.product.add_to_cart', ['id' => $product->id, 'quantity' => 1]) }}"
                                                    target="_self" title="{{ __('Add to Cart') }}"
                                                    class="icon cart-btn add-to-cart-btn">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                            </div>
                                        </figure>
                                        <div class="product-details">
                                            <div class="ratings justify-content-center mb-10">
                                                <div class="rate bg-img"
                                                    data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                                    <div class="rating-icon bg-img"
                                                        style="width: {{ $product->average_rating * 20 }}%; background-image: url({{ asset('assets/frontend/images/rate-star.png') }});"
                                                        data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="product-title mb-2 lc-2">
                                                <a
                                                    href="{{ route('shop.product_details', ['slug' => $product->slug]) }}">{{ strlen($product->title) > 50 ? mb_substr($product->title, 0, 50, 'UTF-8') . '...' : $product->title }}</a>
                                            </h5>
                                            <div class="product-price justify-content-center">
                                                <h6 class="new-price">{{ symbolPrice($product->current_price) }}</h6>
                                                @if (!empty($product->previous_price))
                                                    <span
                                                        class="old-price font-sm">{{ symbolPrice($product->previous_price) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div><!-- product-default -->
                                </div>
                            @endforeach

                        </div>
                    @else
                        <h4 class="product-title mb-2">
                            {{ __('No Product Found') . '!' }}
                        </h4>
                    @endif
                    <div class="pagination pagination-2 mt-20 mb-40 justify-content-center" data-aos="fade-up">
                        {{ $products->appends([
                                'keyword' => request()->input('keyword'),
                                'category' => request()->input('category'),
                                'rating' => request()->input('rating'),
                                'min' => request()->input('min'),
                                'max' => request()->input('max'),
                                'sort' => request()->input('sort'),
                            ])->links() }}
                    </div>

                    @if (!empty(showAd(3)))
                        <div class="text-center mb-40">
                            {!! showAd(3) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Shop-area end -->
@endsection

@section('custom-js-for-shop')
    <script src="{{ asset('assets/frontend/js/shop.js') }}"></script>
@endsection
