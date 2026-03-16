@extends('frontend.layout')
@section('style')
    <link rel="stylesheet" href="{{ 'assets/css/summernote-content.css' }}">
@endsection

@php
    $imagePath = $details->image ? 'assets/img/posts/' . $details->image : '';
    $fullImagePath = asset($imagePath);
    $currentUrl = url()->current();
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$details" :og-url="$currentUrl" :og-image="$fullImagePath" />


@php
    $title = $details->title ?? __('Blog Details');
    $postDetails = $pageHeading->post_details_page_title ?? __('Blog Details');
@endphp

@section('content')
    <!-- Breadcrumb start -->
    <div class="breadcrumb-area bg-img bg-cover z-1 header-next"
    @if (!empty($breadcrumb)) data-bg-img="{{ asset('./assets/img/' . $breadcrumb) }}" @endif>
    <div class="overlay opacity-75"></div>
    <div class="container">
        <div class="content text-center">
            <h2 class="color-white">{{ !empty($title) ? $title : '' }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}"></a>{{ __('Home') }}</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $postDetails}}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
    <!-- Breadcrumb end -->

    <!-- Blog-details-area start -->
    <div class="blog-details-area pt-50 pb-60">
        <div class="container">
            <div class="row justify-content-center gx-xl-5">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="blog-description mb-40">
                        <article class="item-single">
                            <div class="image">
                                <div class="lazy-container ratio ratio-16-9 radius-md">
                                    <img class="lazyload" src="{{ asset('assets/img/posts/' . $details->image) }}"
                                        data-src="{{ asset('assets/img/posts/' . $details->image) }}" alt="Blog Image">
                                </div>
                            </div>
                            <div class="content">
                                <ul class="info-list">
                                    <li><i class="fal fa-user"></i>{{ $details->author }}</li>
                                    <li><i class="fal fa-calendar"></i>{{ date_format($details->created_at, 'F d, Y') }}
                                    </li>
                                    <li><i class="fal fa-tag"></i>{{ $details->categoryName }}</li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#socialMediaModal"
                                        class="share-btn">
                                        <i class="fas fa-share-alt"></i>
                                        {{ __('Share') . ' ' }}
                                    </a>
                                </ul>
                                <h4 class="title">
                                    {{ $details->title }}
                                </h4>
                                <div class="summernote-content">{!! $details->content !!}</div>
                            </div>
                        </article>
                    </div>
                        <div class="row">
                             @if ($disqusInfo->disqus_status == 1)
                                <div class="col-xl-10">
                                    <div id="disqus_thread"></div>
                                </div>
                             @endif
                        </div>
                    </div>

                <div class="col-lg-4">
                    <aside class="widget-area border radius-md px-25" data-aos="fade-up">
                        <div class="widget widget-search py-25">
                            <h5 class="title mb-15">{{ __('Search Posts') }}</h5>
                            <div class="search-form">
                                <form id="searchForm" action="{{ route('blog') }}" method="GET">
                                    <div class="input-inline bg-white shadow-md rounded-pill">
                                        <input class="form-control border-0" placeholder="{{ __('Search By Title') }}"
                                            name="title"
                                            value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}"
                                            required="">
                                        <input type="hidden" name="category"
                                            value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
                                        <button class="btn-icon rounded-pill search-btn " type="submit"
                                            aria-label="Search button">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- side category bar start --}}
                        @includeIf('frontend.blog.side-bar')
                        {{-- side category bar end --}}
                        <div class="widget widget-post py-25">
                            <h5 class="title">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#blogPost">
                                    {{ __('Recent Post') }}
                                </button>
                            </h5>
                            <div id="blogPost" class="collapse show">
                                <div class="accordion-body mt-20 scroll-y">
                                    @foreach ($recentPost as $post)
                                        <article class="article-item mb-20">
                                            <div class="image">
                                                <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                    target="_self" title="{{ $post->title }}"
                                                    class="lazy-container ratio ratio-1-1 radius-sm">
                                                    <img class="lazyload"
                                                        src="{{ asset('assets/img/posts/' . $post->image) }}"
                                                        data-src="{{ asset('assets/img/posts/' . $post->image) }}"
                                                        alt="Blog Image">
                                                </a>
                                            </div>
                                            <div class="content">
                                                <ul class="info-list">
                                                    <li>{{ $post->author }}</li>
                                                    <li>{{ $post->created_at->format('d/m/Y') }}</li>
                                                </ul>
                                                <h6 class="lc-2 mb-0">
                                                    <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                        target="_self" title="{{ $post->title }}">
                                                        {{ $post->title }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="pb-40"></div>
                        {{-- ad area start --}}
                        @includeIf('frontend.blog.ads')

                        <!-- Modal for social media link -->
                        @include('frontend.partials.social-media-link-modal')
                    </aside>

                    <!-- Spacer -->
                    <div class="pb-40"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog-details-area end -->
    
    @php
        $spaceBannerBgImg = $basicInfo->banner_section_bg_img;
        $spaceBannerForegroundImg = $basicInfo->banner_section_foreground_img;
    @endphp
        <x-home.space-banner :isActiveSection="$isActiveSection" :homeSectionInfo="$homeSectionInfo" :spaceBannerBgImg="$spaceBannerBgImg" :spaceBannerForegroundImg="$spaceBannerForegroundImg" version="1" />
    <!-- Newsletter-area end -->
    @php
        $langCode = $currentLanguageInfo->code ?? 'en';
    @endphp
 
@endsection

@section('custom-script')
    @if ($disqusInfo->disqus_status == 1)
        <script>
            "use strict"
            const shortName = "{{ $disqusInfo->disqus_short_name }}";
            let currentUrl = "{{ $currentUrl }}";
            let postDetailsId = "{{ $details->id }}"; 
            let postTitle = "{{ $details->title }}";  
            let longCode = "{{ $langCode }}";  
        </script>
        <script src="{{ asset('assets/frontend/js/disqus-init.js') }}"></script>
    @endif
@endsection
