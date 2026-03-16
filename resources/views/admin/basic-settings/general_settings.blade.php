@extends('admin.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('General Settings') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Settings') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('General Settings') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.basic_settings.general_settings.update') }}" method="post"
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
                @include('admin.basic-settings.favicon')
                @include('admin.basic-settings.logo')
            </div>
            <div class="row">
                @include('admin.basic-settings.preloader.preloader_status')
                 @include('admin.basic-settings.preloader.index')
            </div>
            <div class="row ">
                @include('admin.basic-settings.timezone')
                @include('admin.basic-settings.time-format')
            </div>

            <div class="row">

                @include('admin.basic-settings.currency')
            
            </div>

            <div class="row">
                @include('admin.basic-settings.theme-&-home')
            </div>
            <div class="row">
                @include('admin.basic-settings.information')
                @include('admin.basic-settings.appearance')

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
