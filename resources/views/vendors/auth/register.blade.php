@extends('frontend.layout')
@php
    if (Session::has('currentLocaleCode')) {
        $locale = Session::get('currentLocaleCode');
    }
    app()->setLocale($locale);
@endphp

@php
    $title = $pageHeading->seller_signup_page_title ?? __('No Page Title Found');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!--====== Start Signup Area Section ======-->
    <div class="user-area-section ptb-100 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="main-form">
                        <div class="user-form">
                            

                            <form action="{{ route('vendor.signup_submit') }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter Name') }}">
                                    @error('name')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Username') . '*' }}</label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                        placeholder="{{ __('Enter username') }}">
                                    @error('username')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Email Address') . '*' }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        placeholder="{{ __('Enter email address') }}">
                                    @error('email')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Phone') . '*' }}</label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                        placeholder="{{ __('Enter phone number') }}">
                                    @error('phone')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Password') . '*' }}</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="password"
                                            value="{{ old('password') }}" placeholder="{{ __('Enter password') }}">
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-20">
                                    <label class="form-label font-sm">{{ __('Confirm Password') . '*' }}</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            value="{{ old('password_confirmation') }}"
                                            placeholder="{{ __('Re-enter password') }}">
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($recaptchaStatus == 1)
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
                                        class="btn btn-lg btn-primary radius-sm">{{ __('Signup') }}</button>
                                </div>

                            </form>
                        </div>
                        <p class="mt-3 text-center">
                            {!! formatPunctuation(__('Already have an account'), $direction, true) !!}
                            <a class="color-primary" href="{{ route('vendor.login') }}">{{ __('Login Now') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== End Signup Area Section ======-->
@endsection
