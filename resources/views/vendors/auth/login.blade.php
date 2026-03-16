@extends('frontend.layout')
@php
    if (Session::has('currentLocaleCode')) {
        $locale = Session::get('currentLocaleCode');
    }
    app()->setLocale($locale);
@endphp

@php
    $title = $pageHeading->seller_login_page_title ?? __('No Page Title Found');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!--====== Start Login Area Section ======-->
    <div class="user-area-section ptb-100 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="main-form">
                        <div class="user-form">
                            @if (Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif

                        

                            <form action="{{ route('vendor.login_submit') }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Username') . '*' }}</label>
                                    <input type="text" class="form-control" name="username"
                                        placeholder="{{ __('Enter username') }}">
                                    @error('username')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Password') . '*' }}</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="{{ __('Enter password') }}">
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($bs->google_recaptcha_status == 1)
                                    <div class="form-group my-4">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}

                                        @error('g-recaptcha-response')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-lg btn-primary radius-sm">{{ __('Login') }}</button>
                                </div>

                            </form>

                        </div>
                        <div class="justify-content-between d-flex mt-3">
                            <p>
                                {!! formatPunctuation(__("Don\'t have an account"), $direction, true) !!}
                                <a class="color-primary" href="{{ route('vendor.signup') }}">{{ __('Signup Now') }}</a>
                            </p>
                            <a class="color-primary" href="{{ route('vendor.forget.password') }}">
                                {!! formatPunctuation(__('Lost your password'), $direction, true) !!}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== End Login Area Section ======-->
@endsection
