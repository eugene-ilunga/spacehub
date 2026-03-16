@php
    $timezonename = now()->timezoneName;
@endphp
<script>
    'use strict';
    let baseCurrency = "{{ $basicInfo->base_currency_symbol }}";
    let baseCurrencyPosition = "{{ $basicInfo->base_currency_symbol_position }}";
    const baseURL = "{{ url('/') }}";
    const timeZone = "{{ $timezonename }}";
    const timeFormat = "{{ $basicInfo->time_format }}";
    const warningMsgForMultiday =
        "{{ __('Selected date range includes a weekend or holiday. Please choose a different date range') . '.' }}";
    let readLess = "{{ __('Read Less') }}";
    let readMore = "{{ __('Read More') }}";
</script>

<!-- Jquery JS -->
<script src="{{ asset('assets/frontend/js/vendors/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/frontend/js/vendors/bootstrap.min.js') }}"></script>
<!-- Data Tables JS -->
<script src="{{ asset('assets/frontend/js/vendors/datatables.min.js') }}"></script>
<!-- Date-range Picker JS -->
<script src="{{ asset('assets/frontend/js/vendors/moment.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/vendors/daterangepicker.js') }}"></script>
<!-- Noui Range Slider JS -->
<script src="{{ asset('assets/frontend/js/vendors/nouislider.min.js') }}"></script>
<!-- Counter JS -->
<script src="{{ asset('assets/frontend/js/vendors/jquery.counterup.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('assets/frontend/js/vendors/jquery.nice-select.min.js') }}"></script>
<!-- Select 2 JS -->
<script src="{{ asset('assets/frontend/js/vendors/select2.min.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('assets/frontend/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<!-- Swiper Slider JS -->
<script src="{{ asset('assets/frontend/js/vendors/swiper-bundle.min.js') }}"></script>
<!-- Lazysizes -->
<script src="{{ asset('assets/frontend/js/vendors/lazysizes.min.js') }}"></script>
<!-- SVG Loader -->
<script src="{{ asset('assets/frontend/js/vendors/svg-loader.min.js') }}"></script>
<!-- AOS JS -->
<script src="{{ asset('assets/frontend/js/vendors/aos.min.js') }}"></script>
<!-- Mouse Hover JS -->
<script src="{{ asset('assets/frontend/js/vendors/mouse-hover-move.js') }}"></script>
<!-- Leaflet Map JS -->
<script src="{{ asset('assets/frontend/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('assets/frontend/js/vendors/leaflet.markercluster.js') }}"></script>

<!-- toastr JS -->
<script src="{{ asset('assets/frontend/js/vendors/toastr.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/vendors/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/vendors/moment-timezone-with-data.min.js') }}"></script>
{{-- announcement-popup --}}
<script src="{{ asset('assets/frontend/js/vendors/announcement-popup.js') }}"></script>

{{-- whatsapp js --}}
<script src="{{ asset('assets/frontend/js/floating-whatsapp.js') }}"></script>

{{-- whatsapp init code --}}
@if ($basicInfo->whatsapp_status == 1)
    <script type="text/javascript">
    "use strict"
        var whatsapp_popup = "{{ $basicInfo->whatsapp_popup_status }}";
        var whatsappImg = "{{ asset('assets/img/whatsapp.svg') }}";
        var whHeaderTitle = "{{ $basicInfo->whatsapp_header_title }}";
        var whpopupMessage = "{{ addslashes($basicInfo->whatsapp_popup_message) }}";
        var whPhoneNumber = "{{ $basicInfo->whatsapp_number }}";
        var whPrimaryColor = "var(--color-primary, #128C7E)";
    </script>
    <script src="{{ asset('assets/frontend/js/whatsapp-init.js') }}"></script>
@endif



@if (session()->has('success'))
    <script>
        toastr['success']("{{ __(session('success')) }}");
    </script>
@endif

@if (session()->has('error'))
    <script>
        toastr['error']("{{ __(session('error')) }}");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        toastr['warning']("{{ __(session('warning')) }}");
    </script>
@endif
@yield('variables')
<!-- Map JS -->

@if (session()->has('formType') && $errors->any())
    <script>
        $(document).ready(function() {
            var formType = "{{ session('formType') }}";
            if (formType == 'getQuoteModal') {
                $('#getQuoteModal').modal('show');
            } else if (formType == 'bookATourModal') {
                $('#bookATourModal').modal('show');
            }
        });
    </script>
@endif

<script src="{{ asset('assets/frontend/js/negotiable-modal-open.js') }}"></script>

@yield('script')

<!-- Main script JS -->
<script src="{{ asset('assets/frontend/js/subscribe-booking-form.js') }}"></script>
<script src="{{ asset('assets/frontend/js/script.js') }}"></script>

{{-- custom script js --}}
<script src="{{ asset('assets/frontend/js/wishlist-location.js') }}"></script>
@yield('custom-script')

@yield('custom-js-for-shop')
<script src="{{ asset('assets/frontend/js/cart.js') }}"></script>
