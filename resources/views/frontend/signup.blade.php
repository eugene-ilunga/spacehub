@extends('frontend.layout')

@php
    $title = $pageHeading->signup_page_title ?? __('Signup');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!-- Sign up Start -->
    <div class="authentication-area bg-light ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="main-form">
                        <div class="main-form-wrapper">
                            <h3 class="title mb-30 text-center">{{ __("Let's go") . '!' }}</h3>
                            <form id="authForm" action = "{{ route('user.signup_submit') }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label for="user_name" class="form-label font-sm">{{ __('Name') }}<span
                                            class="color-red">*</span></label>
                                    <input type="text" name="user_name" id="user_name" class="form-control"
                                        placeholder="{{ __('Enter Name') }}" value="{{ old('user_name') }}" required>
                                    @error('user_name')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-20">
                                    <label for="email" class="form-label font-sm">{{ __('Email Address') }}<span
                                            class="color-red">*</span></label>
                                    <input type="email" name="email_address" id="email" class="form-control"
                                        placeholder="{{ __('Enter Your Email Address') }}"
                                        value="{{ old('email_address') }}" required>
                                    @error('email_address')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-20">
                                    <label for="password" class="form-label font-sm"></label>{{ __('Password') }}<span
                                        class="color-red">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="{{ __('Enter password') }}" value="{{ old('password') }}"
                                            required>
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
                                        @error('password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-20">
                                    <label for="confirmPassword"
                                        class="form-label font-sm">{{ __('Confirm Password') }}<span
                                            class="color-red">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" id="confirmPassword"
                                            class="form-control" placeholder="{{ __('Confirm Password') }}"
                                            value="{{ old('password_confirmation') }}" required>
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
                                        @error('password_confirmation')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
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

                                <div class="text-center pt-10">
                                    <button class="btn btn-lg btn-primary w-100 radius-sm" type="submit"
                                        aria-label="Signup">{{ __('Signup') }}</button>
                                </div>

                            </form>
                        </div>
                        <div class="text-center mt-20">
                            <div class="link font-sm">
                                {!! formatPunctuation(__('Already a member'), $direction, true) !!}
                                <a href="{{ route('user.login') ?? '#' }}" target="_self"
                                    title="{{ __('Login Now') }}">{{ __('Login Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sign up End -->
@endsection
