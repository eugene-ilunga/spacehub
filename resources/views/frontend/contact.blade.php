@extends('frontend.layout')

@php
    $title = $pageHeading->contact_page_title ?? __('Contact');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />
@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])
    <!-- Breadcrumb end -->

    <!-- Contact-area start -->
    <div class="contact-area pt-100 pb-60">
        <div class="container">
            <div class="section-title title-center mb-50">
                <h2 class="title mb-20">{{ __(@$contactContent->title) }}</h2>
                <p class="text ">
                    {!! @$contactContent->text !!}
                </p>
            </div>
            @if (count($mobiles) > 0)
                <div class="contact-info row justify-content-center">
                    <div class="col-lg-4 col-md-6 item">
                        <div class="card text-center shadow-md radius-md mb-30" data-aos="fade-up">
                            <div class="icon bg-primary-light mx-auto radius-sm">
                                <i class="fal fa-phone-plus"></i>
                            </div>
                            <div class="card-text mt-20">
                                <span class="mb-15 d-inline-block">{{ __('MOBILE') }}</span>
                                @foreach ($mobiles as $mobile)
                                    <span class="h6 mb-10"><a href="tel:{{ @$mobile }}" target="_self"
                                            title="{{ @$mobile }}">{{ @$mobile }}</a></span>
                                @endforeach
                            </div>
                        </div>
                    </div>
            @endif
            @if (count($emails) > 0)
                <div class="col-lg-4 col-md-6 item">
                    <div class="card text-center shadow-md radius-md mb-30" data-aos="fade-up">
                        <div class="icon bg-primary-light mx-auto radius-sm">
                            <i class="fal fa-envelope"></i>
                        </div>
                        <div class="card-text mt-20">
                            <span class="mb-15 d-inline-block">{{ __('EMAIL') }}</span>
                            @foreach ($emails as $email)
                                <span class="h6 mb-10"><a href="mailTo:{{ $email }}" target="_self"
                                        title="{{ $email }}">{{ $email }}</a></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if (!empty($contactContent->location))
                <div class="col-lg-4 col-md-6 item">
                    <div class="card text-center shadow-md radius-md mb-30" data-aos="fade-up">
                        <div class="icon bg-primary-light mx-auto radius-sm">
                            <i class="fal fa-map-marker-alt"></i>
                        </div>

                        <div class="card-text mt-20">
                            <span class="mb-15 d-inline-block">{{ __('LOCATION') }}</span>
                            <span class="h6 mb-10">{{ @$contactContent->location }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Spacer -->
        <div class="pb-70"></div>

        <div class="row gx-xl-5">
            <div class="col-lg-6 mb-40" data-aos="fade-up">

                <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-20">
                                <label for="name" class="form-label font-sm">{{ __('Name') . '*' }}</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="{{ __('Enter Your Full Name') }}">
                                @error('name')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-20">
                                <label for="email" class="form-label font-sm">{{ __('Email') . '*' }}</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="{{ __('Enter Your Email Address') }}">
                                @error('email')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-20">
                                <label for="subject" class="form-label font-sm">{{ __('Subject') . '*' }}</label>
                                <input type="text" name="subject" class="form-control" id="subject"
                                    placeholder="{{ __('Enter Email Subject') }}">
                                @error('subject')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-20">
                                <label for="message" class="form-label font-sm">{{ __('Message') . '*' }}</label>
                                <textarea name="message" id="message" class="form-control" cols="30" rows="8"
                                    placeholder="{{ __('Write Your Message') }}"></textarea>
                                @error('message')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        @if ($info->google_recaptcha_status == 1)
                            <div class="col-lg-12">
                                <div class="form-group mb-20 mb-20">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <button class="btn btn-lg btn-primary radius-sm" type="submit"
                                aria-label="Send Message">{{ __('Send Message') }}</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-lg-6 mb-40" data-aos="fade-up">
                <div class="map h-100 overflow-hidden radius-md">
                    <iframe class="lazyload h-100" id="map"
                        src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $info->latitude }},%20{{ $info->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Contact-area end -->

    @if (!empty(showAd(3)))
        <div class="text-center mb-4">
            {!! showAd(3) !!}
        </div>
    @endif

    <!-- Newsletter-area start -->
    @php
      $spaceBannerBgImg = $basicInfo->banner_section_bg_img ?? null;
      $spaceBannerForegroundImg = $basicInfo->banner_section_foreground_img ?? null;
    @endphp

    <x-home.space-banner :isActiveSection="$isActiveSection" :homeSectionInfo="$homeSectionInfo" :spaceBannerBgImg="$spaceBannerBgImg" :spaceBannerForegroundImg="$spaceBannerForegroundImg" version="1" />
    <!-- Newsletter-area end -->
@endsection
