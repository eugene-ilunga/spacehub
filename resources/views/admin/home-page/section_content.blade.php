@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Images & Texts') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Pages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Images & Texts') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.home_page.section_content_update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update General Settings') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @include('admin.home-page.category_feature_title')
                    @include('admin.home-page.space_banner_section')
                </div>
                <div class="row">
                    @include('admin.home-page.work-process-section.title_image')
                    @include('admin.home-page.testimonial-section.title_image')
                    @include('admin.home-page.video-banner.index')
                </div>

                <div class="row">
                    @include('admin.home-page.hero-section.static.index')
                    @include('admin.home-page.popular_city_section')
                </div>
                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
