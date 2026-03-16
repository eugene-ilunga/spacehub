<!DOCTYPE html>
@php
    use Illuminate\Support\Str;
    use App\Models\Language;
    $selLang = App\Models\Language::where('code', request()->input('language'))->first();
        $vendorLang = session('vendor_lang');
    if ($vendorLang) {
        $langCode = Str::after($vendorLang, 'admin_');
    } elseif (!empty($selLang)) {
        $langCode = $selLang->code;
    } else {
        $langCode = Language::where('is_default', 1)->value('code');
    }

     $direction = Language::where('code', $langCode)->value('direction');

@endphp

<html lang="{{ $langCode }}" @if ($direction == 1) dir="rtl" @endif>

<head>
    {{-- required meta tags --}}
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>{{ __('Vendor') . ' | ' . $websiteInfo->website_title }}</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

    {{-- include styles --}}
    @includeIf('vendors.partials.styles')

    {{-- additional style --}}
    @yield('style')
</head>

<body data-background-color="{{ Session::get('seller_theme_version') == 'light' ? 'white2' : 'dark' }}">
    {{-- loader start --}}
    <div class="request-loader">
        <img src="{{ asset('assets/img/loader.gif') }}" alt="loader">
    </div>
    {{-- loader end --}}

    <div class="wrapper">
        {{-- top navbar area start --}}
        @includeIf('vendors.partials.top-navbar')
        {{-- top navbar area end --}}

        {{-- side navbar area start --}}
        @includeIf('vendors.partials.side-navbar')
        {{-- side navbar area end --}}

        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
            @includeIf('vendors.partials.limit-check')

            {{-- footer area start --}}
            @includeIf('vendors.partials.footer')
            {{-- footer area end --}}
        </div>
    </div>

    @php
        $notAbleToRevert = __('You will not be able to revert this');
    @endphp
    <script>
        'use strict'
        var areYouSure = "{{ __('Are you sure?') }}";
        var notAbleToRevert = "{{ $notAbleToRevert }}";
        var yesDeleteiIt = "{{ __('Yes, delete it') }}";
        var CancelText = "{{ __('Cancel') }}";
    </script>

    {{-- include scripts --}}
    @includeIf('vendors.partials.scripts')

    {{-- additional script --}}
    @yield('variable')
    @yield('script')


</body>

</html>
