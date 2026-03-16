@props([
    'isActiveSection',
    'homeSectionInfo',
    'spaceBannerBgImg' => null,
    'spaceBannerForegroundImg' => null,
    'basicInfo' => null,
    'version' => '1',
])

@if ($isActiveSection->space_banner_section_status == 1)
    @if ($version == '1')
        <!-- Original Version 1 Code -->
        <section class="newsletter-area newsletter-area_v1 pb-100" data-aos="fade-up">
            <div class="container">
                <div class="newsletter-inner position-relative overflow-hidden z-1 p-30 radius-md bg-img bg-cover"
                    data-bg-img="{{ asset('assets/img/' . $spaceBannerBgImg) }}">
                    <div class="overlay"></div>
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6">
                            <div class="content-title">
                                @if (!empty($homeSectionInfo->banner_section_title))
                                    <h2 class="title mb-30 color-white">
                                        {{ @$homeSectionInfo->banner_section_title }}
                                    </h2>
                                @else
                                    <h2 class="title mb-30 color-white">
                                        {{ __('No Highlights to Showcase Yet!') }}
                                    </h2>
                                @endif
                                <a href="{{ route('space.index') }}" class="btn btn-lg btn-primary radius-sm"
                                    title="{{ @$homeSectionInfo->banner_section_title }}"
                                    target="_self">{{ isset($homeSectionInfo->banner_section_button_text)
                                        ? $homeSectionInfo->banner_section_button_text
                                        : __('Start Space Booking') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="image">
                                <img class="lazyload" src="{{ asset('assets/img/' . $spaceBannerForegroundImg) }}"
                                    data-src="{{ asset('assets/img/' . $spaceBannerForegroundImg) }}" alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif($version == '2')
        <!-- Original Version 2 Code -->
        <section class="newsletter-area newsletter-area_v2" data-aos="fade-up">
            <div class="container">
                <div class="newsletter-inner position-relative overflow-hidden z-1 p-5 bg-img bg-cover"
                    data-bg-img="{{ asset('assets/img/' . @$spaceBannerBgImg) }}">
                    <div class="overlay"></div>
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-6">
                            <div class="content-title">
                                @if (!empty($homeSectionInfo->banner_section_title))
                                    <h2 class="title mb-30 color-white">
                                        {{ @$homeSectionInfo->banner_section_title }}
                                    </h2>
                                @else
                                    <h2 class="title mb-30 color-white">
                                        {{ __('No Highlights to Showcase Yet!') }}
                                    </h2>
                                @endif
                                <a href="{{ route('space.index') }}" class="btn btn-lg btn-primary"
                                    title="{{ @$homeSectionInfo->banner_section_title }}"
                                    target="_self">{{ isset($homeSectionInfo->banner_section_button_text)
                                        ? $homeSectionInfo->banner_section_button_text
                                        : __('Start Space Booking') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif($version == '3')
        <!-- Original Version 3 Code -->
        <section class="newsletter-area newsletter-area_v3" data-aos="fade-up">
            <div class="newsletter-inner position-relative overflow-hidden z-1 pt-50 pb-50 bg-img bg-cover"
                data-bg-img="{{ asset('assets/img/' . $spaceBannerBgImg) }}">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6">
                            <div class="content-title ">
                                @if (!empty($homeSectionInfo->banner_section_title))
                                    <h2 class="title mb-30 color-white">
                                        {{ @$homeSectionInfo->banner_section_title }}
                                    </h2>
                                @else
                                    <h2 class="title mb-30 color-white">
                                        {{ __('No Highlights to Showcase Yet!') }}
                                    </h2>
                                @endif
                                <a href="{{ route('space.index') }}" class="btn btn-lg btn-primary rounded-pill"
                                    title="{{ @$homeSectionInfo->banner_section_title }}"
                                    target="_self">{{ isset($homeSectionInfo->banner_section_button_text)
                                        ? $homeSectionInfo->banner_section_button_text
                                        : __('Start Space Booking') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="image">
                                <img class="lazyload" src="{{ asset('assets/img/' . $spaceBannerForegroundImg) }}"
                                    data-src="{{ asset('assets/img/' . $spaceBannerForegroundImg) }}" alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif
