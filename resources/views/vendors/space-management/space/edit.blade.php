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
                <a href="#">{{ @$space_title->title }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Space') }}</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Space') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}">
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
                                @if (count($sliderImages) > 0)
                                    <div id="reload-slider-div">
                                        <div class="row mt-2">
                                            <div class="col">
                                                <table class="table" id="img-table">
                                                    @foreach ($sliderImages as $key => $sliderImage)
                                                        <tr class="table-row" id="{{ 'slider-image-' . $key }}">
                                                            <td>
                                                                @if ($sliderImage)
                                                                    <img class="thumb-preview mdb_3523 width-150"
                                                                        src="{{ asset('assets/img/spaces/slider-images/' . $sliderImage) }}"
                                                                        alt="slider image">
                                                                @else
                                                                    <img src="{{ asset('assets/img/noimage.jpg') }}"
                                                                        alt="..." class="uploaded-img">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <i class="fa fa-times-circle"
                                                                    onclick="rmvStoredImg({{ $service->id }}, {{ $key }})"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <tr>
                                        <td colspan="2">{{ __('No slider images available') }}</td>
                                    </tr>
                                @endif
                                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                                    @csrf
                                    <div class="fallback"></div>
                                </form>
                                <p class="text-warning mt-3 mb-0">
                                    {{ '*' . __('Upload 860x610 pixel size image for best quality') . '.' }}</span>
                                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
                            </div>
                            @php
                                $isTrueSlotTime =
                                    isset($service) && !empty($service->space_type) && $service->space_type == 1
                                        ? '1'
                                        : '0';
                                $isTrueForHour =
                                    isset($service) && !empty($service->space_type) && $service->space_type == 2
                                        ? '1'
                                        : '0';
                            @endphp

                            <form id="serviceForm"
                                action="{{ isset($service->id) ? route('vendor.space_management.space.update', ['id' => $service->id]) : '#' }}"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="space_id" value="{{ $service->id }}">
                                <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                                <input type="hidden" id="editFixedTimeSlotRental" value="{{ $isTrueSlotTime }}">
                                <div id="slider-image-id"></div>

                                <div class="form-group">
                                    <div class="col-12 mb-2 pl-0">
                                        <label for="">{{ __('Thumbnail Image') }} <span
                                                class="text-danger">**</span></label>
                                    </div>

                                    <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                        <img src="{{ isset($service->thumbnail_image) ? asset('assets/img/spaces/thumbnail-images/' . $service->thumbnail_image) : asset('assets/img/noimage.jpg') }}"
                                            alt="..." class="cropped-thumbnail-image">
                                    </div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                                    <p class="text-warning">{{ __('Recommended Image Size') . ':' }} <strong dir="ltr">{{ '750 x 600 px' }}</strong></p>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Space Status') . '*' }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="space_status" value="1"
                                                        class="selectgroup-input"
                                                        {{ ($service->space_status ?? '') == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="space_status" value="0"
                                                        class="selectgroup-input"
                                                        {{ ($service->space_status ?? '') == 0 ? 'checked' : '' }}>
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
                                                        class="selectgroup-input"
                                                        {{ $service->booking_status == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="booking_status" value="0"
                                                        class="selectgroup-input"
                                                        {{ ($service->booking_status ?? '') == 0 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                            <p class="text-warning text-muted">
                                                {{ '*' .
                                                    __('Enabling this option will display a dropdown input below to select a form for get quote') .
                                                    ', ' .
                                                    __('Once enabled, the space rent will be treated as negotiable') .
                                                    '.' }}
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
                                                        class="selectgroup-input"
                                                        {{ ($service->book_a_tour ?? '') == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="book_a_tour" value="0"
                                                        class="selectgroup-input"
                                                        {{ ($service->book_a_tour ?? '') == 0 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                            <p class="text-warning text-muted">
                                                {{ '*' .
                                                    __('Enabling this option will display a dropdown input below to select a form for booking a tour') .
                                                    '.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if (isset($service) && !empty($service->space_type) && $service->space_type == 1)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Do you want to add rent per time slot') . '?' }} *</label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="use_slot_rent" value="1"
                                                            class="selectgroup-input" id="use_slot_rent_yes"
                                                            {{ $service->use_slot_rent == 1 ? 'checked' : '' }}>
                                                        <span class="selectgroup-button">{{ __('Yes') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="use_slot_rent" value="0"
                                                            class="selectgroup-input" id="use_slot_rent_no"
                                                            {{ $service->use_slot_rent == 0 ? 'checked' : '' }}>
                                                        <span class="selectgroup-button">{{ __('No') }}</span>
                                                    </label>
                                                </div>
                                                <p class="text-warning">
                                                    {{ '*' .
                                                        __('If Yes is selected, rent can be added for each individual time slot') .
                                                        '.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($service) && !empty($service->space_type) && $service->space_type === 3)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Similar Spaces') . '*'}}</label>
                                                <input type="number" class="form-control" name="similar_space_quantity"
                                                    value="{{ @$service->similar_space_quantity }}"
                                                    placeholder="{{ __('Enter number of similar spaces') }}">
                                                <p class=" text-warning form-text text-muted">
                                                    {{ __('How many spaces do you have of this type') . '?' }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($service) && !empty($service->space_type) && $service->space_type === 2)
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Preparation Time') . ' (' . __('in minutes') . ')' . '*' }}</label>
                                                <input type="number" class="form-control" name="prepare_time"
                                                    value={{ @$service->prepare_time }}
                                                    placeholder="{{ __('Enter Prepare Time') }}">
                                                <p class=" text-warning form-text text-muted">
                                                    {{ __('Preparation time is the duration (in minutes) required to prepare the space before it can be used') . '.' }}
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
                                                placeholder="{{ __('Enter Minimum Guests') }}"
                                                value="{{ $service->min_guest ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Maximum Guests') . '*' }}</label>
                                            <input type="number" class="form-control" name="max_guest"
                                                placeholder="{{ __('Enter Maximum Guests') }}"
                                                value="{{ $service->max_guest ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="{{ ($isTrueSlotTime && $service->use_slot_rent == 1) || $isTrueForHour ? 'col-lg-12' : 'col-lg-6' }}"
                                        id="spaceSizeCol">
                                        <div class="form-group ">
                                            <label>{{ __('Space Size') . '*' }}</label>
                                            <input type="number" class="form-control" name="space_size"
                                                placeholder="{{ __('Enter Space Size') }}"
                                                value="{{ $service->space_size ?? '' }}">
                                        </div>
                                    </div>
                                    @if (isset($service) && !empty($service->space_type) && $service->space_type === 1)
                                        <div class="col-lg-6" id="baseRentField"
                                            style="{{ $service->use_slot_rent == 0 ? 'display: none;' : 'display: block' }}">
                                            <div class="form-group">
                                                <label>{{ __('Space Rent') }} ({{ __('in') }}
                                                    {{ $websiteInfo->base_currency_text }})
                                                    *</label>
                                                <input type="number" class="form-control" name="space_rent"
                                                    placeholder="{{ __('Enter Space Rent') }}"
                                                    value="{{ $service->space_rent ?? '' }}">
                                            </div>
                                            <p class="text-warning">
                                                {{ '* ' . __('This rent will be applied to all time slots') . '. ' }}
                                            </p>
                                        </div>
                                    @endif
                                    @if (isset($service) && !empty($service->space_type) && $service->space_type === 3)
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Rent Per Day') }} ({{ __('in') }}
                                                    {{ $websiteInfo->base_currency_text }})*</label>
                                                <input type="text" class="form-control" name="price_per_day"
                                                    value="{{ @$service->price_per_day }}"
                                                    placeholder="{{ __('Enter price per day') }}">

                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (isset($service) && !empty($service->space_type) && $service->space_type === 2)
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Rent Per Hour') }} ({{ __('in') }}
                                                    {{ $websiteInfo->base_currency_text }})*</label>
                                                <input type="text" class="form-control" name="rent_per_hour"
                                                    value="{{ @$service->rent_per_hour }}"
                                                    placeholder="{{ __('Enter rent per hour') }}">

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Similar Spaces') . '*'}}</label>
                                                <input type="number" class="form-control" name="similar_space_quantity"
                                                    value="{{ @$service->similar_space_quantity }}"
                                                    placeholder="{{ __('Enter number of similar spaces') }}">
                                                <p class=" text-warning form-text text-muted">
                                                    {{ __('How many spaces do you have of this type') . '?' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>{{ __('Opening Time') . '*' }}</label>
                                                <input type="text" class="form-control time-24slot flatpickr-input ltr"
                                                    name="opening_time"
                                                    value="{{ @$service->opening_time ? \Carbon\Carbon::parse($service->opening_time)->format('H:i') : '' }}"
                                                    placeholder="{{ __('Enter opening time') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Closing Time') }}
                                                    *</label>
                                                <input type="text" class="form-control time-24slot flatpickr-input ltr"
                                                    name="closing_time"
                                                    value="{{ @$service->closing_time ? \Carbon\Carbon::parse($service->closing_time)->format('H:i') : '' }}"
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
                                                        class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : 'ltr' }}"
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
                                                                    placeholder="{{ __('Enter Space Title') }}"
                                                                    value="{{ $language->serviceData->title ?? '' }}">
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
                                                                    <option selected disabled>
                                                                        {{ __('Select a Category') }}
                                                                    </option>
                                                                    @if (isset($categories) && $categories->isNotEmpty())
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}"
                                                                                {{ $category->id === @$language->serviceData->space_category_id ? 'selected' : '' }}
                                                                                class="space-category">
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                @if ($categories->isEmpty())
                                                                    <p class="text-warning mt-2 mb-0">
                                                                        {{ __('Please create a Category first to proceed in') . ' ' . $language->code . ' ' . __('language') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        @php
                                                            $countries = \App\Models\Country::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();

                                                            $country = \App\Models\Country::where([
                                                                ['status', 1],
                                                                ['language_id', $language->id],
                                                                ['id', $language->serviceData->country_id],
                                                            ])->first();

                                                            $states = \App\Models\State::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])
                                                                ->when($language->serviceData, function ($query) use (
                                                                    $language,
                                                                ) {
                                                                    return $query->where(
                                                                        'country_id',
                                                                        $language->serviceData->country_id,
                                                                    );
                                                                })
                                                                ->get();
                                                                $state = \App\Models\State::where([
                                                                    ['status', 1],
                                                                    ['language_id', $language->id],
                                                                    ['id', $language->serviceData->state_id],
                                                                ])->first();

                                                            $cities = \App\Models\City::where([
                                                                ['language_id', $language->id],
                                                                ['status', 1],
                                                            ])->get();
                                                            $city = \App\Models\City::where([
                                                                ['status', 1],
                                                                ['language_id', $language->id],
                                                                ['id', $language->serviceData->city_id],
                                                            ])->first();

                                                        @endphp
                                                        <div
                                                            class="col-lg-4 countryDropdownContainer @if ($countries->isNotEmpty()) d-block @else d-none @endif ">
                                                            <input type="hidden" name="countryDropdownLangId"
                                                                value="{{ $language->id }}">
                                                            <input type="hidden" id="selectedCountryId" value="">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('Country') . '*' }}</label>

                                                                <select name="{{ $language->code }}_country_id"
                                                                    class="form-control countryDropdown select2 countryDropdownDataload"
                                                                    data-language-id="{{ $language->id }}">
                                                                    @if (!empty($country))
                                                                    <option selected value="{{ $country->id }}">{{ $country->name }}</option>
                                                                        
                                                                    @else
                                                                        <option selected disabled>{{ __('Select a Country') }}</option>
                                                                    @endif
  
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="col-lg-4 stateDropdownContainer @if ($states->isNotEmpty()) d-block @else d-none @endif">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('State')}} <span class="state-required-symbol d-none">*</span></label>
                                                                <select name="{{ $language->code }}_state_id"
                                                                    class="form-control stateDropdown select2 stateDropdownDataload"
                                                                    data-language-id="{{ $language->id }}">
                                                                    <option selected disabled>{{ __('Select a State') }}
                                                                    </option>
                                                                    @if (!empty($state))
                                                                        <option selected value="{{ $state->id  }}">{{ $state->name }}</option>
                                                                    @else
                                                                       <option selected disabled>{{ __('Select a State') }}</option> 
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="{{ $states->isEmpty() ? 'col-lg-8' : 'col-lg-4'  }} cityDropdownContainer">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label for="">{{ __('City') . '*' }}</label>
                                                                <select name="{{ $language->code }}_city_id"
                                                                    class="form-control cityDropdown select2 cityDropdownDataload" data-language-id="{{ $language->id }}">
                                                                    @if (!empty($city))
                                                                    <option selected value="{{ $city->id }}">{{ $city->name }}</option>                                                               
                                                                    @else                                                          <option selected disabled>{{ __('Select a City') }}
                                                                    </option>
                                                                    @endif

                                                                </select>
                                                                @if ($cities->isEmpty())
                                                                    <p class="text-warning mt-2 mb-0">
                                                                        {{ __('Please create a city first to proceed in') . ' ' . $language->code . ' ' . __('language') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                    @php
                                                        $subcategories = \App\Models\SpaceSubCategory::where([
                                                            ['language_id', $language->id],
                                                            [
                                                                'space_category_id',
                                                                $language->serviceData->space_category_id,
                                                            ],
                                                            ['status', 1],
                                                        ])->get();
                                                    @endphp

                                                    <div class="row">
                                                        <div class="col-lg-6 subcategoryDropdownContainer {{ $language->serviceData->sub_category_id == null ? 'd-none' : '' }}">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label
                                                                    for="">{{ __('Space Subcategory') }}</label>
                                                                <select name="{{ $language->code }}_subcategory_id"
                                                                    class="form-control subcategoryDropdown">
                                                                    <option selected disabled>
                                                                        {{ __('Select a Subcategory') }}</option>
                                                                    @foreach ($subcategories as $subcategory)
                                                                    <option value="{{ $subcategory->id }}" class="space-sub-category" {{ $language->serviceData && $subcategory->id === $language->serviceData->sub_category_id ? 'selected' : '' }} >{{ $subcategory->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="{{ $language->serviceData->sub_category_id == null ? 'col-lg-12' : 'col-lg-6' }} addressDiv">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Address') . '*' }}</label>
                                                                <input type="text" class="form-control search-address"
                                                                    id="autocomplete"
                                                                    name="{{ $language->code }}_address"
                                                                    value="{{ @$language->serviceData->address }}"
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
                                                                    <label>{{ __('Latitude') }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="latitude"
                                                                        placeholder="{{ __('Enter Latitude for map') }}"
                                                                        value="{{ $service->latitude ?? '' }}">
                                                                    <p class="text-warning">
                                                                        {{ __('The Latitude must be between -90 to 90. Ex:49.43453') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group ">
                                                                    <label>{{ __('Longitude')}}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="longitude"
                                                                        placeholder="{{ __('Enter Longitude for map') }}"
                                                                        value="{{ $service->longitude ?? '' }}">
                                                                </div>
                                                                <p class="text-warning">
                                                                    {{ __('The Longitude must be between -180 to 180. Ex:149.91553') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    {{-- end latitude and longtitude code for default language --}}

                                                    @php
                                                        $getQuoteFormClass =  $service->booking_status == 1 && $service->book_a_tour == 0 ? 'col-lg-12' : 'col-lg-6';
                                                        $getRequestFormClass =  $service->booking_status == 0 && $service->book_a_tour == 1 ? 'col-lg-12' : 'col-lg-6';
                                                    @endphp

                                                    <div class="row">
                                                        <div class="{{ $getQuoteFormClass }} getQuoteForm">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php

                                                                    $forms = App\Models\Form::where([
                                                                        ['seller_id', $seller->id],
                                                                        ['language_id', $language->id],
                                                                    ])->get();
                                                                @endphp

                                                                <label>{{ __('Get Quote Form') . '*' }}</label>
                                                                <select name="{{ $language->code }}_quote_form_id"
                                                                    class="form-control seller_form"
                                                                    data-lang_id="{{ $language->id }}"
                                                                    id="seller_form{{ $language->id }}">
                                                                    <option selected disabled>
                                                                        {{ __('Select a Form') }}
                                                                    </option>

                                                                    @foreach ($forms as $form)
                                                                        <option value="{{ $form->id }}"
                                                                            {{ @$language->serviceData && @$form->id === $language->serviceData->get_quote_form_id ? 'selected' : '' }}>
                                                                            {{ $form->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="mt-2 mb-0 text-warning">
                                                                    {{ '*' .
                                                                        __('Please select a quote form to customize your pricing') .' ' . __('and') .' ' .__('service offerings for your space booking needs') .
                                                                        '.' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="{{ $getRequestFormClass }} bookATourForm">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php

                                                                    $forms = App\Models\Form::where([
                                                                        ['seller_id', $seller->id],
                                                                        ['language_id', $language->id],
                                                                    ])->get();
                                                                @endphp

                                                                <label>{{ __('Tour Request Form') . '*' }}</label>
                                                                <select name="{{ $language->code }}_tour_form_id"
                                                                    class="form-control seller_form"
                                                                    data-lang_id="{{ $language->id }}"
                                                                    id="seller_form{{ $language->id }}">
                                                                    <option selected disabled>
                                                                        {{ __('Select a Form') }}
                                                                    </option>

                                                                    @foreach ($forms as $form)
                                                                        <option value="{{ $form->id }}"
                                                                            {{ @$language->serviceData && @$form->id === $language->serviceData->tour_request_form_id ? 'selected' : '' }}>
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

                                                    <div class="row" id="reload-space-amenity-div">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php
                                                                    $amenities = \App\Models\SpaceAmenity::query()
                                                                        ->where('language_id', '=', $language->id)
                                                                        ->orderBy('serial_number', 'asc')
                                                                        ->get();
                                                                @endphp

                                                                <label>{{ __('Space Amenities') . '*' }}</label>
                                                                <div>

                                                                    @foreach ($amenities as $amenity)
                                                                        <div class="d-inline mr-3">
                                                                            <input type="checkbox"
                                                                                class="mr-1 amenity-checkbox-without-downgraded
                                                                                   @if (count($amenityDowngrade) > 0) amenity-checkbox @endif"
                                                                                id="amenity_{{ $amenity->id }}"
                                                                                name="{{ $language->code ?? '' }}_amenities[]"
                                                                                value="{{ $amenity->id ?? '' }}"
                                                                                data-space-id="{{ $service->id }}"
                                                                                data-code="{{ $language->code }}"
                                                                                {{ isset($language->serviceData->amenities) &&
                                                                                !is_null($language->serviceData->amenities) &&
                                                                                in_array($amenity->id, json_decode($language->serviceData->amenities, true) ?? [])
                                                                                    ? 'checked'
                                                                                    : '' }}>
                                                                            <label
                                                                                for="amenity_{{ $amenity->id }}">{{ $amenity->name ?? '' }}</label>
                                                                        </div>
                                                                    @endforeach
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
                                                                    name="{{ $language->code }}_description" placeholder="{{ __('Enter Space Description') }}" data-height="300">{{ $language->serviceData->description ?? '' }}</textarea>
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
    {{-- thumbnail image cropper model --}}
    <p class="d-none" id="blob_image"></p>
    <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2>{{ __('Thumbnail') }} <span class="text-danger">**</span></h2>
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
                        <div class="thumb-preview cropper-preview" style="background: {{ $d_none }}">
                            <img src="{{ asset('assets/img/noimage.jpg') }}"
                                data-no_image="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                class="uploaded-thumbnail-img" id="image">
                        </div>
                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="thumbnail-input" name="thumbnail_image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail image cropper model end --}}

    {{-- Google map modal --}}
    <div class="modal fade" id="GoogleMapModal" tabindex="-1" role="dialog"
        aria-labelledby="GoogleMapModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="GoogleMapModalLongTitle">{{ __('Google Map') }}</h5>
                    <div>
                        <button type="button" class="btn btn-secondary btn-xs"
                            data-dismiss="modal">{{ __('Choose') }}</button>
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">X</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        'use strict'
        var address = "{{ @$address }}";
        var numberOfAmenity = {{ @$currentPackage->number_of_amenities_per_space }};
        var numberOfSliderImage = {{ @$currentPackage->number_of_slider_image_per_space }};
        var maxSliderImage = {{ @$maxSliderImage }};
        var sliderImageCount = {{ count($sliderImages) }};
        const imgUpUrl = "{{ route('vendor.space_management.space.upload_slider_image') }}";
        const imgRmvUrl = "{{ route('vendor.space_management.space.remove_slider_image') }}";
        var imgDetachUrl = "{{ route('vendor.space_management.detach_slider_image') }}";
        var deleteAmenityUrl = "{{ route('vendor.space_management.space.delete-amenity') }}";
        var stateByCountryUrl = "{{ route('vendor.location_management.space.states_by_country') }}";
        var cityByCountryOrStateUrl = "{{ route('vendor.location_management.space.cities_by_country_or_state') }}";
        var subcategoryUrl = "{{ route('vendor.space_management.get_subcategory') }}";
        var loadCountryUrl = "{{ route('admin.get_countries') }}";
        var loadStateUrl = "{{ route('admin.get_states') }}";
        var loadCityUrl = "{{ route('admin.get_cities') }}";
    </script>
    @if ($settings->google_map_api_key_status == 1)
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ $settings->google_map_api_key }}&libraries=places&callback=initMap"
            async defer></script>
        <script type="text/javascript" src="{{ asset('assets/admin/js/map-init.js') }}"></script>
    @endif

    <script type="text/javascript" src="{{ asset('assets/admin/js/slider-image.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper-init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/city-state-country.js') }}"></script>
@endsection
