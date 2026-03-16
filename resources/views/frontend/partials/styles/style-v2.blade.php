<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}" type="image/x-icon">

<!-- Google Fonts -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/fonts.googleapi.css') }}">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/bootstrap.min.css') }}">
<!-- Data Tables CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/datatables.min.css') }}">
<!-- Fontawesome Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/fonts/fontawesome/css/all.min.css') }}">
<!-- Date-range Picker -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/daterangepicker.css') }}">
<!-- Noui Range Slider -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/nouislider.min.css') }}">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/magnific-popup.min.css') }}">
<!-- Swiper Slider -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/swiper-bundle.min.css') }}">
<!-- Nice Select -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/nice-select.css') }}">
<!-- Select 2 -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/select2.min.css') }}">
<!-- AOS Animation CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/aos.min.css') }}">
<!-- Animate CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/animate.min.css') }}">
<!-- Leaflet Map CSS  -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/MarkerCluster.css') }}">
{{-- toastr --}}
<link rel="stylesheet" href="{{ asset('assets/frontend/css/toastr.css') }}" />
{{-- cookie-alert --}}
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/cookie-alert.css') }}" />
{{-- Announcement Popups Css --}}
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/announcement-popup.css') }}" />
{{-- jquery.timepicker --}}
<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors/jquery.timepicker.min.css') }}">
<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
<!-- Responsive CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/responsive.css') }}">

{{-- whatsapp css --}}
<link rel="stylesheet" href="{{ asset('assets/frontend/css/floating-whatsapp.css') }}">
@if (!empty($currentLanguageInfo) && $currentLanguageInfo->direction == 1)
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/rtl.css') }}">
@endif
{{-- user-selected text color for footer --}}
@php
    $footerTextColor = ltrim($footerInfo?->footer_background_color ?? '', '#');
@endphp
@if ($footerTextColor)
<style>
    .footer-area .footer-widget .footer-links i,
    .footer-area .footer-links li a,
    .footer-area .footer-widget .title,
    .footer-area .footer-widget p,
    .footer-area .copy-right-area .container .copy-right-content span {
        color: #{{ $footerTextColor }};
    }
</style>
@endif
