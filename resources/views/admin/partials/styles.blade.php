{{-- fontawesome css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">

{{-- fontawesome icon picker css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}">

{{-- bootstrap css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">

{{-- bootstrap tags-input css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-tagsinput.css') }}">

{{-- jQuery-ui css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.min.css') }}">

{{-- jQuery-timepicker css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.timepicker.min.css') }}">

{{-- bootstrap-datepicker css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.min.css') }}">

{{-- dropzone css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/dropzone.min.css') }}">

{{-- atlantis css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/atlantis.css') }}">

{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
{{-- Nice Select css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/nice-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
 {{-- flatpickr css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">

{{-- admin-main css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/admin-main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/main-default-backend.css') }}">

@if ($direction == 1)
  <link rel="stylesheet" href="{{asset('assets/admin/css/admin-rtl.css')}}">
@endif
@yield('css-for-add-booking')

