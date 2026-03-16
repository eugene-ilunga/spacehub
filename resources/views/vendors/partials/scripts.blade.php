<script>
    'use strict';
    let demo_mode = "{{ env('DEMO_MODE') }}";
    let timeFormatForSearch = '{{ $settings->time_format }}';
    let baseCurrency = '{{ $settings->base_currency_symbol }}';
    let baseCurrencyPosition = '{{ $settings->base_currency_symbol_position }}';
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
    let receiptTxt = "{{ __('Receipt') }}";
    let Receipt_image_must_be = "{{ __('Receipt image must be') . ' ' . __('jpg / jpeg / png') }}";
    let receiptImageIsRequired = "{{ __('Please upload a receipt image') . '.' }}";

</script>

{{-- core js files --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>

 {{-- Bootstrap v5.3.3 --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>

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

{{--  nice-select.min.js  --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/nice-select.min.js') }}"></script>

{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/select2.min.js') }}"></script>

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
            delay: 4000
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

<script>
    var account_status = {{ Auth::guard('seller')->user()->status }};
    var baseUrl = "{{ route('index') }}";
</script>
@if (session()->has('secret_login'))
    <script>
        var secret_login = {{ Session::get('secret_login') }};
    </script>
@else
    <script>
        var secret_login = 0;
    </script>
@endif

{{-- admin-main js --}}

<script type="text/javascript" src="{{ asset('assets/admin/js/admin-main.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/sub-service.js') }}"></script>


@if (session()->has('modal-display'))
    <script>
        $(document).ready(function() {
            $('#packageLimitModal').modal('show');
        });
    </script>
    @php
        session()->forget('modal-display');
    @endphp
@endif

<script type="text/javascript" src="{{ asset('assets/admin/js/notifyMessageForAjax.js') }}"></script>
