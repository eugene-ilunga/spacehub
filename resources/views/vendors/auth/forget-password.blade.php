@extends('frontend.layout')
@php
    if (Session::has('currentLocaleCode')) {
        $locale = Session::get('currentLocaleCode');
    }
    app()->setLocale($locale); 
@endphp

@php
  $title = $pageHeading->seller_forget_password_page_title ?? __('Forget Password');
@endphp

	{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Forget Password Area Section ======-->
  <div class="user-area-section ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main-form">
            <div class="user-form">

            

              <form action="{{ route('vendor.forget.mail') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                  <label class="form-label font-sm">{{ __('Email Address') . '*' }}</label>
                  <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Enter registered email') }}">
                  @error('email')
                    <p class="text-danger mt-1">{{ $message }}</p>
                  @enderror
                </div>
  
                <div class="form-group">
                  <button type="submit" class="btn btn-lg btn-primary radius-sm">{{ __('Proceed') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Forget Password Area Section ======-->
@endsection
