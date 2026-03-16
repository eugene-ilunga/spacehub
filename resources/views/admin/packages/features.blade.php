@extends('admin.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Package Features') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{route('admin.dashboard', ['language' => $defaultLang->code]) }}">
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
                <a href="#">{{ __('Packages Management') }} </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Package Features') }} </a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Package Features') }} </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxEditForm" class="" action="{{ route('admin.package.features.update') }}"
                                method="post">
                                {{ csrf_field() }}
                                <div class="alert alert-warning">
                                    {{ __('Only these selected features will be visible in frontend Pricing Section') }}
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('Package Features') }} </label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="spaces"
                                                class="selectgroup-input" @if (is_array($features) && in_array('spaces', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Spaces') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="slider_images_per_space"
                                                class="selectgroup-input" @if (is_array($features) && in_array('slider_images_per_space', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Slider Images Per Space') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="services_per_space"
                                                class="selectgroup-input" @if (is_array($features) && in_array('services_per_space', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Services Per Space') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="variants_per_service"
                                                class="selectgroup-input" @if (is_array($features) && in_array('variants_per_service', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Variants Per Service') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="amenities_per_space"
                                                class="selectgroup-input" @if (is_array($features) && in_array('amenities_per_space', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Amenities Per Space') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="support_tickets"
                                                class="selectgroup-input" @if (is_array($features) && in_array('support_tickets', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Support Tickets') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="add_booking"
                                                class="selectgroup-input" @if (is_array($features) && in_array('add_booking', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Manual Booking Entry from Dashboard') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="fixed_timeslot_rental"
                                                class="selectgroup-input" @if (is_array($features) && in_array('fixed_timeslot_rental', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Fixed Timeslot Rental') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="hourly_rental"
                                                class="selectgroup-input" @if (is_array($features) && in_array('hourly_rental', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Hourly Rental') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="multi_day_rental"
                                                class="selectgroup-input" @if (is_array($features) && in_array('multi_day_rental', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Multi-day Rental') }} </span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="updateBtn" class="btn btn-success">{{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
