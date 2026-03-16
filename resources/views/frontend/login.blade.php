@extends('frontend.layout')

@php
    $title = $pageHeading->login_page_title ?? __('Login');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!-- Breadcrumb end -->

    <!-- Authentication Start -->
    <div class="authentication-area bg-light ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                
                    <div class="main-form">
                        @if ($basicInfo->guest_checkout_status == 1 && $pageType != null)
                                <div class="text-center mb-3">
                                    @if ($pageType == 'space')
                                        <a href="{{ route('frontend.booking.checkout.index', ['guest' => 1]) }}"
                                            class="btn btn-md btn-outline w-100 bg-white radius-sm">
                                            {{ __('Continue as Guest') }}
                                        </a>
                                    @elseif ($pageType == 'product')
                                        <a href="{{ route('shop.checkout', ['guest' => 1]) }}"
                                            class="btn btn-md btn-outline w-100 bg-white radius-sm">
                                            {{ __('Continue as Guest') }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        <div class="main-form-wrapper">

                            <h3 class="title mb-30 text-center">{{ __('Welcome back') . '!' }}</h3>
                            <form id="authForm" action="{{ route('user.login_submit') }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label for="userName" class="form-label font-sm">{{ __('Username') }}<span
                                            class="color-red">*</span></label>
                                    <input type="text" name="username" id="userName" class="form-control radius-sm"
                                        placeholder="{{ __('Enter username') }}" required>
                                    @error('username')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-20">
                                    <label for="password" class="form-label font-sm">{{ __('Password') }}<span
                                            class="color-red">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" class="form-control radius-sm"
                                            placeholder="{{ __('Enter password') }}" required>
                                        <span class="show-password-field">
                                            <i class="show-icon"></i>
                                        </span>
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
                                </div>
                                <div class="text-center pt-10">
                                    <button class="btn btn-lg btn-primary w-100 radius-sm" type="submit"
                                        aria-label="Login">{{ __('Login') }}</button>
                                </div>
                            </form>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap gap-2 mt-20">
                            <div class="link font-sm">
                                <a href="{{ route('user.forget_password' ?? '#') }}" title="{{ __('Forgot Password') }}">
                                    {!! formatPunctuation(__('Forgot Password'), $direction, true) !!}
                                </a>
                            </div>
                            <div class="link font-sm">
                                {!! formatPunctuation(__("Don\'t have an account"), $direction, true) !!}
                                <a href="{{ route('user.signup') ?? '#' }}" title="{{ __('Go Signup') }}"
                                    target="_self">{{ __('Click Here') }}</a>
                                {{ __('to Signup') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Authentication End -->
@endsection
