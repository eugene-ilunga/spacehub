@extends('admin.layout')

@php
use App\Models\Language;
$selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang->language) && $selLang->language->rtl == 1)
@section('styles')
<style>
    form input,
    form textarea,
    form select {
        direction: rtl;
    }

    form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Edit Package') }}</h4>
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
            <a href="#">{{ __('Subscriptions Management') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Packages Management') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.package.index', ['language' => $defaultLang->code]) }}">{{ __('Packages') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Edit') }}</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title d-inline-block">{{ __('Edit Package') }}</div>
                <a class="btn btn-info btn-sm float-right d-inline-block"
                    href="{{ route('admin.package.index') }}?language={{ $defaultLang->code }}">
                    <span class="btn-label">
                        <i class="fas fa-backward"></i>
                    </span>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body pt-5 pb-5">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                            action="{{ route('admin.package.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <div class="form-group">
                                <label for="title">{{ __('Package Title') }}*</label>
                                <input id="title" type="text" class="form-control" name="title"
                                    placeholder="{{ __('Enter Package title') }}" value="{{ __($package->title) }}">
                                <p id="err_title" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="price">{{ __('Price') }}
                                    ({{ $settings->base_currency_text }})*</label>
                                <input id="price" type="number" class="form-control" name="price"
                                    placeholder="{{ __('Enter Package price') }}" value="{{ @$package->price }}">
                                <p class="text-warning">
                                    <small>{{ __('If price is 0') . ',' . ' ' . __('than it will appear as free') }}</small>
                                </p>
                                <p id="err_price" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Icon') }}*</label>
                                <div class="btn-group d-block">
                                    <button type="button" class="btn btn-primary iconpicker-component">
                                        <i class="{{ $package->icon }}"></i>
                                    </button>
                                    <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="{{ $package->icon }}" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu"></div>
                                </div>
                                <input type="hidden" id="inputIcon" name="icon">
                                <p id="err_icon" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="term">{{ __('Package Term') }}*</label>
                                <select id="term" name="term" class="form-control" required>
                                    <option value="" selected disabled>{{ __('Choose a Package term') }}</option>
                                    <option value="monthly" {{ $package->term == 'monthly' ? 'selected' : '' }}>
                                        {{ __('Monthly') }}</option>
                                    <option value="yearly" {{ $package->term == 'yearly' ? 'selected' : '' }}>
                                        {{ __('Yearly') }}</option>
                                    <option value="lifetime" {{ $package->term == 'lifetime' ? 'selected' : '' }}>
                                        {{ __('Lifetime') }}</option>
                                </select>
                                <p id="err_term" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('Package Features') }}</label>
                                <div class="selectgroup selectgroup-pills">
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Support Tickets"
                                            class="selectgroup-input" {{ !empty($package->package_feature) &&
                                        in_array('Support Tickets', json_decode($package->package_feature, true))
                                        ? 'checked'
                                        : '' }}>
                                        <span class="selectgroup-button">{{ __('Support Tickets') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Add Booking"
                                            class="selectgroup-input" {{ !empty($package->package_feature) &&
                                        in_array('Add Booking', json_decode($package->package_feature, true))
                                        ? 'checked'
                                        : '' }}>
                                        <span class="selectgroup-button">{{ __('Manual Booking Entry from Dashboard') }}</span>
                                    </label>

                                    @if (isset($settings) && $settings->fixed_time_slot_rental == 1)
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Fixed Timeslot Rental"
                                            class="selectgroup-input" {{ !empty($package->package_feature) &&
                                        in_array('Fixed Timeslot Rental', json_decode($package->package_feature, true))
                                        ? 'checked'
                                        : '' }}>
                                        <span class="selectgroup-button">{{ __('Fixed Timeslot Rental') }}</span>
                                    </label>
                                    @endif
                                    @if (isset($settings) && $settings->hourly_rental == 1)
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Hourly Rental"
                                            class="selectgroup-input" {{ !empty($package->package_feature) &&
                                        in_array('Hourly Rental', json_decode($package->package_feature, true))
                                        ? 'checked'
                                        : '' }}>
                                        <span class="selectgroup-button">{{ __('Hourly Rental') }}</span>
                                    </label>
                                    @endif
                                    @if (isset($settings) && $settings->multi_day_rental == 1)
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Multi Day Rental"
                                            class="selectgroup-input" {{ !empty($package->package_feature) &&
                                        in_array('Multi Day Rental', json_decode($package->package_feature, true))
                                        ? 'checked'
                                        : '' }}>
                                        <span class="selectgroup-button">{{ __('Multi Day Rental') }}</span>
                                    </label>
                                    @endif
                                </div>
                                <p id="err_features" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Number of Spaces') }} *</label>
                                <input type="number" class="form-control" name="number_of_space"
                                    value="{{ $package->number_of_space }}"
                                    placeholder="{{ __('Enter Number of spaces') }}">
                                <p class="text-warning">{{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}
                                </p>
                                <p id="err_number_of_space" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Number of Services per Space') }} *</label>
                                <input type="number" class="form-control" name="number_of_service_per_space"
                                    value="{{ $package->number_of_service_per_space }}"
                                    placeholder="{{ __('Enter Number of Services per Space') }}">
                                <p class="text-warning">{{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}
                                </p>
                                <p id="err_number_of_service_per_space" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Number of Variants per Service') }} *</label>
                                <input type="number" class="form-control" name="number_of_option_per_service"
                                    value="{{ $package->number_of_option_per_service }}"
                                    placeholder="{{ __('Enter Number of Variants') }}">
                                <p class="text-warning">{{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}
                                </p>
                                <p id="err_number_of_option_per_service" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Number of Slider images per Space') }} *</label>
                                <input type="number" class="form-control" name="number_of_slider_image_per_space"
                                    value="{{ $package->number_of_slider_image_per_space }}"
                                    placeholder="{{ __('Enter Number of Slider images per Space') }}">
                                <p class="text-warning">{{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}
                                </p>
                                <p id="err_number_of_slider_image_per_space" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Number of Amenities') }} *</label>
                                <input type="number" class="form-control" name="number_of_amenities_per_space"
                                    value="{{ $package->number_of_amenities_per_space }}"
                                    placeholder="{{ __('Enter Number of Amenities per Space') }}">
                                <p class="text-warning">{{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}
                                </p>
                                <p id="err_number_of_amenities_per_space" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}*</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="" selected disabled>{{ __('Select a Status') }}</option>
                                    <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}</option>
                                    <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>
                                        {{ __('Deactive') }}</option>
                                </select>
                                <p id="err_status" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Popular') }}*</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="recommended" value="1" class="selectgroup-input" {{
                                            $package->recommended == 1 ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('Yes') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="recommended" value="0" class="selectgroup-input" {{
                                            $package->recommended == 0 ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('No') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Custom Features') }}</label>
                                <textarea class="form-control" name="custom_features" rows="5"
                                    placeholder="{{ __('Enter Custom Features') }}">{{ $package->custom_features }}</textarea>
                                <p class="text-warning">
                                    <small>{{ __('Enter new line to seperate features') }}</small>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form">
                    <div class="form-group from-show-notify row">
                        <div class="col-12 text-center">
                            <button type="submit" id="submitBtn" form="packageEditForm" class="btn btn-success">{{
                                __('Update') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/package.js') }}"></script>
@endsection
