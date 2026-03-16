<!DOCTYPE html>
<html lang="{{ $currentLanguageInfo ? $currentLanguageInfo->code : 'en' }}"
    @if ($currentLanguageInfo && $currentLanguageInfo->direction == 1) dir="rtl" @endif>

<head>
    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (View::hasSection('metaKeywords'))
        <meta name="keywords" content="@yield('metaKeywords')">
    @endif
    @if (View::hasSection('metaDescription'))
        <meta name="description" content="@yield('metaDescription')">
    @endif


    {{-- og meta tags --}}
    <meta property="og:url" content="@yield('og-url')">
    <meta property="og:image" itemprop="image" content="@yield('og-image')">
    <meta property="og:image:type" content="image/jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og-title')" />
    <meta property="og:description" content="@yield('og-description')" />
    @php
        $websiteTitle = $websiteInfo->website_title ? ' | ' . strip_tags($websiteInfo->website_title) : '';
    @endphp

    {{-- title --}}
    <title>@yield('pageHeading') {{ $websiteTitle }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}" type="image/x-icon">

    {{-- include styles --}}
    @if ($basicInfo->theme_version == 1)
        @includeIf('frontend.partials.styles.style-v1')
    @elseif ($basicInfo->theme_version == 2)
        @includeIf('frontend.partials.styles.style-v2')
    @elseif ($basicInfo->theme_version == 3)
        @includeIf('frontend.partials.styles.style-v3')
    @endif
    @php
        $primaryColor = $basicInfo->primary_color;
    @endphp

    <style>
        :root {
            --color-primary: #{{ $primaryColor }};
            --color-primary-rgb: {{ hexToRgb($primaryColor) }};
        }

        .breadcrumbs-area::after {
            background-color: #{{ $basicInfo->breadcrumb_overlay_color }};
            opacity: {{ $basicInfo->breadcrumb_overlay_opacity }};
        }
    </style>

</head>

<body class="@if ($basicInfo->theme_version == 3) theme-dark @else '' @endif">

    <!-- General Page Preloader -->
    @if (isset($basicInfo->preloader_status) && $basicInfo->preloader_status == 1)
        <div id="preLoader" data-preloader_status="{{ $basicInfo->preloader_status }}">
            <img src="{{ asset('assets/img/' . $basicInfo->preloader) }}" alt="">
        </div>
    @else
        <div id="preLoader">
            <div class="loader"></div>
        </div>
    @endif
    <!-- Preloader end -->

    <!-- AJAX Form Preloader -->
    <div class="ajaxPreLoader d-none" id="preLoader" >
        <div class="loader"></div>
    </div>

    <div class="main-wrapper">

        <!-- Header-area start -->
        @if ($basicInfo->theme_version == 1)
            @includeIf('frontend.partials.header.header-nav-v1')
        @elseif ($basicInfo->theme_version == 2)
            @includeIf('frontend.partials.header.header-nav-v2')
        @elseif ($basicInfo->theme_version == 3)
            @includeIf('frontend.partials.header.header-nav-v3')
        @endif
        <!-- Header-area end -->
        @yield('content')
    </div>

    {{-- announcement popup --}}
    @includeIf('frontend.partials.popups')

    {{-- cookie alert --}}
    @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
        @includeIf('cookie-consent::index')
    @endif

    <!-- Footer-area start -->
    @if ($basicInfo->theme_version == 1)
        @includeIf('frontend.partials.footer.footer-v1')
    @elseif ($basicInfo->theme_version == 2)
        @includeIf('frontend.partials.footer.footer-v2')
    @elseif ($basicInfo->theme_version == 3)
        @includeIf('frontend.partials.footer.footer-v3')
    @endif
    <!-- Footer-area end-->
    
    <!-- Go to Top -->
    <div class="go-top"><i class="fal fa-angle-up"></i></div>
    <!-- Go to Top -->

    {{-- floating whatsapp button --}}

    @if ($basicInfo->whatsapp_status == 1)
        <div id="whatsapp-btn"></div>
    @endif

    @yield('variable')
    @yield('script')
    <!-- Jquery JS -->
    @if ($basicInfo->theme_version == 1)
        @includeIf('frontend.partials.scripts.script-v1')
    @elseif ($basicInfo->theme_version == 2)
        @includeIf('frontend.partials.scripts.script-v2')
    @elseif ($basicInfo->theme_version == 3)
        @includeIf('frontend.partials.scripts.script-v3')
    @endif
    {{-- additional script --}}

</body>

</html>
