@extends('frontend.layout')
@php
    $imagePath = $spaceContent->image ? 'assets/img/spaces/thumbnail-images/' . $spaceContent->image : '';
    $fullImagePath = asset($imagePath);
    $currentUrl = url()->current();
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$spaceContent" :og-url="$currentUrl" :og-image="$fullImagePath" />

@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;
@endphp

@section('content')


    <div class="breadcrumb-area breadcrumb-area_v2 header-next bg-primary-light">
        <div class="container">
            <div class="content pt-5 pb-4">
                <h3 class="mb-20">{{ @$spaceContent->title }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('space.index') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$pageHeading->space_details_page_title ?? __('Space Details') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <!-- listing-details-area start -->
    <div class="listing-details-area pt-50 pb-60">
        <div class="container">
            <div class="pb-50">
                <!-- product-slider-wrapper -->
                <div class="product-slider-wrapper">
                    <div class="swiper product-slider-style-2 radius-sm">
                        <div class="swiper-wrapper">
                            @foreach ($sliderImages as $sliderImage)
                                <div class="swiper-slide slide-item lazy-container">
                                    <a class="gallery-item"
                                        href="{{ asset('assets/img/spaces/slider-images/' . $sliderImage) }}">
                                        <img class="lazyload"
                                            src="{{ asset('assets/img/spaces/slider-images/' . $sliderImage) }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="slider-navigation">
                            <div class="slider-btn slider-btn-prev rounded-circle"><i class="fal fa-angle-left"></i></div>
                            <div class="slider-btn slider-btn-next rounded-circle"><i class="fal fa-angle-right"></i></div>
                        </div>
                    </div>
                    <div thumbsSlider="" class="swiper product-slider-style-2-thump">
                        <div class="swiper-wrapper">
                            @foreach ($sliderImages as $sliderImage)
                                <div class="swiper-slide slide-thump-item">
                                    <img src="{{ asset('assets/img/spaces/slider-images/' . $sliderImage) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gx-xl-5">
                <div class="col-lg-8">
                    <div class="product-single-details" data-aos="fade-up">
                        <div class="product-info">
                            <h3 class="product-title mb-15">{{ @$spaceContent->title }}</h3>
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <ul class="product-info_list list-unstyled">
                                    <li class="location" data-tooltip="tooltip" data-bs-placement="top"
                                        title="{{ __('Space Address') }}">
                                        <i class="fal fa-map-marker-alt"></i>
                                        <span>

                                            @if ($spaceContent->city_name)
                                                {{ trim($spaceContent->city_name) }}
                                                @if ($spaceContent->state_name)
                                                    , {{ trim($spaceContent->state_name) }}
                                                @endif
                                                @if ($spaceContent->country_name)
                                                    , {{ trim($spaceContent->country_name) }}
                                                @endif
                                            @elseif ($spaceContent->state_name)
                                                {{ trim($spaceContent->state_name) }}
                                                @if ($spaceContent->country_name)
                                                    , {{ trim($spaceContent->country_name) }}
                                                @endif
                                            @elseif ($spaceContent->country_name)
                                                {{ trim($spaceContent->country_name) }}
                                            @else
                                                {{ ' ' }}
                                            @endif
                                        </span>
                                    </li>
                                    <li data-tooltip="tooltip" data-bs-placement="top" title="{{ __('Space Size') }}">
                                        <i class="fal fa-square"></i>
                                        <span>{{ @$spaceContent->space_size }}
                                            {{ @$spaceUnit->space_units }}</span>
                                    </li>
                                    <li data-tooltip="tooltip" data-bs-placement="top" title="{{ __('Space Capacity') }}">
                                        <i class="fal fa-users"></i>
                                        <span>{{ @$spaceContent->min_guest }}-{{ @$spaceContent->max_guest }}</span>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#socialMediaModal"
                                            class="share-btn">
                                            <i class="fas fa-share-alt"></i>
                                            {{ __('Share') . ' ' }}
                                        </a>
                                    </li>
                                </ul>

                                @php
                                    $filledStars = $averageRating > 0 ? ($averageRating / 5) * 100 : 0;
                                @endphp
                                
                                <div class="d-flex gap-1 align-items-center">
                                    <div class="ratings size-md">
                                        <div class="rate bg-img"
                                            data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}"
                                            style="background-image: url('{{ asset('assets/frontend/images/rate-star-md.png') }}'); background-repeat: no-repeat;">
                                            <div class="rating-icon bg-img"
                                                style="width: {{ $filledStars }}%; background-image: url('{{ asset('assets/frontend/images/rate-star-md.png') }}'); background-repeat: no-repeat;"
                                                data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                                            </div>
                                        </div>
                                        <span
                                            class="ratings-total">{{ number_format($averageRating, 1) . ' ' . '(' . $reviewCount . ')' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Displays a horizontal tab navigation menu --}}
                        <div>
                            <x-space-details.tabs-navigation />

                            {{-- TAB CONTENT WRAPPER COMPONENT --}}
                            <x-space-details.tab-content>

                                {{-- Displays the space description content --}}
                                <x-space-details.tab-overview :description="$spaceContent->description" />

                                {{-- Displays the space amenities --}}
                                <x-space-details.tab-amenities :amenityIds="json_decode($spaceContent->amenities) ?? []" />

                                {{-- Displays the space location --}}
                                <x-space-details.tab-location :address="$spaceContent->address" :latitude="$space->latitude" :longitude="$space->longitude"
                                    :websiteTitle="$websiteInfo->website_title" />

                                {{-- Displays the space review section --}}
                                @include('frontend.space.review')
                            </x-space-details.tab-content>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <aside class="widget-area" data-aos="fade-up">
                        <div class="widget widget-booking border radius-md mb-40">
                            <div class="p-20 border-bottom">
                                <h4 class="title">
                                    {{ __('Booking Form') }}
                                </h4>
                            </div>
                            <form action="{{ route('confirm.booking', ['slug' => request()->slug]) }}" method="POST"
                                id="selectedItemsForm">
                                @csrf
                                <input type="hidden" name="space_id" value="{{ $spaceContent->space_id }}">
                                <input type="hidden" name="seller_id" value="{{ $spaceContent->seller_id }}">
                                <input id="prepareTimeId" type="hidden" name="prepare_time"
                                    value="{{ @$spaceContent->prepare_time }}">
                                @php
                                    $columnClass =
                                        optional($space)->booking_status == 1 && optional($space)->book_a_tour == 1
                                            ? 'col-md-6'
                                            : 'col-md-12';
                                @endphp

                                @if (isset($space) && optional($space)->booking_status == 1)
                                    <div class="px-20 pt-20">
                                        <div class="row gx-3">
                                            @if (isset($space) && optional($space)->booking_status == 1)
                                                <div class="{{ $columnClass }} mb-20">
                                                    <a href="#" data-bs-toggle="modal"
                                                        class="btn btn-lg btn-primary radius-sm w-100"
                                                        data-bs-target="#getQuoteModal">
                                                        {{ __('Get Quote') }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if (isset($space) && optional($space)->book_a_tour == 1)
                                                <div class="col-md-6 mb-20">
                                                    <a href="#" data-bs-toggle="modal"
                                                        class="btn btn-lg btn-primary radius-sm w-100"
                                                        data-bs-target="#bookATourModal">
                                                        {{ __('Book A Tour') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if (isset($space) && optional($space)->space_type == 3)
                                        <div class="date-form p-20 pb-0">
                                            <div class="form-group">
                                                <label for="eventDate">{{ __('Date') . '*' }}</label>
                                                <input type="text"
                                                    class="form-control text-only checkInDate spaceBookingDate"
                                                    id="eventDate" name="eventDate" autocomplete="off"
                                                    placeholder="{{ __('Date Format') }}"
                                                    data-space_id="{{ $spaceContent->space_id }}"
                                                    data-seller_id="{{ $spaceContent->seller_id }}" />
                                                @error('bookingDate')
                                                    <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    @if (isset($space) && optional($space)->book_a_tour == 1)
                                        <div class="px-20 pt-20">
                                            <a href="#" data-bs-toggle="modal"
                                                class="btn btn-lg btn-primary radius-sm w-100 mb-10"
                                                data-bs-target="#bookATourModal"> {{ __('Book A Tour') }}
                                            </a>
                                        </div>
                                    @endif
                                    @if (isset($space) && optional($space)->space_type == 3)
                                        <div class="form-group  p-20 pb-0">
                                            <label for="eventDate">{{ __('Date') . '*' }}</label>
                                            <input type="text"
                                                class="form-control text-only checkInDate spaceBookingDate" id="eventDate"
                                                name="eventDate" autocomplete="off"
                                                placeholder="{{ __('Date Format') }}"
                                                data-space_id="{{ $spaceContent->space_id }}"
                                                data-seller_id="{{ $spaceContent->seller_id }}" />

                                            @error('bookingDate')
                                                <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <p id="" class="mt-2 mb-0 text-danger em  p-20 bookingMessage"
                                            style="display:none;">
                                        </p>
                                    @else
                                        <div class="date-time-group date-form p-20 pb-0">
                                            <div class="form-group">
                                                <label for="eventDate">{{ __('Date') . '*' }}</label>
                                                <input type="text"
                                                    class="form-control text-only checkInDate spaceBookingDate"
                                                    id="eventDate" name="eventDate" autocomplete="off"
                                                    placeholder="{{ __('Date Format') }}"
                                                    data-space_id="{{ $spaceContent->space_id }}"
                                                    data-seller_id="{{ $spaceContent->seller_id }}" />
                                                @error('bookingDate')
                                                    <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if (isset($space) && optional($space)->space_type == 1)
                                                <div class="form-group">
                                                    <label for="eventTime">{{ __('Time') . '*' }}</label>
                                                    <select class="form-control mdb_343 select2" name="eventTime"
                                                        id="eventTime" dir="ltr">
                                                        <option value="" selected disabled>
                                                            {{ __('Select Time Slot') }}
                                                        </option>

                                                    </select>
                                                    <div id="timeSlotId"></div>
                                                    @error('timeSlotId')
                                                        <p class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @elseif(isset($space) && optional($space)->space_type == 2)
                                                <div id="selectTime" class="selecttime">
                                                    <div class="form-group selecttimefrom">
                                                        <label>{{ __('Start Time') . '*' }}</label>

                                                        <input id="timepickerForHourly" type="text"
                                                            placeholder="{{ __('Select Start time') }}"
                                                            class="timepicker selectTime" name="start_time"
                                                            value="{{ old('start_time') }}">
                                                    </div>
                                                    <div id="startTimeForHourlyRental"></div>
                                                    @error('start_time')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>

                                        @if (isset($space) && optional($space)->space_type == 2)
                                            <div id="SelectHours">
                                                <div class="form-group p-20 pb-0">
                                                    <label>{{ __('Number Of Hours') . '*' }}</label>
                                                    <input type="number" class="form-control" name="hours"
                                                        id="hours" value="{{ old('hours') }}"
                                                        placeholder="{{ __('Enter hours') }}" min="1"
                                                        max="24">
                                                    @error('hours')
                                                        <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                                    @enderror
                                                    <p id="" class="mt-2 mb-0 text-danger em bookingMessagehour"
                                                        style="display:none;">
                                                    </p> <!-- Placeholder for JS message -->
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                <div id="numberOfGuest">
                                    <div class="form-group p-20 ">
                                        <label>{{ __('Number Of Guests') . '*' }}</label>
                                        <input type="number" class="form-control numberOfGuest" name="number_of_guest"
                                            placeholder="{{ __('Enter Number Of Guests') }}" min="1">
                                        @php
                                            $spaceMargin = '10px';
                                        @endphp
                                        <span
                                            class="text-warning">{{ __('Minimum Guests') . ': ' }}{{ optional($space)->min_guest }}</span>
                                        <span class="text-warning"
                                            style="margin-left: {{ $spaceMargin }}">{{ __('Maximum Guests') . ': ' }}{{ optional($space)->max_guest }}</span>
                                        @error('numberOfGuest')
                                            <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($serviceContentsWithSubservice->isNotEmpty() || $serviceContentsWithoutSubservice->isNotEmpty())
                                    <div class="select-addons bg-primary-light">
                                        <div class="p-20 bg-primary-light ">
                                            <h6 class="mb-0">{{ __('Choose Services') }}</h6>
                                        </div>
                                        @if ($serviceContentsWithSubservice->isNotEmpty())
                                            @foreach ($serviceContentsWithSubservice as $index => $serviceContent)
                                                <div class="addons">
                                                    <h6 class="title">
                                                        <button class="accordion-button border-bottom p-20 bg-white"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $index }}">
                                                            {{ $serviceContent->service_title ?? '' }}
                                                        </button>

                                                    </h6>

                                                    <div id="collapse{{ $index }}" class="collapse">
                                                        <div class="accordion-body px-20 pt-20">

                                                            <div class="row gx-xl-2">
                                                                @if ($serviceContent->subservice_selection_type === 'multiple')
                                                                    @foreach ($serviceContent->subServices as $key => $subservice)
                                                                        {{-- This service has variants and allows selecting multiple variants --}}
                                                                        <x-space-details.multi_variant_card
                                                                            :subservice="$subservice" :serviceContent="$serviceContent"
                                                                            :space="$space" :position="$position"
                                                                            :symbol="$symbol" :key="$key"
                                                                            :parentIndex="$loop->parent->index" />
                                                                    @endforeach
                                                                @else
                                                                    @foreach ($serviceContent->subServices as $key => $subservice)
                                                                        {{-- This service has variants and allows selecting single variant--}}
                                                                        <x-space-details.variants_card :subservice="$subservice"
                                                                            :serviceContent="$serviceContent" :space="$space"
                                                                            :position="$position" :symbol="$symbol"
                                                                            :key="$key" :parentIndex="$loop->parent->index" />
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- This service has no variants and allows selecting multiple services --}}
                                        @if ($serviceContentsWithoutSubservice->isNotEmpty())
                                            @foreach ($serviceContentsWithoutSubservice as $index => $serviceContent)
                                                <x-space-details.without_variants :serviceContent="$serviceContent" :index="$index"
                                                    :position="$position" :symbol="$symbol" :space="$space"
                                                    :key="$index" serviceType="withoutSubservice" />
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                {{-- pricing overview section --}}
                                <x-space-details.pricing_overview :spaceContent="$spaceContent" :space="$space" :position="$position"
                                    :symbol="$symbol" />
                            </form>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    {{-- this code is displayed related spaces --}}
    @if ($relatedSpaces->isNotEmpty())
        <section class="pb-50 related-product">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title mb-30 d-flex justify-content-between align-items-center">
                            <h3 class="title mb-0">{{ __('Related Spaces') }}</h3>
                            <div class="slider-navigation related-product-navigation d-flex">
                                <div class="slider-btn slider-btn-prev rounded-circle"><i class="fal fa-angle-left"></i>
                                </div>
                                <div class="slider-btn slider-btn-next rounded-circle"><i class="fal fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper related-product-slider">
                    <div class="swiper-wrapper">
                        @foreach ($relatedSpaces as $space)
                            <x-space.related_space :space="$space" :position="$position" :symbol="$symbol" />
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @include('frontend.space.get-quote-modal')
    @include('frontend.space.book-a-tour-modal')

    <!-- Modal day -->
    <div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('How Many Days') . "?" }}
                    </h5>
                    <button type="button" class="btn_close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fal fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitBtnForDayValue"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal day -->
    <div class="modal fade" id="dayEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('Edit How Many Days') . '?' }}
                    </h5>
                    <button type="button" class="btn_close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fal fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceType">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitEditBtnForDayValue"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dayModalwithoutService" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('Edit How Many Days') . '?' }}
                    </h5>
                    <button type="button" class="btn_close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fal fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input type="number" class="form-control" value="" id="inputDayForService">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="subserviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceId">
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" value="" id="serviceIndexValue">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitEditBtnWithoutSubservice"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for social media link -->
    @include('frontend.partials.social-media-link-modal')

    <!-- listing-details-area end -->
@section('variables')
    <script>
        var type = {!! json_encode($space->space_type ?? '1') !!};
        var isTimeSlotRent = {!! json_encode($spaceContent->use_slot_rent ?? '0') !!};
        var timeFormatSpaceDetails = {!! json_encode($basicInfo->time_format ?? '') !!};
        var openingTime = {!! json_encode($opening_time ?? '') !!};
        var closingTime = {!! json_encode($closing_time ?? '') !!};
        var prepareTime = {!! json_encode($space->prepare_time ?? '') !!};
    </script>
@endsection

<script>
    'use strict'
    var translations = {
        timeSlotRequired: "{{ __('Time slot is required.') }}",
        dateRequired: "{{ __('Date is required.') }}",
        numberOfGuestsRequired: "{{ __('Number of guests is required.') }}",
        startTime: "{{ __('Start time is required') }}",
        hours: "{{ __('Hours is required') . '.' }}",
        inValidNumber: "{{ __('Custom hour cannot be 0. Please enter a valid number') . '.' }}",
        timeOvarlap: "{{ __('Time overlaps with booking') }}",
        tryDifferent: "{{ __('Please choose a different time slot') }}",
        timeSlot: "{{ __('Time slot') }}",
        isReserved: "{{ __('is reserved') }}",
        selectTimeslot: "{{ __('Select Time Slot') }}",
        noResulrFound: "{{ __('No results found') }}",
        moreThanNumberOfDay: "{{ __('Day value cannot be more than') }}",
        days: "{{ __('Days') }}",
        day: "{{ __('Day') }}",
    };
    var weekendDays = @json($weekendDays);
    var bookingsArray = @json($bookedMultidaySpace);
    var currencyPosition = @json($position);
    var currencySymbol = @json($symbol);
    var spaceBooking = @json($spaceBooking);
    var type = {!! json_encode($space->space_type) !!};
    var quantity = {!! json_encode($quantity) !!};
    var holidayDate = {!! json_encode($holiday_date) !!};
    var getTimeSlotUrl = "{{ route('frontend.booking.get_time_slot') }}";
    var spaceDetailsUrl = "{{ route('space.details') }}";
    var loginUrl = "{{ route('user.login') }}";

    var errorMessageForBooking = "{{ __('An error occurred during booking. Please try again') . '.' }}";
</script>

@endsection
