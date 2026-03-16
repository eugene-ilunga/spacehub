@extends('frontend.layout')


@php
    $imagePath = $details->featured_image ? 'assets/img/products/featured-images/' . $details->featured_image : '';
    $fullImagePath = asset($imagePath);
    $currentUrl = url()->current();
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$details" :og-url="$currentUrl" :og-image="$fullImagePath" />

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/shop.css') }}">
@endsection

@section('content')

    {{-- breadcrub start --}}
     <div class="breadcrumb-area bg-img bg-cover z-1 header-next"
    @if (!empty($breadcrumb)) data-bg-img="{{ asset('./assets/img/' . $breadcrumb) }}" @endif>
    <div class="overlay opacity-75"></div>
    <div class="container">
        <div class="content text-center">
            <h2 class="color-white">{{ !empty($details->title) ? $details->title : __('Product Details') }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}"></a>{{ __('Home') }}</li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('Product Details')}}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

    {{-- breadcrub end --}}

    <!-- Shop-single-area start -->
    <div class="shop-single-area pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5 align-items-center">
                <div class="col-lg-6">
                    <div class="shop-single-gallery mb-40" data-aos="fade-up">
                        <div class="swiper shop-single-slider">
                            <div class="swiper-wrapper">
                                @php $sliderImages = json_decode($details->slider_images); @endphp
                                @foreach ($sliderImages as $sliderImage)
                                    <div class="swiper-slide">
                                        <figure class="lazy-container ratio ratio-1-1">
                                            <a href="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}"
                                                class="lightbox-single">
                                                <img class="lazyload" src="{{ asset('assets/images/placeholder.png') }}"
                                                    data-src="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}"
                                                    alt="product image" />
                                            </a>
                                        </figure>
                                    </div>
                                @endforeach

                            </div>
                            <!-- Slider navigation buttons -->
                            <div class="slider-navigation">
                                <button type="button" title="Slide prev" class="slider-btn slider-btn-prev radius-0">
                                    <i class="fal fa-angle-left"></i>
                                </button>
                                <button type="button" title="Slide next" class="slider-btn slider-btn-next radius-0">
                                    <i class="fal fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="shop-thumb">
                            <div class="swiper shop-thumbnails">
                                <div class="swiper-wrapper">
                                    @foreach ($sliderImages as $sliderImage)
                                        <div class="swiper-slide">
                                            <div class="thumbnail-img lazy-container ratio ratio-1-1">
                                                <img class="lazyload" src="assets/images/placeholder.png"
                                                    data-src="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}"
                                                    alt="product image" />
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-single-details mb-40" data-aos="fade-up">
                        <h3 class="product-title mb-30">{{ $details->title }}</h3>
                        <div class="ratings mb-10">
                            @if (!empty($details->average_rating))
                                <div class="rate bg-img" data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                    <div class="rating-icon bg-img"
                                        style="width: {{ $details->average_rating * 20 }}%; background-image: url({{ asset('assets/frontend/images/rate-star.png') }});"
                                        data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}"></div>
                                </div>
                            @endif
                            <span class="ratings-total">({{ number_format($details->average_rating, 1) }})</span>
                        </div>

                        <div class="product-price mb-30 d-inline-flex align-items-center gap-2">
                            <h4 class="new-price color-primary mb-0">{{ symbolPrice($details->current_price) }}</h4>
                            @if (!empty($details->previous_price))
                                <span class="old-price h5 mb-0 color-medium text-decoration-line-through">
                                    {{ symbolPrice($details->previous_price) }}
                                </span>
                            @endif
                        </div>
                        <div class="product-desc">
                            {!! $details->summary !!}
                        </div>
                        <div class="btn-groups mt-30">
                            <div class="quantity-input">
                                <div class="quantity-down">
                                    <i class="fal fa-minus"></i>
                                </div>
                                <input type="text" value="1" name="quantity" id="product-quantity"
                                    spellcheck="false" data-ms-editor="true">
                                <div class="quantity-up">
                                    <i class="fal fa-plus"></i>
                                </div>
                            </div>
                            <a href="{{ route('shop.product.add_to_cart', ['id' => $details->id, 'quantity' => 'qty']) }}"
                                class="btn btn-md btn-primary add-to-cart-btn" title="{{ __('Add To Cart') }}"
                                target="_self">{{ __('Add To Cart') }}</a>
                        </div>
                        <div class="social-link style-2 mt-30">
                            <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank" title="{{ __('Facebook') }}"><i class="fab fa-facebook-f"></i></a>

                            <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                                target="_blank" title="{{ __('Twitter') }}"><i class="fab fa-twitter"></i></a>

                            <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"
                                target="_blank" title="{{ __('Linkedin') }}"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                        <div class="product-category mt-30">
                            {{ __('Category') . ':' }}
                            <a
                                href="{{ route('shop.products', ['category' => $details->categorySlug]) }}">{{ $details->categoryName }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="description mb-40" data-aos="fade-up">
                <div class="tabs-navigation tabs-navigation-2 mb-30">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link active btn-md" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button">{{ __('Description') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn-md" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button">{{ __('Reviews') }}</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab1">
                        <!-- Product description -->
                        <div class="product-desc">
                            {!! replaceBaseUrl($details->content, 'summernote') !!}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        @if (count($reviews) == 0)
                            <h5>{{ __('This product has no review yet') . '!' }}</h5>
                        @else
                            <h5 class="title mb-15">
                                {{ __(' All Reviews') }}
                            </h5>
                            <div class="reviews">
                                @foreach ($reviews as $review)
                                    <div class="author">
                                        <div class="image">
                                            @if (empty($review->user->image))
                                                <img class="lazyload blur-up" src="assets/images/placeholder.png"
                                                    data-src="{{ asset('assets/img/user.png') }}" alt="Person Image">
                                            @else
                                                <img class="lazyload blur-up" src="assets/images/placeholder.png"
                                                    data-src="{{ asset('assets/img/users/' . $review->user->image) }}"
                                                    alt="Person Image">
                                            @endif
                                        </div>
                                        <div class="author-info">
                                            @php
                                                $name = $review->user->first_name . ' ' . $review->user->last_name;
                                                $date = date_format($review->created_at, 'F d, Y');
                                            @endphp
                                            <h6 class="mb-2 lh-1">{{ $name }}</h6>
                                            <div class="ratings mb-2">
                                                <div class="rate bg-img"
                                                    data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                                    <div class="rating-icon bg-img"
                                                        style="width: {{ $review->rating * 20 }}%; background-image: url({{ asset('assets/frontend/images/rate-star.png') }});"
                                                        data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                                    </div>
                                                </div>
       
                                                <span class="ratings-total">({{ $review->rating }})</span>
                                            </div>
                                            <p class="text">
                                                {{ $review->comment }}
                                            </p>
                                        
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @guest('web')
                            <div class="cta-btn mt-20">
                                <a href="{{ route('user.login', ['redirect_path' => 'product-details']) }}"
                                    class="btn btn-md btn-primary">
                                    {{ __('Login') }}
                                </a>
                            </div>
                        @endguest

                        @auth('web')
                            <div class="shop-review-form mt-30">
                                <h5 class="title mb-10">
                                    {{ __('Add Review') }}
                                </h5>
                                <form action="{{ route('shop.product_details.store_review', ['id' => $details->id]) }}"
                                    method="POST" id="reviewSubmitForm">
                                    @csrf
                                    <div class="form-group mb-20">
                                        <label class="mb-1">{{ __('Comment') }}</label>
                                        <textarea class="form-control" placeholder="{{ __('Comment') }}" name="comment">{{ old('comment') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">{{ __('Rating') . '*' }}</label>
                                        <ul class="rating list-unstyled review-value-list mb-20">
                                            <li class="review-value review-1">
                                                <i class="fas fa-star" data-ratingVal="1"></i>
                                            </li>
                                            <li class="review-value review-2">
                                                <i class="fas fa-star" data-ratingVal="2"></i>
                                                <i class="fas fa-star" data-ratingVal="2"></i>
                                            </li>
                                            <li class="review-value review-3">
                                                <i class="fas fa-star" data-ratingVal="3"></i>
                                                <i class="fas fa-star" data-ratingVal="3"></i>
                                                <i class="fas fa-star" data-ratingVal="3"></i>
                                            </li>
                                            <li class="review-value review-4">
                                                <i class="fas fa-star" data-ratingVal="4"></i>
                                                <i class="fas fa-star" data-ratingVal="4"></i>
                                                <i class="fas fa-star" data-ratingVal="4"></i>
                                                <i class="fas fa-star" data-ratingVal="4"></i>
                                            </li>
                                            <li class="review-value review-5">
                                                <i class="fas fa-star" data-ratingVal="5"></i>
                                                <i class="fas fa-star" data-ratingVal="5"></i>
                                                <i class="fas fa-star" data-ratingVal="5"></i>
                                                <i class="fas fa-star" data-ratingVal="5"></i>
                                                <i class="fas fa-star" data-ratingVal="5"></i>
                                            </li>
                                        </ul>
                                    </div>

                                    <input type="hidden" id="rating-id" name="rating" value="{{ old('rating') }}">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-primary" value="{{ __('Submit') }}">
                                    </div>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
        @if (!empty(showAd(3)))
            <div class="text-center mt-4 mb-40">
                {!! showAd(3) !!}
            </div>
        @endif
    </div>
    <!-- Shop-single-area end -->

    <!-- Related Product-area start -->
    @if (count($related_products) > 0)
        <div class="shop-area pb-75" data-aos="fade-up">
            <div class="container">
                <div class="section-title title-inline mb-30">
                    <h3 class="title mb-20">{{ __('Related Product') }}</h3>
                    <!-- Slider navigation buttons -->
                    <div class="slider-navigation mb-20">
                        <button type="button" title="Slide prev" class="slider-btn slider-btn-prev btn-outline radius-0"
                            id="shop-slider-prev">
                            <i class="fal fa-angle-left"></i>
                        </button>
                        <button type="button" title="Slide next" class="slider-btn slider-btn-next btn-outline radius-0"
                            id="shop-slider-next">
                            <i class="fal fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div class="swiper shop-slider mb-40">
                    <div class="swiper-wrapper">
                        @foreach ($related_products as $product)
                            <div class="swiper-slide">
                                <div class="product-default shadow-none text-center mb-25">
                                    <figure class="product-img mb-15">
                                        <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                                            class="lazy-container ratio ratio-1-1">
                                            <img class="lazyload"
                                                src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                                data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                                                alt="Product">
                                        </a>
                                        <div class="product-overlay">
                                            <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                                                target="_self" title="{{ __('View Details') }}" class="icon"><i
                                                    class="fas fa-eye"></i></a>
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
                                        <h5 class="product-title mb-2">
                                            <a
                                                href="{{ route('shop.product_details', ['slug' => $product->slug]) }}">{{ strlen($product->title) > 15 ? mb_substr($product->title, 0, 15, 'UTF-8') . '...' : $product->title }}</a>
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
                </div>

                @if (!empty(showAd(3)))
                    <div class="text-center mt-4 mb-40">
                        {!! showAd(3) !!}
                    </div>
                @endif
            </div>
        </div>
    @endif
    <!-- Related Product-area end -->
@endsection

@section('custom-js-for-shop')
    <script src="{{ asset('assets/frontend/js/shop.js') }}"></script>
@endsection
