@extends('frontend.layout')

@php
    $title = $pageHeading->about_us_page_title ?? __('About Us');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title ?? ''])
    <!-- Breadcrumb end -->
    @if ($secInfo->about_section_status == 1)
        <!-- About-area start -->
        @if (!empty($aboutData->title))
            <section class="about-area about-area_v1 pt-100 pb-60">
                <div class="container-fluid px-0">
                    <div class="row align-items-center gx-xl-5" data-aos="fade-up">
                        <div class="col-lg-6">
                            <div class="image mb-40">
                                <img class="lazyload blur-up"
                                    src="{{ asset('assets/img/' . $aboutInfo->about_section_image) }}"
                                    data-src="{{ asset('assets/img/' . $aboutInfo->about_section_image) }}" alt="Image">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="content-title fluid-right pb-20">
                                <h2 class="title mb-20">
                                    {{ @$aboutData->title }}
                                </h2>
                                <p>
                                    {!! @$aboutData->text !!}
                                </p>
                                <div class="info-list mt-30">
                                    @if ($aboutContent->isNotEmpty())
                                        @foreach ($aboutContent as $content)
                                            <div class="card mb-20">
                                                <div class="card_content">
                                                    <span class="h4 lh-1 mb-1">{{ @$content->sub_title }}</span>
                                                    <p class="card_text">
                                                        {{ @$content->sub_text }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>{{ __('No content available.') }}</p>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <div class="row">
                <div class="col">
                    <h3 class="text-center mt-5">
                        {{ __('Currently, there’s no content available in this section. We appreciate your patience!') }}
                    </h3>
                </div>
            </div>
        @endif
    @endif
    <!-- About-area end -->
    <!-- Works-area start -->
    @if ($basicInfo->theme_version == 1)
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
                                        <div class="arrow">
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
    @elseif ($basicInfo->theme_version == 2)
        @if ($isActiveSection->work_process_section_status == 1)
            <section class="works-area works-area_v2 pt-100 pb-70 bg-img bg-cover"
                data-bg-img="{{ asset('assets/frontend/images/works-bg-1.jpg') }}" data-aos="fade-up">
                <div class="container">
                    <div class="section-title title-center mb-50">
                        @if (!empty($homeSectionInfo->workprocess_section_title))
                            <h2 class="title">{{ $homeSectionInfo->workprocess_section_title }}</h2>
                        @else
                            <h2 class="title">{{ __('How Spacekoi Platform Work Perfectly') }}</h2>
                        @endif
                    </div>
                    @if (count($allFeature) > 0)
                        <div class="row gx-xl-5">
                            @foreach ($allFeature as $feature)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card mb-30">
                                        <div class="card_top">
                                            <span class="card_number h3 mb-0 border">{{ $feature->number }}</span>
                                            <img class="lazyload"
                                                src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                                data-src="{{ $loop->last ? '' : asset('/') . 'assets/frontend/images/icon/arrow-right.png' }}"
                                                alt="Image">
                                        </div>
                                        <div class="card_content border p-30">
                                            <div class="card_icon mb-25">
                                                <i class="{{ $feature->icon }}"></i>
                                            </div>
                                            <h4 class="card_title lc-1 mb-15">{{ @$feature->title }}</h4>
                                            <p class="card_text lc-2">
                                                {{ @$feature->description }}
                                            </p>
                                        </div>
                                    </div>
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
    @else
        @if ($isActiveSection->work_process_section_status == 1)
            <section class="works-area works-area_v3 pt-100 pb-70" data-aos="fade-up">
                <div class="container">
                    <div class="section-title title-center mb-50">
                        @if (!empty($homeSectionInfo->workprocess_section_title))
                            <h2 class="title">{{ $homeSectionInfo->workprocess_section_title }}</h2>
                        @else
                            <h2 class="title">{{ __('How MultiSpace Platform Work Perfectly') }}</h2>
                        @endif
                    </div>
                    @if (count($allFeature) > 0)
                        <div class="row">
                            @foreach ($allFeature as $feature)
                                <div class="col-lg-4 col-md-6 item mb-30">
                                    <div class="card text-center">
                                        <span class="card_number h3 mb-0 mx-auto">{{ $feature->number }}</span>
                                        <div class="card_wrapper radius-xl bg-primary-light">
                                            <div class="card_icon mx-auto mb-25">
                                                <i class="{{ $feature->icon }}"></i>
                                            </div>
                                            <div class="card_content">
                                                <h5 class="card_title lc-1 mb-15">{{ @$feature->title }}</h5>
                                                <p class="card_text lc-2">
                                                    {{ @$feature->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <div class="arrow">
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

    @endif
    <!-- Works-area end -->

    <!-- Testimonial-area start -->
    @if ($basicInfo->theme_version == 1)
        @if ($isActiveSection->testimonials_section_status == 1)
            <section class="testimonial-area testimonial-area_v1 pb-100" data-aos="fade-up">
                <div class="container">
                    <div class="wrapper radius-md p-30 bg-img bg-cover"
                        data-bg-img="{{ asset('assets/frontend/images/testimonial-bg-1.jpg') }}">
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
                                                        <h3 class="text-center mt-5">
                                                            {{ __('No Testimonial Found') . '!' }}
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
                                            <div class="swiper-pagination mt-20" id="testimonial-slider-1-pagination">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="avatar">
                            @foreach ($testimonials->take(4) as $testimonial)
                                <img class="lazyload avatar-1 rounded-pill"
                                    src="{{ asset('assets/frontend/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                    alt="Person Image">
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @elseif ($basicInfo->theme_version == 2)
        @if ($isActiveSection->testimonials_section_status == 1)
            <!-- Testimonial-area start -->
            <section class="testimonial-area testimonial-area_v2 ptb-100" data-aos="fade-up">
                <div class="container">
                    <div class="section-title title-center mb-50">
                        <h2 class="title mb-20">
                            @if (!empty($homeSectionInfo->testimonial_title))
                                <h2 class="title">{{ @$homeSectionInfo->testimonial_title }}</h2>
                            @else
                                <h2 class="title">{{ __('What Our Trusted Clients Say About Us') }}</h2>
                            @endif
                        </h2>
                    </div>
                    @if (count($testimonials) == 0)
                        <div class="row">
                            <div class="col">
                                <h3 class="text-center mt-5">{{ __('No Testimonial Found') . '!' }}</h3>
                            </div>
                        </div>
                    @else
                        <div class="swiper" id="testimonial-slider-2">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="slider-item p-25">
                                            <div class="quote">
                                                <p class="text mb-0">
                                                    {{ @$testimonial->comment }}
                                                </p>
                                            </div>
                                            <div class="client mt-25">
                                                <div class="client-info d-flex align-items-center">
                                                    <div class="client-img">
                                                        <div class="lazy-container rounded-circle ratio ratio-1-1">
                                                            <img class="lazyload"
                                                                src="{{ asset('assets/img/clients/' . @$testimonial->image) }}"
                                                                data-src="{{ asset('assets/img/clients/' . @$testimonial->image) }}"
                                                                alt="Person Image">
                                                        </div>
                                                    </div>
                                                    <div class="content">
                                                        <h6 class="name">{{ @$testimonial->name }}</h6>
                                                        <span class="designation">{{ @$testimonial->occupation }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="swiper-pagination position-relative mt-25" id="testimonial-slider-2-pagination">
                            </div>
                        </div>
                    @endif

                </div>
            </section>
            <!-- Testimonial-area end -->
        @endif
    @else
        @if ($isActiveSection->testimonials_section_status == 1)
            <section class="testimonial-area testimonial-area_v3 pb-60" data-aos="fade-up">
                <div class="container">
                    <div class="row gx-xl-5">
                        <div class="col-lg-6">
                            <div class="content-title mb-25">
                                @if (!empty($homeSectionInfo->testimonial_title))
                                    <h2 class="title">{{ @$homeSectionInfo->testimonial_title }}</h2>
                                @else
                                    <h2 class="title">{{ __('What Say Our Happy Clients About Us') }}</h2>
                                @endif
                            </div>
                            <div class="swiper mb-40" @if (count($testimonials) > 0) id="testimonial-slider-3" @endif>
                                <div class="swiper-wrapper">
                                    @foreach ($testimonials as $testimonial)
                                        <div class="swiper-slide">
                                            <div class="slider-item">
                                                <div class="quote">
                                                    <p class="text mb-0">
                                                        {{ @$testimonial->comment }}
                                                    </p>
                                                </div>
                                                <div class="client mt-25">
                                                    <div class="client-info d-flex align-items-center">
                                                        <div class="client-img">
                                                            <div class="lazy-container rounded-circle ratio ratio-1-1">
                                                                <img class="lazyload"
                                                                    src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                                                    data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                                                    alt="Person Image">
                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <h6 class="name">{{ @$testimonial->name }}</h6>
                                                            <span
                                                                class="designation">{{ @$testimonial->occupation }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="swiper-pagination" id="testimonial-slider-3-pagination"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="testimonial-images mb-40">
                                <img class="lazyload radius-xl"
                                    src="{{ asset('assets/img/clients/' . @$testimonialClientImage1->image) }}"
                                    data-src="{{ asset('assets/img/clients/' . @$testimonialClientImage1->image) }}"
                                    alt="Image">
                                <img class="lazyload radius-xl"
                                    src="{{ asset('assets/img/clients/' . @$testimonialClientImage2->image) }}"
                                    data-src="{{ asset('assets/img/clients/' . @$testimonialClientImage2->image) }}"
                                    alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    @if (!empty(showAd(3)))
        <div class="text-center mb-4">
            {!! showAd(3) !!}
        </div>
    @endif

    <!-- Newsletter-area start -->
    @php
        $spaceBannerBgImg = $basicInfo->banner_section_bg_img;
        $spaceBannerForegroundImg = $basicInfo->banner_section_foreground_img;
    @endphp
        <x-home.space-banner :isActiveSection="$isActiveSection" :homeSectionInfo="$homeSectionInfo" :spaceBannerBgImg="$spaceBannerBgImg" :spaceBannerForegroundImg="$spaceBannerForegroundImg" version="1" />
    <!-- Newsletter-area end -->

@endsection
