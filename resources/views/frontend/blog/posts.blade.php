@extends('frontend.layout')

@php
    $title = $pageHeading->blog_page_title ?? __('Blog');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])
    <!-- Breadcrumb end -->

    <!-- Blog-area start -->
    <div class="blog-area blog-area_v1 pt-70 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-2 order-lg-1">
                    @if (count($posts) > 0)
                        <div class="row pb-10" data-aos="fade-up">
                            @foreach ($posts as $post)
                                <div class="col-md-6">
                                    <article class="card border radius-md mt-30">
                                        <div class="card_top">
                                            <div class="card_img p-20">
                                                <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                    target="_self" title="Link"
                                                    class="lazy-container radius-sm ratio ratio-2-3">
                                                    <img class="lazyload"
                                                        src="{{ asset('assets/img/posts/' . $post->image) }}"
                                                        data-src="{{ asset('assets/img/posts/' . $post->image) }}"
                                                        alt="Blog Image">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card_content px-20">
                                            <h4 class="card_title lc-2 mb-15">
                                                <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                    target="_self" title="Link">
                                                    {{ strlen($post->title) > 45 ? mb_substr($post->title, 0, 45, 'UTF-8') . '...' : $post->title }}
                                                </a>
                                            </h4>
                                            <p class="card_text lc-2">
                                                {!! strlen(strip_tags($post->content)) > 100
                                                    ? mb_substr(strip_tags($post->content), 0, 100, 'UTF-8') . '...'
                                                    : strip_tags($post->content) !!}
                                            </p>
                                            <div class="cta-btn mt-20">
                                                <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                    class="btn btn-lg btn-secondary radius-sm shadow-md icon-end"
                                                    title="{{ __('READ MORE') }}" target="_self">
                                                    <span>{{ __('READ MORE') }}</span>
                                                    <i class="fal fa-long-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="row">
                            <div class="col">
                                <h3 class="text-center mt-5">{{ __('No results found') . '!' }}</h3>
                            </div>
                        </div>
                    @endif
                    <nav class="pagination-nav mt-20 mb-40" data-aos="fade-up">
                        {{ $posts->appends([
                                'title' => request()->input('title'),
                                'category' => request()->input('category'),
                                'tags' => request()->input('tags'),
                            ])->links() }}
                    </nav>
                    {{-- ad area start --}}
                    @if (!empty(showAd(3)))
                        <div class="text-center mt-2 mb-40">
                            {!! showAd(3) !!}
                        </div>
                    @endif
                    {{-- ad area end --}}
                </div>
                {{-- side bar start --}}
                <div class="col-lg-3 order-1 order-lg-2">
                    <div class="mt-30 d-none d-lg-block"></div>
                    <aside class="widget-area border radius-md px-25" data-aos="fade-up">
                        @includeIf('frontend.blog.side-bar')
                    </aside>
                    @if (!empty(showAd(2)))
                        <div class="text-center mt-30 mb-40">
                            {!! showAd(2) !!}
                        </div>
                    @endif
                </div>
                {{-- side bar end --}}
            </div>
        </div>
    </div>
    <!-- Blog-area end -->

        <!-- Newsletter-area start -->
    @php
        $spaceBannerBgImg = $basicInfo->banner_section_bg_img;
        $spaceBannerForegroundImg = $basicInfo->banner_section_foreground_img;
    @endphp
        <x-home.space-banner :isActiveSection="$isActiveSection" :homeSectionInfo="$homeSectionInfo" :spaceBannerBgImg="$spaceBannerBgImg" :spaceBannerForegroundImg="$spaceBannerForegroundImg" version="1" />
    <!-- Newsletter-area end -->

@endsection
