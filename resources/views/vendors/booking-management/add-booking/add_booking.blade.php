@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Booking') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('vendor.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Bookings & Requests') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Booking Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Booking') }}</a>
            </li>
        </ul>
    </div>
    @php
        $position = $spaceUnit->base_currency_symbol_position;
        $symbol = $spaceUnit->base_currency_symbol;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card widget_card">
                <div class="card-header mb-20">
                    <div class="card-title d-inline-block">{{ __('Add Booking') }}</div>
                </div>
                <div class="col-xl-12">
                    <aside class="widget-area" data-aos="fade-up">
                        <div class="widget widget-booking ">
                            <form action="{{ route('vendor.confirm.booking', ['slug' => request()->slug]) }}" method="POST"
                                id="selectedItemsForm">
                                @csrf
                                <div class="widget_from_wrapper">
                                    <div class="widget_from_left">
                                        <input type="hidden" name="space_id" value="{{ $spaceContent->space_id }}">
                                        <input type="hidden" name="seller_id" value="{{ $spaceContent->seller_id }}">
                                        <input type="hidden" id="totalAmountHidden" name="total_amount_for_space">
                                        <input type="hidden" id="vatAmountHidden" name="vat_amount_for_space">
                                        <input type="hidden" id="subtotalAmountHidden" name="sub_total_for_space">
                                        <input id="prepareTimeId" type="hidden" name="prepare_time"
                                            value="{{ @$spaceContent->prepare_time }}">

                                        <div class="eventdate_numberOfGuest mb-20">

                                            @if (isset($space) && optional($space)->space_type == 3)
                                                <div class="form-group p-0">
                                                    <label for="eventDate">{{ __('Date') . '*' }}</label>
                                                    <input type="text"
                                                        class="form-control text-only checkInDate spaceBookingDate"
                                                        id="eventDate" name="eventDate" autocomplete="off"
                                                        placeholder="{{ __('Date Format') }}"
                                                        data-space_id="{{ $spaceContent->space_id }}"
                                                        data-seller_id="{{ $spaceContent->seller_id }}" />
                                                    <p id="bookingDateError" class="text-danger mt-2 mb-0"></p>
                                                    @error('bookingDate')
                                                        <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <p id="bookingMessage" class="mt-2 mb-0 text-danger em  p-20"
                                                    style="display:none;">
                                                </p>
                                            @else
                                                <div class="form-group date-form p-0">
                                                    <label for="eventDate">{{ __('Date') . '*' }}</label>
                                                    <input type="text"
                                                        class="form-control text-only checkInDate spaceBookingDate"
                                                        id="eventDate" name="eventDate" autocomplete="off"
                                                        placeholder="{{ __('Date Format') }}"
                                                        data-space_id="{{ $spaceContent->space_id }}"
                                                        data-seller_id="{{ $spaceContent->seller_id }}" />
                                                    <p id="bookingDateError" class="text-danger mt-2 mb-0"></p>
                                                    @error('bookingDate')
                                                        <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @if (isset($space) && optional($space)->space_type == 1)
                                                    <div class="form-group date-form p-0 ">
                                                        <label for="eventTime">{{ __('Time') . '*' }}</label>
                                                        <select class="form-control mdb_343 select2" name="eventTime"
                                                            id="eventTime" dir="ltr">
                                                            <option value="" selected disabled>
                                                                {{ __('Select Time Slot') }}
                                                            </option>
                                                        </select>
                                                        <div id="timeSlotId"></div>
                                                        <p id="timeSlotIdError" class="text-danger mt-2 mb-0"></p>
                                                        @error('timeSlotId')
                                                            <p class="mt-1 mb-0 text-danger em">{{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>
                                                @elseif(isset($space) && optional($space)->space_type == 2)
                                                    <div id="selectTime">
                                                        <div class="form-group p-0">
                                                            <label>{{ __('Start Time') . '*' }}</label>
                                                            <input id="timepickerForHourly" type="text"
                                                                placeholder="{{ __('Select Start time') }}"
                                                                class="timepicker form-control selectTime" name="start_time"
                                                                value="{{ old('start_time') }}">
                                                            <p id="startTimeError" class="text-danger mt-2 mb-0"></p>
                                                            @error('start_time')
                                                                <p class="text-danger mt-1">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (isset($space) && optional($space)->space_type == 2)
                                                    <div id="SelectHours">
                                                        <div class="form-group p-0">
                                                            <label>{{ __('Number Of Hours') . '*' }}</label>
                                                            <input type="number" class="form-control" name="hours"
                                                                id="hours" value="{{ old('hours') }}"
                                                                placeholder="{{ __('Enter hours') }}" min="1"
                                                                max="24">
                                                            <p id="totalHourError" class="text-danger mt-2 mb-0"></p>
                                                            @error('hours')
                                                                <p class="mt-2 mb-0 text-danger em">
                                                                    {{ $message }}</p>
                                                            @enderror
                                                            <p id="bookingMessage" class="mt-2 mb-0 text-danger em"
                                                                style="display:none;"></p>
                                                            <!-- Placeholder for JS message -->
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            <div id="numberOfGuest" class="form-group p-0">
                                                <label>{{ __('Number Of Guests') . '*' }}</label>
                                                <input type="number" class="form-control numberOfGuest"
                                                    name="number_of_guest"
                                                    placeholder="{{ __('Enter Number Of Guests') }}" min="1">
                                                @php
                                                    $spaceMargin = '10px';
                                                @endphp
                                                <span
                                                    class="text-warning">{{ __('Minimum Guests') . ': ' }}{{ optional($space)->min_guest }}</span>
                                                <span class="text-warning"
                                                    style="margin-left: {{ $spaceMargin }}">{{ __('Maximum Guests') . ': ' }}{{ optional($space)->max_guest }}</span>
                                                <p id="numberOfGuestError" class="text-danger mt-2 mb-0"></p>
                                                @error('numberOfGuest')
                                                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div id="customerFullName" class="form-group p-0">
                                                <label>{{ __('Customer Full Name') . '*' }}</label>
                                                <input type="text" class="form-control customerFullName"
                                                    name="customer_full_name" placeholder="{{ __('Enter Name') }}">
                                                @php
                                                    $spaceMargin = '10px';
                                                @endphp
                                                <p id="fullNameError" class="text-danger mt-2 mb-0"></p>
                                                @error('fullName')
                                                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div id="customerPhoneNumber" class="form-group p-0">
                                                <label>{{ __('Customer Phone Number') . '*' }}</label>
                                                <input type="text" class="form-control customerPhoneNumber"
                                                    name="customer_phone_number"
                                                    placeholder="{{ __('Enter phone number') }}" min="1">
                                                @php
                                                    $spaceMargin = '10px';
                                                @endphp

                                                <p id="customerPhoneNumberError" class="text-danger mt-2 mb-0"></p>
                                                @error('customerPhoneNumber')
                                                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div id="customerEmailAddress" class="form-group p-0">
                                                <label>{{ __('Customer Email') . '*' }}</label>
                                                <input type="email" class="form-control customerEmailAddress"
                                                    name="customer_email" placeholder="{{ __('Enter Email Address') }}">
                                                @php
                                                    $spaceMargin = '10px';
                                                @endphp
                                                <p id="customerEmailAddressError" class="text-danger mt-2 mb-0"></p>
                                                @error('numberOfGuest')
                                                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>


                                        </div>
                                        <div class="select-addons">
                                            @if ($serviceContentsWithSubservice->isNotEmpty())
                                                <div class="addons-title">
                                                    <h6 class="select-addons-title ">{{ __('Choose Services') }}
                                                    </h6>
                                                </div>
                                            @endif
                                            <div class="addons_list_area_wrapper">
                                                <div class="addons_list_area">
                                                    @if ($serviceContentsWithSubservice->isNotEmpty())
                                                        @foreach ($serviceContentsWithSubservice as $index => $serviceContent)
                                                            <div class="addons">
                                                                <h6 class="title mb-0">
                                                                    <button class="accordion-button" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapse{{ $index }}">
                                                                        {{ $serviceContent->service_title ?? '' }}
                                                                    </button>
                                                                </h6>
                                                                <div id="collapse{{ $index }}" class="collapse">
                                                                    <div class="accordion-body">
                                                                        <div class="addons_card_wrapper">
                                                                            @if ($serviceContent->subservice_selection_type === 'multiple')
                                                                                @foreach ($serviceContent->subServices as $key => $subservice)
                                                                                    <div class="addons_card_list">
                                                                                        <div class="card bg-none mb-10">
                                                                                            <div class="mb-1">
                                                                                                <div
                                                                                                    class="image-checkbox">
                                                                                                    <input type="checkbox"
                                                                                                        class="image-checkbox-input"
                                                                                                        name="image-{{ $loop->parent->index }}[]"
                                                                                                        id="img-{{ $subservice->id }}"
                                                                                                        value="{{ $subservice->id }}"
                                                                                                        data-img="{{ $subservice->image ?? '' }}"
                                                                                                        data-price_type="{{ $serviceContent->price_type ?? '' }}"
                                                                                                        data-space-service-id="{{ $serviceContent->id ?? '' }}"
                                                                                                        data-sub-service-id="{{ $subservice->id ?? '' }}"
                                                                                                        data-is_custom_day="{{ $serviceContent->is_custom_day ?? '' }}"
                                                                                                        data-index_value="{{ @$key }}"
                                                                                                        data-subservice_selection_type="{{ @$serviceContent->subservice_selection_type }}"
                                                                                                        data-index_value="{{ @$key }}"
                                                                                                        data-space_type="{{ $space->space_type ?? '' }}"
                                                                                                        data-has_subservcie_available="{{ @$serviceContent->has_sub_services }}"
                                                                                                        data-price="{{ $subservice->price ?? '' }}">
                                                                                                    <label
                                                                                                        for="img-{{ $subservice->id }}">
                                                                                                        <div
                                                                                                            class="card_img radius-sm">
                                                                                                            <img src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                                                                                                                data-src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                                                                                                                alt="{{ $subservice->service_title ?? '' }}">
                                                                                                        </div>
                                                                                                        <span
                                                                                                            class="btn btn-sm btn-primary no-animation radius-sm">{{ __('Selected') }}</span>
                                                                                                    </label>
                                                                                                </div>
                                                                                                @if (isset($space) && !empty($space->space_type) && $space->space_type == 3 && $serviceContent->is_custom_day == 1)
                                                                                                    <div
                                                                                                        class="selectedDay-{{ $subservice->id }} d-none">
                                                                                                        <p
                                                                                                            class="card_text font-sm mb-0 ">
                                                                                                            {{ __('Days') . ' :' }}
                                                                                                            <span
                                                                                                                class="numberOfCustomDay-{{ $subservice->id }}"></span>
                                                                                                            <span
                                                                                                                class="btn-label dayModalEditBtn"
                                                                                                                data-index_value="{{ @$key }}"
                                                                                                                data-service_id="{{ @$serviceContent->id }}"
                                                                                                                data-sub_service_id="{{ @$subservice->id }}"
                                                                                                                data-space-space_type="{{ @$space->space_type }}">
                                                                                                                <i
                                                                                                                    class="fas fa-edit"></i>
                                                                                                            </span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="card_content">
                                                                                                <p
                                                                                                    class="serviceStageTitle card_text font-sm mb-0">
                                                                                                    {{ $subservice->sub_service_title ?? '' }}
                                                                                                </p>
                                                                                                <p
                                                                                                    class="serviceStagePrice card_text font-sm ltr">
                                                                                                    {{ $position == 'left' ? $symbol : '' }}{{ $subservice->price ?? '' }}
                                                                                                    {{ $position == 'right' ? $symbol : '' }}{{ $serviceContent->price_type == 'fixed' ? '' : '/' . __('Person') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @else
                                                                                @foreach ($serviceContent->subServices as $key => $subservice)
                                                                                    <div class="addons_card_list">
                                                                                        <div class="card bg-none mb-10">
                                                                                            <div class=" mb-1">
                                                                                                <div class="image-radio">
                                                                                                    <input type="radio"
                                                                                                        class="image-radio-input"
                                                                                                        name="image-{{ $loop->parent->index }}"
                                                                                                        id="img-{{ $subservice->id }}"
                                                                                                        value="{{ $subservice->id }}"
                                                                                                        data-img="{{ $subservice->image ?? '' }}"
                                                                                                        data-is_custom_day="{{ $serviceContent->is_custom_day ?? '' }}"
                                                                                                        data-price_type="{{ $serviceContent->price_type ?? '' }}"
                                                                                                        data-space_type="{{ $space->space_type ?? '' }}"
                                                                                                        data-space-service-id="{{ $serviceContent->id ?? '' }}"
                                                                                                        data-index_value="{{ $key }}"
                                                                                                        data-subservice_selection_type="{{ @$serviceContent->subservice_selection_type }}"
                                                                                                        data-sub-service-id="{{ $subservice->id ?? '' }}"
                                                                                                        data-has_subservcie_available="{{ @$serviceContent->has_sub_services }}"
                                                                                                        data-price="{{ $subservice->price ?? '' }}">
                                                                                                    <label
                                                                                                        for="img-{{ $subservice->id }}">
                                                                                                        <div
                                                                                                            class="card_img radius-sm">
                                                                                                            <img src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                                                                                                                data-src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                                                                                                                alt="{{ $subservice->service_title ?? '' }}">
                                                                                                        </div>
                                                                                                        <span
                                                                                                            class="btn btn-sm btn-primary no-animation radius-sm">{{ __('Selected') }}</span>
                                                                                                    </label>
                                                                                                </div>

                                                                                                @if (isset($space) && !empty($space->space_type) && $space->space_type == 3 && $serviceContent->is_custom_day == 1)
                                                                                                    <div
                                                                                                        class="selectedDay-{{ $subservice->id }} d-none">
                                                                                                        <p
                                                                                                            class="card_text font-sm mb-0 ">
                                                                                                            {{ __('Days') . ' :' }}
                                                                                                            <span
                                                                                                                class="numberOfCustomDay-{{ $subservice->id }}"></span>
                                                                                                            <span
                                                                                                                class="btn-label dayModalEditBtn"
                                                                                                                data-index_value="{{ @$key }}"
                                                                                                                data-service_id="{{ @$serviceContent->id }}"
                                                                                                                data-sub_service_id="{{ @$subservice->id }}"
                                                                                                                data-space_type="{{ @$space->space_type }}">
                                                                                                                <i
                                                                                                                    class="fas fa-edit"></i>
                                                                                                            </span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="card_content">
                                                                                                <p
                                                                                                    class="serviceStageTitle card_text font-sm mb-0">
                                                                                                    {{ $subservice->sub_service_title ?? '' }}
                                                                                                </p>
                                                                                                <p
                                                                                                    class="serviceStagePrice card_text font-sm">
                                                                                                    {{ $position == 'left' ? $symbol : '' }}{{ $subservice->price ?? '' }}{{ $position == 'right' ? $symbol : '' }}
                                                                                                    ({{ __(ucfirst($serviceContent->price_type)) ?? '' }})
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="addons_list_area">
                                                    @if ($serviceContentsWithoutSubservice->isNotEmpty())
                                                        <div class="">
                                                            <h6 class="select-addons-title ">
                                                                {{ __('Choose Other Services') }}
                                                            </h6>
                                                        </div>
                                                    @endif
                                                    @if ($serviceContentsWithoutSubservice->isNotEmpty())
                                                        @foreach ($serviceContentsWithoutSubservice as $index => $serviceContent)
                                                            <div class="addons">
                                                                <div id="checkbox-container" class="custom-checkbox">
                                                                    <input class="input-checkbox" type="checkbox"
                                                                        name="checkbox"
                                                                        id="checkbox-{{ $index ?? '' }}"
                                                                        value="{{ $serviceContent->id ?? '' }}"
                                                                        data-price="{{ $serviceContent->price ?? '' }}"
                                                                        data-space-service-id="{{ $serviceContent->id ?? '' }}"
                                                                        data-is_custom_day="{{ $serviceContent->is_custom_day ?? '' }}"
                                                                        data-space_type="{{ $space->space_type ?? '' }}"
                                                                        data-price_type="{{ $serviceContent->price_type ?? '' }}"
                                                                        data-has_subservcie_available="{{ @$serviceContent->has_sub_services }}">
                                                                    <label class="form-check-label"
                                                                        for="checkbox-{{ $index ?? '' }}">
                                                                        <span class="h6 mb-0">
                                                                            <span
                                                                                class="title">{{ @$serviceContent->title_without_subservice }}</span>
                                                                            <span
                                                                                class="qty">{{ $position == 'left' ? $symbol : '' }}{{ @$serviceContent->price }}{{ $position == 'right' ? $symbol : '' }}{{ $serviceContent->price_type == 'fixed' ? '' : '/' . __('Person') }}
                                                                            </span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                @if (isset($space) && !empty($space->space_type) && $space->space_type == 3 && $serviceContent->is_custom_day == 1)
                                                                    <div
                                                                        class="selectedDay-{{ $serviceContent->id }} d-none">
                                                                        <p class="card_text font-sm mb-0 ">
                                                                            {{ __('Days') . ' :' }}
                                                                            <span
                                                                                class="numberOfCustomDay-{{ $serviceContent->id }}"></span>
                                                                            <span
                                                                                class="btn-label dayModalEditBtnWithoutSubservice"
                                                                                data-index_value="{{ @$key }}"
                                                                                data-service_id="{{ @$serviceContent->id }}"
                                                                                data-sub_service_id="{{ @$subservice->id }}"
                                                                                data-space-space_type="{{ @$space->space_type }}">
                                                                                <i class="fas fa-edit"></i>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-footer">
                                        <div class="border p-20">
                                            <div class="widget widget-select mb-20">
                                                <h4 class="select-addons-title addons-title-pricing-overview  mb-20">
                                                    {{ __('Pricing Overview') }}
                                                </h4>
                                                <ul class="list-group pb-20">
                                                    <li id="timeSlotRentWrapper" class="d-none">
                                                        <span class="h6 mb-0">{{ __('Time Slot Rent') }}</span>
                                                        <span class="h6 mb-0 timeSlotRentValue"></span>
                                                    </li>
                                                    @if (isset($spaceContent) &&
                                                            !empty($spaceContent->space_rent) &&
                                                            $spaceContent->space_type != 3 &&
                                                            $spaceContent->space_type != 2)
                                                        <li id="spaceRent">
                                                            <span class="h6 mb-0">{{ __('Space Rent') }}</span>
                                                            <span
                                                                data-space_rent="
                                                            {{ @$spaceContent->space_rent }}"
                                                                class="h6 mb-0 spaceRent">{{ $position == 'left' ? $symbol : '' }}{{ @$spaceContent->space_rent }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($spaceContent) && $spaceContent->space_type == 3 && !empty($spaceContent->price_per_day))
                                                        <li id="rentPerDay">
                                                            <span class="h6 mb-0">{{ __('Rent Per Day') }}</span>
                                                            <span
                                                                class="h6 mb-0 rentPerDay">{{ $position == 'left' ? $symbol : '' }}{{ @$spaceContent->price_per_day }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                        </li>
                                                    @endif

                                                    @if (isset($spaceContent) && $spaceContent->space_type == 2 && !empty($spaceContent->rent_per_hour))
                                                        <li id="rentPerHour">
                                                            <span class="h6 mb-0">{{ __('Rent Per Hour') }}</span>
                                                            <span
                                                                class="h6 mb-0 rentPerHour">{{ $position == 'left' ? $symbol : '' }}{{ @$spaceContent->rent_per_hour }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <span class="h6 mb-0">{{ __('Service Total') }}</span>
                                                        <span
                                                            class="h6 mb-0 serviceTotal">{{ $position == 'left' ? $symbol : '' }}0.00{{ $position == 'right' ? $symbol : '' }}</span>
                                                    </li>

                                                    @if (isset($spaceContent) && $spaceContent->space_type == 3)
                                                        <li>
                                                            <span
                                                                class="h6 mb-0 numberOfDayTextOneOrZero">{{ __('Number of Day') }}
                                                            </span>
                                                            <span
                                                                class="h6 mb-0 d-none numberOfDayTextMoreThanOne">{{ __('Number of Days') }}
                                                            </span>
                                                            <span class="h6 mb-0 numberOfDay">0</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($spaceContent) && $spaceContent->space_type == 2)
                                                        <li>
                                                            <span
                                                                class="h6 mb-0 numberOfHourTextOneOrZero">{{ __('Total Hour') }}</span>
                                                            <span
                                                                class="h6 mb-0 numberOfHourTextMoreThanOne d-none">{{ __('Total Hours') }}</span>
                                                            <span class="h6 mb-0 totalHourForSpaceType2">0</span>
                                                        </li>
                                                    @endif
                                                    <li id="subTotalAmount">
                                                        <span class="h6 mb-0 color-primary"> {{ __('Subtotal') }}</span>
                                                        <span class="h6 mb-0 color-primary subTotalAmount" dir="ltr">
                                                            {{ $position == 'left' ? $symbol : '' }}
                                                            @if (isset($subtotal) && !empty($subtotal))
                                                                {{ $subtotal }}
                                                            @else
                                                                {{ '0.00' }}
                                                            @endif
                                                            {{ $position == 'right' ? $symbol : '' }}
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span class="h6 mb-0 ">{{ __('Tax') }}
                                                            <span dir="ltr">{{ ' (' . number_format(@$settings->tax, 2) . '%)' }}</span>
                                                        </span>
                                                        <span dir="ltr" class="h6 mb-0" id="vatAmountSpan">
                                                            {{ $position == 'left' ? $symbol : '' }}
                                                            @if (isset($vatAmount) && !empty($vatAmount))
                                                                {{ number_format($vatAmount, 2) }}
                                                            @else
                                                                {{ '0.00' }}
                                                            @endif
                                                            {{ $position == 'right' ? $symbol : '' }}
                                                        </span>
                                                    </li>

                                                    <input type="hidden" id="taxPerSpace" name=""
                                                        value={{ @$settings->tax }}>
                                                    <li id="TotalAmount">
                                                        <span class="h6 mb-0 color-primary"> {{ __('Total') }}</span>

                                                        <span dir="ltr" id="totalAmount" class="h6 mb-0 color-primary totalAmount">
                                                            {{ $position == 'left' ? $symbol : '' }}
                                                            @if (isset($totalAmount) && !empty($totalAmount))
                                                                {{ number_format($totalAmount , 2) }}
                                                            @else
                                                                {{ '0.00' }}
                                                            @endif
                                                            {{ $position == 'right' ? $symbol : '' }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="form-group p-0">
                                                <label
                                                    for="spaceDiscount">{{ __('Discount') . ' (' . $settings->base_currency_text . ')' }}</label>
                                                <input type="text" id="spaceDiscount" class="form-control"
                                                    name="space_discount" value="">
                                            </div>
                                            <div class="form-group p-0">
                                                <label>{{ __('Payment Status') . '*' }}</label>
                                                <select name="payment_status" class="form-control niceselect">
                                                    <option selected disabled>{{ __('Select Payment Status') }}
                                                    </option>
                                                    <option {{ old('payment_status') == 'completed' ? 'selected' : '' }}
                                                        value="completed">
                                                        {{ __('Paid') }}
                                                    </option>
                                                    <option {{ old('payment_status') == 'pending' ? 'selected' : '' }}
                                                        value="pending">
                                                        {{ __('Unpaid') }}
                                                    </option>
                                                </select>
                                                <div id="paymentStatusId"></div>
                                                <p id="paymentStatusError" class="text-danger mt-2 mb-0"></p>
                                                @error('payment_status')
                                                    <p class="mt-1 mb-0 ml-1 text-danger">{{ __($message) }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <select id="payment-gateway" class="niceselect form-control border "
                                                    name="gateway">
                                                    <option value="" selected="" disabled="">
                                                        {{ __('Select a Payment Gateway') }}
                                                    </option>
                                                    @if (count($onlineGateways) > 0)
                                                        @foreach ($onlineGateways as $onlineGateway)
                                                            <option value="{{ $onlineGateway->keyword }}"
                                                                {{ old('gateway') == $onlineGateway->keyword ? 'selected' : '' }}
                                                                data-gateway_type="online">
                                                                {{ __($onlineGateway->name) }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                    @if (count($offlineGateways) > 0)
                                                        @foreach ($offlineGateways as $offlineGateway)
                                                            <option value="{{ $offlineGateway->name }}"
                                                                {{ old('gateway') == $offlineGateway->name ? 'selected' : '' }}
                                                                data-gateway_type="offline"
                                                                data-has_attachment="{{ $offlineGateway->has_attachment }}">
                                                                {{ __($offlineGateway->name) }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="paymentGatewayErrorId"></div>
                                                <p id="paymentGatewayError" class="text-danger mt-2 mb-0"></p>
                                                <button id="confirmBookingButton"
                                                    class="btn btn-lg btn-primary radius-sm w-100" type="submit"
                                                    aria-label="button"> {{ __('Proceed To Pay') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </aside>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal day -->
    <div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('How Many Days') . '?' }}
                    </h5>
                    <button type="button" class="daymodal-close" data-dismiss="modal" aria-label="Close"><i
                            class="fal fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-10">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitBtnForDayValue"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dayEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('Edit How Many Days') . '?' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-10">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceType">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitEditBtnForDayValue"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dayModalwithoutService" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('Edit How Many Days') . '?' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-10">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitEditBtnWithoutSubservice"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('variable')
    <script>
        var type = {!! json_encode($space->space_type ?? '1') !!};
        var isTimeSlotRent = {!! json_encode($spaceContent->use_slot_rent ?? '0') !!};
        var timeFormatSpaceDetails = {!! json_encode($settings->time_format ?? '') !!};
        var openingTime = {!! json_encode($space->opening_time ?? '') !!};
        var closingTime = {!! json_encode($space->closing_time ?? '') !!};
        var prepareTime = {!! json_encode($space->prepare_time ?? '') !!};
        var type = {!! json_encode($space->space_type) !!};
        var weekendDays = @json($weekendDays);
        var quantity = {!! json_encode($quantity) !!};
        var holidayDate = {!! json_encode($holiday_date) !!};
    </script>
@endsection

@section('script')
    <script>
        'use strict'
        var type = {!! json_encode($space->space_type) !!};
        var spaceBooking = @json($spaceBooking);
        var bookingsArray = @json($spaceBooking);
        var currencyPosition = @json($position);
        var currencySymbol = @json($symbol);
        var getTimeSlotUrl = "{{ route('vendor.booking.get_time_slot') }}";
        var spaceDetailsUrl = "{{ route('space.details') }}";
        var translations = {
            timeSlotRequired: "{{ __('Time slot is required') . '.' }}",
            dateRequired: "{{ __('Date is required') . '.' }}",
            numberOfGuestsRequired: "{{ __('Number of guests is required') . '.' }}",
            startTime: "{{ __('Start time  is required') . '.' }}",
            hours: "{{ __('Hours  is required') . '.' }}",
            inValidNumber: "{{ __('Custom hour cannot be 0. Please enter a valid number') . '.' }}",
            timeOvarlap: "{{ __('Time overlaps with booking') . '.' }}",
            tryDifferent: "{{ __('Please choose a different time slot') . '.' }}",
            selectTimeslot: "{{ __('Select Time Slot') }}",
            isReserved: "{{ __('is reserved') . '.' }}",
            moreThanNumberOfDay: "{{ __('Day value cannot be more than') }}",
            days: "{{ __('Days') }}",
            day: "{{ __('Day') }}",
            paymentStatusRequired: "{{ __('Payment status is required') . '.' }}",
            paymentGatewayError: "{{ __('Payment gateway is required when payment status is paid') . '.' }}",
            downgradeErrorMsg: "{{ __('Your feature limit is over or down graded') . '!' }}",
            warningMsg: "{{ __('Warning') }}",
            fullName: "{{ __('Full name is required') . '.' }}",
            customerPhoneNumber: "{{ __('Phone number is required') . '.' }}",
            customerEmailAddress: "{{ __('Email address is required') . '.' }}",
            noResulrFound: "{{ __('No results found') }}",
        };

    </script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-add-booking.js') }}"></script>
@endsection
