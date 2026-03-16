@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

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
                            <form id="ajaxForm" action="{{ route('admin.space-management.space.settings.update') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Tax') . ' (%)' . '*' }}</label>
                                    <input type="number" name="tax" step="0.1" class="form-control"
                                        value="{{ @$space_settings->tax }}">
                                    <p class="text-danger" id="err_tax"></p>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Space Units') . '*' }} </label>
                                    <input type="text" name="space_units" class="form-control"
                                        value="{{ @$space_settings->space_units }}">
                                    <p class="text-danger" id="err_space_units"></p>

                                </div>
                                <div class="form-group">
                                    <label>{{ __('Fixed Timeslot Rental') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="fixed_time_slot_rental" value="1"
                                                class="selectgroup-input" @if ($space_settings->fixed_time_slot_rental == 1) checked @endif>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="fixed_time_slot_rental" value="0"
                                                class="selectgroup-input" @if ($space_settings->fixed_time_slot_rental == 0) checked @endif>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                    <p class="text-danger" id="err_fixed_time_slot_rental"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Hourly Rental') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="hourly_rental" value="1"
                                                class="selectgroup-input" @if ($space_settings->hourly_rental == 1) checked @endif>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="hourly_rental" value="0"
                                                class="selectgroup-input" @if ($space_settings->hourly_rental == 0) checked @endif>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                    <p class="text-danger" id="err_hourly_rental"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Multi-Day') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="multi_day_rental" value="1"
                                                class="selectgroup-input" @if ($space_settings->multi_day_rental == 1) checked @endif>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="multi_day_rental" value="0"
                                                class="selectgroup-input" @if ($space_settings->multi_day_rental == 0) checked @endif>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                    <p class="text-danger" id="err_multi_day_rental"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="submitBtn"
                                    class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
