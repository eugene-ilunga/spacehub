@php
    $notAbleToRevert = __('You will not be able to revert this') . '!';
@endphp

<script>
    'use strict';
    const baseUrl = "{{ url('/') }}";
    let curr_url = "{{ url()->current() }}";
    let queryString = "{{ request()->getQueryString() }}";
    let demo_mode = "{{ env('DEMO_MODE') }}";
    let timeFormatForSearch = '{{ $settings->time_format }}';
    let baseCurrency = '{{ $settings->base_currency_symbol }}';
    let baseCurrencyPosition = '{{ $settings->base_currency_symbol_position }}';
    let timeFormat = 'h:i A';
    if (timeFormatForSearch == '12h') {
        let timeFormat = 'h:i A';
    } else {
        timeFormat = 'H:i';
    }
    const timeZone = "{{ $settings->time_zone }}";
    const warningMsgForMultiday =
        "{{ __('Selected date range includes a weekend or holiday. Please choose a different date range') . '.' }}";
    let warningTxt = "{{ __('Warning') }}";
    let errorTxt = "{{ __('Error') }}";
    let successTxt = "{{ __('Success') }}";
    let featureLimitTxt = "{{ __('Your feature limit is over or down graded') . '!' }}";
    let loginAlertTxt = "{{ __('Your account needs admin approval') . '!' }}";
    let alertTxt = "{{ __('Alert') }}";
    let ticketCloseTxt = "{{ __('You want to close this ticket') . '!' }}";
    let ticketCloseYesTxt = "{{ __('Yes, close it') . '.' }}";
    let aminityLimitTxt = "{{ __('You can only select up to') }}";
    let aminityTxt = "{{ __('amenities') . '.' }}";
    let selectAStateTxt = "{{ __('Select a State') }}";
    let selectACityTxt = "{{ __('Select a City') }}";
    let selectACountryTxt = "{{ __('Select a Country') }}";
    let selectASubcategoryTxt = "{{ __('Select a Subcategory') }}";
    let selectACategoryTxt = "{{ __('Select a Category') }}";
    let categoryNotFoundTxt = "{{ __('Category Not Found') }}";
    let noSpaceAvailableTxt = "{{ __('No spaces available') }}";
    let latAmenityDeleteWarningTxt = "{{ __('Sorry, the last amenity cannot be deleted') . '.' }}";
</script>

{{-- core js files --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>

{{-- jQuery ui --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery timepicker --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/sweet-alert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>

{{-- tinymce js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- js color --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jscolor.min.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/fontawesome-iconpicker.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/datatables.bootstrap4.min.js') }}"></script>

{{-- dropzone js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/dropzone.min.js') }}"></script>

{{-- atlantis js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/atlantis.js') }}"></script>

{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
{{-- nice select js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.nice-select.min.js') }}"></script>

<!-- Date-range Picker JS -->
<script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/admin/js/flatpickr.js') }}"></script>


{{-- setup csrf-token for ajax request --}}
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let account_status = 1;
    let secret_login = 1;
</script>

{{-- fonts and icons script --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/webfont.min.js') }}"></script>

<script>
    WebFont.load({
        google: {
            "families": ["Lato:300,400,700,900"]
        },
        custom: {
            "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                "simple-line-icons"
            ],
            urls: ["{{ asset('assets/admin/css/fonts.min.css') }}"]
        },
        active: function() {
            sessionStorage.fonts = true;
        }
    });
</script>

@if (session()->has('success'))
    <script>
        var content = {};

        content.message = "{{ session('success') }}";
        content.title = successTxt;
        content.icon = 'fas fa-check-circle';

        $.notify(content, {
            type: 'success',
            placement: {
                from: 'top',
                align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000,
        });
    </script>
@endif

@if (session()->has('warning'))
    <script>
        var content = {};

        content.message = "{{ session('warning') }}";
        content.title = warningTxt;
        content.icon = 'fas fa-exclamation-circle';

        $.notify(content, {
            type: 'warning',
            placement: {
                from: 'top',
                align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000
        });
    </script>
@endif

@if (session()->has('error'))
    <script>
        var content = {};

        content.message = "{{ session('error') }}";
        content.title = errorTxt;
        content.icon = 'fas fa-times-circle';

        $.notify(content, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000
        });
    </script>
@endif
@yield('variable')

{{-- admin-main js --}}

<script type="text/javascript" src="{{ asset('assets/admin/js/admin-main.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/sub-service.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/notifyMessageForAjax.js') }}"></script>


