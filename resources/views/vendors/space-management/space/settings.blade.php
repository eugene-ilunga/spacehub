@extends('vendors.layout')

@includeIf('vendors.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Settings') }}</h4>
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
                <a href="#">{{ __('Space Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Settings') }}</div>
                        </div>

                        <div class="col-lg-3">

                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" action="{{ route('vendor.space_management.space.update_space_setting') }}"
                                method="POST">
                                @csrf
                                @if ($hasFixedTimeslotRental)
                                    <div class="form-group">
                                        <label>{{ __('Fixed Timeslot Rental') . '*' }}</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="fixed_time_slot_rental" value="1"
                                                    class="selectgroup-input"
                                                    @if (@$space_settings->fixed_time_slot_rental === 1 || $hasFixedTimeslotRental) checked @endif>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="fixed_time_slot_rental" value="0"
                                                    class="selectgroup-input"
                                                    @if (@$space_settings->fixed_time_slot_rental === 0) checked @endif>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                        <p class="text-danger" id="err_fixed_time_slot_rental"></p>
                                    </div>
                                @else
                                    <p class="text-warning">
                                        {{ __('Fixed Timeslot Rental is not available in your package.') }}</p>
                                @endif
                                @if ($hasHourlyRental)
                                    <div class="form-group">
                                        <label>{{ __('Hourly Rental') . '*' }}</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="hourly_rental" value="1"
                                                    class="selectgroup-input"
                                                    @if (@$space_settings->hourly_rental === 1 || $hasHourlyRental) checked @endif>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="hourly_rental" value="0"
                                                    class="selectgroup-input"
                                                    @if (@$space_settings->hourly_rental === 0) checked @endif>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                        <p class="text-danger" id="err_hourly_rental"></p>
                                    </div>
                                @else
                                    <p class="text-warning">{{ __('Hourly Rental is not available in your package.') }}</p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
