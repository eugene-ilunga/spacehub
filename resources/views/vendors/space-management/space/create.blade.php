@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('vendor.dashboard') }}">
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
                <a
                    href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}">{{ __('Spaces') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Space') }}</a>
            </li>
        </ul>
    </div>

    @php
            $isTrueSlotTime =  $settings->fixed_time_slot_rental === 1 &&
        isset($space_type) &&
        !empty($space_type) &&
        $space_type == 'fixed_time_slot_rental'
            ? '1'
            : '0';
                $isTrueForHour =
        $settings->hourly_rental === 1 && isset($space_type) && !empty($space_type) && $space_type == 'hourly_rental'
            ? '1'
            : '0';
    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Add Space') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('vendor.space_management.space.select_space_type', ['language' => $defaultLang->code]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward mdb_12"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1 mdb_display_none" id="serviceErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <div class="mdb_353">
                                <label for=""><strong>{{ __('Slider Images') . '*' }}</strong></label>
                                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                                    @csrf
                                    <div class="fallback"></div>
                                </form>
                                <p class="text-warning mt-3 mb-0">
                                    {{ '*' . __('Allowed formats') . ': JPG, JPEG, PNG. ' . __('Maximum file size') . ': 5MB.' }}</p>
                                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
                            </div>

                            <form id="serviceForm" action="{{ route('vendor.space_management.space.store') }}"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="seller_id" value="{{ @$seller->id }}">
                                <input type="hidden" name="type" value="{{ $space_type }}">
                                <input type="hidden" id="fixedTimeSlotRental" value="{{ $settings->fixed_time_slot_rental === 1 && isset($space_type) && $space_type == 'fixed_time_slot_rental' ? '1' : '0' }}">

                                <div id="slider-image-id"></div>

                                <div class="form-group">
                                    <div class="col-12 mb-2 pl-0">
                                        <label for="image"><strong>{{ __('Thumbnail Image') }} <span
                                                    class="text-danger">**</span></strong></label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                            class="cropped-thumbnail-image">
                                    </div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                                    <p class="text-warning">{{ __('Recommended Image Size') . ':' }} <strong dir="ltr"> {{ '750 x 600 px' }}</strong></p>
                                    <p class="text-warning">{{ __('Allowed formats') . ': JPG, JPEG, PNG. ' . __('Maximum file size') . ': 5MB.' }}</p>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Space Status') . '*' }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="space_status" value="1"
                                                        class="selectgroup-input" checked>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="space_status" value="0"
                                                        class="selectgroup-input">
                                                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                                </label>
                                            </div>
                                            <p class="text-warning text-muted">
                                                *{{ __('Only active spaces will be visible and displayed on the frontend') . '.' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Get Quote') . '*' }}</label>
                                            <div class="selectgroup w-100" id="getQuoteStatus">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="booking_status" value="1"
                                                        class="selectgroup-input">
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="booking_status" value="0"
                                                        class="selectgroup-input" checked>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                            <p class="text-warning text-muted">
                                                {{ '*' . __('Enabling this option will display a dropdown input below to select a form for get quote') . ', ' . __('Once enabled, the space rent will be treated as negotiable') . '.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Book A Tour') . '*' }}</label>
                                            <div class="selectgroup w-100" id="bookATourStatus">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="book_a_tour" value="1"
                                                        class="selectgroup-input">
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="book_a_tour" value="0"
                                                        class="selectgroup-input" checked>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                            <p class="text-warning text-muted">
                                                {{ '*' . __('Enabling this option will display a dropdown input below to select a form for booking a tour') . '.' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($settings->hourly_rental === 1 && isset($space_type) && $space_type == 'hourly_rental')
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Preparation Time') . ' (' . __('in minutes') . ')' .  '*' }}</label>
                                                <input type="number" class="form-control" name="prepare_time"
                                                    placeholder="{{ __('Enter prepare time') }}">
                                                <p class=" text-warning form-text text-muted">
                                                    {{ __('Preparation time is the duration (in minutes) required to prepare the space before it can be used') . '.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($settings->fixed_time_slot_rental === 1 && isset($space_type) && $space_type == 'fixed_time_slot_rental')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Do you want to add rent per time slot') . '?' }} *</label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="use_slot_rent" value="1"
                                                            class="selectgroup-input" id="use_slot_rent_yes">
                                                        <span class="selectgroup-button">{{ __('Yes') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="use_slot_rent" value="0"
                                                            class="selectgroup-input" id="use_slot_rent_no" checked>
                                                        <span class="selectgroup-button">{{ __('No') }}</span>
                                                    </label>
                                                </div>
                                                <p class="text-warning text-muted">
                                                    {{ '*' . __('If Yes is selected, rent can be added for each individual time slot') . '.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($settings->multi_day_rental === 1 && isset($space_type) && $space_type == 'multi_day_rental')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Similar Spaces') . '*'}}</label>
                                                <input type="number" class="form-control" name="similar_space_quantity"
                                                    placeholder="{{ __('Enter number of similar spaces') }}">
                                                <p class=" text-warning form-text text-muted">
                                                    {{ __('How many spaces do you have of this type') . '?' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group ">
                                            <label>{{ __('Minimum Guests') . '*' }}</label>
                                            <input type="number" class="form-control" name="min_guest"
                                                placeholder="{{ __('Enter Minimum Guest') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Maximum Guests') . '*' }}</label>
                                            <input type="number" class="form-control" name="max_guest"
                                                placeholder="{{ __('Enter Maximum Guest') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="{{ $isTrueSlotTime || $isTrueForHour ? 'col-lg-12' : 'col-lg-6' }}" id="spaceSizeCol">
                                        <div class="form-group ">
                                            <label>{{ __('Space Size') }} ({{ __('in') }}
                                                {{ $websiteInfo->space_units }})*</label>
                                            <input type="number" class="form-control" name="space_size"
                                                placeholder="{{ __('Enter Space Size') }}">
                                        </div>
                                    </div>

                                    @if ($settings->multi_day_rental === 1 && isset($space_type) && $space_type == 'multi_day_rental')
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Rent Per Day') }} ({{ __('in') }}
                                                    {{ $websiteInfo->base_currency_text }})*</label>
                                                <input type="text" class="form-control" name="price_per_day"
                                                    placeholder="{{ __('Enter price per day') }}">

                                            </div>
                                        </div>
                                    @endif

                                    @if (
                                        $settings->fixed_time_slot_rental === 1 &&
                                            isset($space_type) &&
                                            !empty($space_type) &&
                                            $space_type == 'fixed_time_slot_rental')
                                        <div class="col-lg-6" id="baseRentField">
                                            <div class="form-group">
                                                <label>{{ __('Space Rent') }} ({{ __('in') }}
                                                    {{ $websiteInfo->base_currency_text }})*
                                                </label>
                                                <input type="number" class="form-control" name="space_rent"
                                                    placeholder="{{ __('Enter Space Rent') }}">
                                            </div>
                                            <p class="text-warning">
                                                {{ '*' .__('This rent will be applied to all time slots') . '. ' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                @if ($settings->hourly_rental === 1 && isset($space_type) && $space_type == 'hourly_rental')
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Rent Per Hour') }} ({{ __('in') }}
                                                    {{ optional($websiteInfo)->base_currency_text ?? 'Currency' }})*</label>
                                                <input type="text" class="form-control" name="rent_per_hour"
                                                    placeholder="{{ __('Enter rent per hour') }}">

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Similar Spaces') . '*' }}</label>
                                                <input type="number" class="form-control" name="similar_space_quantity"
                                                    placeholder="{{ __('Enter number of similar spaces') }}">
                                                <p class="text-warning form-text text-muted">
                                                    {{ __('How many spaces do you have of this type') . '?' }}
                                                </p>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Opening Time') . '*' }}</label>
                                                <input type="text" class="form-control time-24slot flatpickr-input ltr" name="opening_time"
                                                    placeholder="{{ __('Enter opening time') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Closing Time') }}
                                                    *</label>
                                                <input type="text" class="form-control time-24slot flatpickr-input ltr" name="closing_time"
                                                    placeholder="{{ __('Enter closing time') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        <div class="version {{ $language->direction == 1 ? 'rtl text-right' : 'ltr' }}">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button"
                                                        class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : ' ltr' }}"
                                                        data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . ' ' . __('Language') }}
                                                        {{ $language->is_default == 1 ? __('(Default)') : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Title') . '*' }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_title"
                                                                    placeholder="{{ __('Enter Space Title') }}">
                                                            </div>
                                                        </div>

                                                        @php
                                                            $categories = \App\Models\SpaceCategory::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();

                                                        @endphp

                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label
                                                                    for="">{{ __('Space Category') . '*' }}</label>
                                                                <select name="{{ $language->code }}_space_category_id"
                                                                    class="form-control spaceCategoryDropdown">
                                                                    <option selected disabled>{{ __('Select a Category') }}
                                                                    </option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            class="space-category">
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        @php
                                                            $countries = \App\Models\Country::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();
                                                            $states = \App\Models\State::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();
                                                            $cities = \App\Models\City::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();

                                                        @endphp
                                                        <div
                                                            class="col-lg-4 countryDropdownContainer @if (isset($countries) && $countries->isNotEmpty()) d-block @else d-none @endif ">
                                                            <input type="hidden" name="countryDropdownLangId"
                                                                value="{{ $language->id }}">
                                                            <input type="hidden" id="selectedCountryId" value="">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('Country') . '*' }}</label>

                                                                <select name="{{ $language->code }}_country_id"
                                                                    class="form-control countryDropdown select2 countryDropdownDataload"
                                                                    data-language-id="{{ $language->id }}">

                                                                    <option selected disabled>{{ __('Select a Country') }}
                                                                    </option>
                                                                    @foreach ($countries as $country)
                                                                        <option value="{{ $country->id }}"
                                                                            class="">
                                                                            {{ @$country->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div
                                                            class="col-lg-4 stateDropdownContainer @if (isset($states) && $states->isNotEmpty()) d-block @else d-none @endif">

                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('State')}} <span class="state-required-symbol d-none">*</span></label>
                                                                <select name="{{ $language->code }}_state_id"
                                                                    class="form-control stateDropdown select2 stateDropdownDataload"
                                                                    data-language-id="{{ $language->id }}">
                                                                    <option selected disabled>{{ __('Select a State') }}
                                                                    </option>
                                                                    @if (isset($states) && $states->isNotEmpty())
                                                                        @foreach ($states as $state)
                                                                            <option value="{{ $state->id }}">
                                                                                {{ @$state->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">
                                                                            {{ __('No states available') }}</option>
                                                                    @endif
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 cityDropdownContainer">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('City') . '*' }}</label>
                                                                <select name="{{ $language->code }}_city_id"
                                                                    class="form-control cityDropdown select2 cityDropdownDataload" data-language-id="{{ $language->id }}">
                                                                    <option selected disabled>{{ __('Select a City') }}
                                                                    </option>
                                                                    @if (isset($cities) && $cities->isNotEmpty())
                                                                        @foreach ($cities as $city)
                                                                            <option value="{{ @$city->id }}"
                                                                                class="">
                                                                                {{ @$city->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @else
                                                                    @endif
                                                                </select>
                                                                @if ($cities->isEmpty())
                                                                <p class="text-warning mt-2 mb-0">
                                                                    {{  __('Please create a city first to proceed in') . ' ' . $language->code . ' ' . __('language') . '.'  }}
                                                                </p>
                                                                    
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 subcategoryDropdownContainer d-none">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label
                                                                    for="">{{ __('Space Subcategory') }}</label>
                                                                <select name="{{ $language->code }}_subcategory_id"
                                                                    class="form-control subcategoryDropdown">
                                                                    <option selected disabled>
                                                                        {{ __('Select a Subcategory') }}
                                                                    </option>

                                                                    <option value="" class="space-sub-category">
                                                                    </option>

                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 addressDiv">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Address') . '*' }}</label>
                                                                <input type="text" class="form-control search-address"
                                                                    name="{{ $language->code }}_address"
                                                                    data-is_default_lang="{{ @$language->is_default }}"
                                                                    placeholder="{{ __('Enter Address') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                {{-- start latitude and longtitude code for default language --}}
                                                    @if ($language->is_default == 1)
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group ">
                                                                    <label>{{ __('Latitude')  }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="latitude" autocomplete="off"
                                                                        placeholder="{{ __('Enter Latitude for map') }}">
                                                                    <p class="text-warning">
                                                                        {{ __('The Latitude must be between -90 to 90. Ex:49.43453') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group ">
                                                                    <label>{{ __('Longitude')}}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="longitude" autocomplete="off"
                                                                        placeholder="{{ __('Enter Longitude for map') }}">
                                                                </div>
                                                                <p class="text-warning">
                                                                    {{ __('The Longitude must be between -180 to 180. Ex:149.91553') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    {{-- end latitude and longtitude code for default language --}}

                                                    <div class="row">
                                                        <div class="col-lg-6 getQuoteForm">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php
                                                                    $forms = App\Models\Form::where([
                                                                        ['seller_id', $seller->id],
                                                                        ['language_id', $language->id],
                                                                        ['status', 1],
                                                                    ])->get();
                                                                @endphp

                                                                <label>{{ __('Get Quote Form') . '*' }}</label>
                                                                <select name="{{ $language->code }}_quote_form_id"
                                                                    class="form-control seller_form"
                                                                    data-lang_id="{{ $language->id }}"
                                                                    id="seller_form{{ $language->id }}">
                                                                    <option selected disabled>{{ __('Select a Form') }}
                                                                    </option>

                                                                    @foreach ($forms as $form)
                                                                        <option value="{{ $form->id }}">
                                                                            {{ $form->name }}</option>
                                                                    @endforeach
                                                                </select>

                                                                <p class="mt-2 mb-0 text-warning">
                                                                    {{ '*' .__('Please select a quote form to customize your pricing') .' ' . __('and') .' ' .__('service offerings for your space booking needs') . '.' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 bookATourForm">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php

                                                                    $forms = App\Models\Form::where([
                                                                        ['seller_id', $seller->id],
                                                                        ['language_id', $language->id],
                                                                        ['status', 1],
                                                                    ])->get();
                                                                @endphp

                                                                <label>{{ __('Tour Request Form') . '*' }}</label>
                                                                <select name="{{ $language->code }}_tour_form_id"
                                                                    class="form-control seller_form"
                                                                    data-lang_id="{{ $language->id }}"
                                                                    id="seller_form{{ $language->id }}">
                                                                    <option selected disabled>{{ __('Select a Form') }}
                                                                    </option>

                                                                    @foreach ($forms as $form)
                                                                        <option value="{{ $form->id }}">
                                                                            {{ $form->name }}</option>
                                                                    @endforeach
                                                                </select>

                                                                <p class="mt-2 mb-0 text-warning">
                                                                    {{ '*' .
                                                                        __('Select a form to schedule your visit') .
                                                                        ', ' .
                                                                        __('including office hours and available tour dates') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php
                                                                    $amenities = \App\Models\SpaceAmenity::where(
                                                                        'language_id',
                                                                        $language->id,
                                                                    )
                                                                        ->orderBy('serial_number', 'asc')
                                                                        ->get();
                                                                @endphp
                                                                <label>{{ __('Space Amenities') . '*' }}</label>
                                                                <div>
                                                                    @if (isset($amenities) && $amenities->isNotEmpty())
                                                                        @foreach ($amenities as $amenity)
                                                                            <div class="d-inline mr-3">
                                                                                <input type="checkbox"
                                                                                    class="mr-1 amenity-checkbox-without-downgraded"
                                                                                    id="amenity_{{ $amenity->id }}"
                                                                                    name="{{ $language->code ?? '' }}_amenities[]"
                                                                                    data-code="{{ $language->code }}"
                                                                                    value="{{ $amenity->id ?? '' }}">
                                                                                <label
                                                                                    for="amenity_{{ $amenity->id }}">{{ $amenity->name ?? '' }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Description') . '*' }}</label>
                                                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                                                    name="{{ $language->code }}_description" placeholder="{{ __('Enter Space Description') }}" data-height="300"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Keywords') }}</label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keywords"
                                                                    placeholder="{{ __('Enter Meta Keywords') }}"
                                                                    data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Description') }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp

                                                            @foreach ($languages as $language)
                                                                @continue($language->id == $currLang->id)

                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="serviceForm" class="btn btn-success">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail image cropper modal --}}
    <p class="d-none" id="blob_image"></p>
    <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2>{{ __('Thumbnail') }}*</h2>
                    <button role="button" class="close btn btn-secondary mr-2 destroy-cropper d-none text-white"
                        data-dismiss="modal" aria-label="Close">
                        {{ __('Crop') }}
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center">
                        @php
                            $d_none = 'none';
                        @endphp
                        <div class="thumb-preview cropper-preview " style="background: {{ $d_none }}">
                            <img src="{{ asset('assets/img/noimage.jpg') }}"
                                data-no_image="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                class="uploaded-thumbnail-img" id="image">
                        </div>
                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm  fw-bold upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="thumbnail-input" name="thumbnail_image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail image cropper modal end --}}
@endsection

@section('script')
    @if ($settings->google_map_api_key_status == 1)
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ $settings->google_map_api_key }}&libraries=places&callback=initMap"
            async defer></script>
        <script type="text/javascript" src="{{ asset('assets/admin/js/map-init.js') }}"></script>
    @endif
    <script>
        'use strict'
        var maxSliderImage = {{ @$maxSliderImage }};
        var numberOfAmenity = {{ @$currentPackage->number_of_amenities_per_space }};
        const imgUpUrl = "{{ route('vendor.space_management.space.upload_slider_image') }}";
        const imgRmvUrl = "{{ route('vendor.space_management.space.remove_slider_image') }}";
        var stateByCountryUrl = "{{ route('vendor.location_management.space.states_by_country') }}";
        var cityByCountryOrStateUrl = "{{ route('vendor.location_management.space.cities_by_country_or_state') }}";
        var subcategoryUrl = "{{ route('vendor.space_management.get_subcategory') }}";
        var loadCountryUrl = "{{ route('vendor.get_countries') }}";
        var loadStateUrl = "{{ route('vendor.get_states') }}";
        var loadCityUrl = "{{ route('vendor.get_cities') }}";
    </script>


    <script type="text/javascript" src="{{ asset('assets/admin/js/slider-image.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper-init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/city-state-country.js') }}"></script>
@endsection
