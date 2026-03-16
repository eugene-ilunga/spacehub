@extends('frontend.layout')

@section('pageHeading')
    {{ $pageHeading->space_page_title ?? __('Spaces') }}
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_space_booking ?? '' }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_space_booking ?? '' }}
    @endif
@endsection
@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;
@endphp

@php
    $title = $pageHeading->checkout_page_title ?? __('Checkout');
@endphp

@section('content')

        <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])
    <!-- Breadcrumb end -->

    <!-- Checkout-area start -->
    <div class="shopping-area pt-100 pb-60">
        <div class="container">
            <form action="{{ route('service.place_order', ['slug' => $spaceContent->slug ?? '']) }}" method="POST"
                enctype="multipart/form-data" id="payment-form">
                @csrf

                <input type="hidden" name="space_id" value="{{ @$spaceContent->space_id }}">
                <input type="hidden" name="number_of_guest" value="{{ @$numberOfGuest }}">
                <input type="hidden" name="start_time" value="{{ @$startTime }}">
                <input type="hidden" name="end_time" value="{{ @$endTime }}">
                <input type="hidden" name="time_slot_id" value="{{ @$timeSlotId }}">
                <input type="hidden" name="booking_date" value="{{ @$bookingDate }}">
                <input type="hidden" name="seller_id" value="{{ @$sellerId }}">
                <input type="hidden" name="grand_total" value="{{ @$grandTotal }}">
                <input type="hidden" name="space_rent" value="{{ @$spaceRent }}">
                <input type="hidden" name="sub_total" value="{{ @$subtotal }}">
                <input type="hidden" name="tax" value="{{ @$taxAmount }}">
                <input type="hidden" name="tax_percentage" value="{{ @$taxPercentage }}">

                <div class="row gx-xl-5">
                    <div class="col-lg-8">
                        <div class="billing-details">
                            <h4 class="mb-20">{{ __('Booking Details') }}</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <input type="hidden" name="user_id"
                                            value="{{ Auth::check() ? Auth::user()->id ?? '' : '' }}">
                                        <label for="firstName" class="form-label font-sm">{{ __('First Name') }}*</label>
                                        <input id="firstName" type="text" class="form-control" name="first_name"
                                            placeholder="{{ __('First Name') }}"
                                            value="{{ old('first_name', Auth::check() ? Auth::user()->first_name ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="lastName" class="form-label font-sm">{{ __('Last Name') }}</label>
                                        <input id="lastName" type="text" class="form-control" name="last_name"
                                            placeholder="{{ __('Last Name') }}"
                                            value="{{ old('last_name', Auth::check() ? Auth::user()->last_name ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="phone" class="form-label font-sm">{{ __('Phone Number') }}*</label>
                                        <input id="phone" type="text" class="form-control" name="customer_phone"
                                            placeholder="{{ __('Phone Number') }}"
                                            value="{{ old('customer_phone', Auth::check() ? Auth::user()->phone_number ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="email" class="form-label font-sm">{{ __('Email Address') }}*</label>
                                        <input id="email" type="email" class="form-control" name="email_address"
                                            placeholder="{{ __('Email Address') }}"
                                            value="{{ old('email_address', Auth::check() ? Auth::user()->email_address ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="district" class="form-label font-sm">{{ __('State') }}</label>
                                        <input id="district" type="text" class="form-control" name="district"
                                            placeholder="{{ __('State') }}"
                                            value="{{ old('district', Auth::check() ? Auth::user()->state ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="city" class="form-label font-sm">{{ __('City') }}</label>
                                        <input id="city" type="text" class="form-control" name="city"
                                            placeholder="{{ __('City') }}"
                                            value="{{ old('city', Auth::check() ? Auth::user()->city ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="postCode"
                                            class="form-label font-sm">{{ __('Post Code') }}/{{ __('Zip') }}</label>
                                        <input id="postCode" type="text" class="form-control" name="post_code"
                                            placeholder="{{ __('Post Code') }}"
                                            value="{{ old('post_code', Auth::check() ? Auth::user()->post_code ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-20">
                                        <label for="country" class="form-label font-sm">{{ __('Country') }}</label>
                                        <input id="country" type="text" class="form-control" name="country"
                                            placeholder="{{ __('Country') }}"
                                            value="{{ old('country', Auth::check() ? Auth::user()->country ?? '' : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-20">
                                        <label for="address" class="form-label font-sm">{{ __('Address') }}</label>
                                        <input id="address" type="text" class="form-control" name="address"
                                            placeholder="{{ __('Address') }}"
                                            value="{{ old('address', Auth::check() ? Auth::user()->address ?? '' : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="order-summary form-block border radius-md mb-30">
                            <h5 class="pb-15 mb-15 border-bottom">{{ __('Your Booking Summary') }}</h5>
                            <ul class="item_list">
                                @if (isset($totalHour) && !empty($totalHour))
                                    <li class="d-flex justify-content-between">
                                        @if ($totalHour > 1)
                                            <p class="font-medium mb-0 color-dark">{{ __('Total Hours') }}</p>
                                        @else
                                            <p class="fw-regular mb-0 color-dark">{{ __('Total Hour') }}</p>
                                        @endif
                                        <span class="price">{{ @$totalHour }}</span>
                                    </li>
                                @endif
                                {{-- booking date set for space type 1 (fixed time slot) and 2 (hourly) --}}
                                @if (isset($spaceType) && $spaceType != 3)
                                    @if (isset($bookingDate) && !empty($bookingDate))
                                        <li class="d-flex justify-content-between">
                                            <p class="fw-regular mb-0 color-dark">{{ __('Booking Date') }}</p>
                                            <span
                                                class="price">{{ \Carbon\Carbon::parse($bookingDate)->format('F j, Y') }}</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- start time set for space type 1 (fixed time slot) and 2 (hourly) --}}
                                @if (isset($startTime) && !empty($startTime))
                                    @php
                                        if ($spaceType == 2) {
                                            $parsedTime = \Carbon\Carbon::parse($startTime)->format('h:i A');
                                        } else {
                                            $parsedTime = $startTime;
                                        }
                                    @endphp
                                    <li class="d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('Start Time') }}</p>
                                        <span style="direction: ltr;" class="price">{{ $parsedTime }}
                                        </span>
                                    </li>
                                @endif

                                {{-- only this end time set for space type 2 (hourly) --}}
                                @if (isset($endTimeWithoutInterval) && !empty($endTimeWithoutInterval))
                                    <li class="d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('End Time') }}</p>
                                        <span style="direction: ltr;"
                                            class="price">{{ \Carbon\Carbon::parse($endTimeWithoutInterval)->format('h:i A') }}</span>
                                    </li>
                                @endif
                                {{-- end time set for space type 1 (fixed time slot) --}}
                                @if (isset($spaceType) && $spaceType == 1)
                                    @if (isset($endTime) && !empty($endTime))
                                        <li class="d-flex justify-content-between">
                                            <p class="fw-regular mb-0 color-dark">{{ __('End Time') }}</p>
                                            <span style="direction: ltr;" class="price">{{ $endTime }}
                                            </span>
                                        </li>
                                    @endif
                                @endif
                                {{-- start date set for space type 3 (multi day) --}}
                                @if (isset($startDate) && !empty($startDate))
                                    <li class="d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('Start Date') }}</p>
                                        <span
                                            class="price">{{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }}</span>
                                    </li>
                                @endif
                                {{-- end date set for space type 3 (multi day) --}}
                                @if (isset($endDate) && !empty($endDate))
                                    <li class="d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('End Date') }}</p>
                                        <span
                                            class="price">{{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}</span>
                                    </li>
                                @endif

                                @if (isset($numberOfGuest) && !empty($numberOfGuest))
                                    <li class="d-flex justify-content-between">
                                        @if ($numberOfGuest > 1)
                                            <p class="fw-regular mb-0 color-dark">{{ __('Number Of Guests') }}</p>
                                        @else
                                            <p class="fw-regular mb-0 color-dark">{{ __('Number Of Guest') }}</p>
                                        @endif
                                        <span class="price">{{ @$numberOfGuest }}</span>
                                    </li>
                                @endif

                            </ul>

                            @foreach ($serviceStages as $service)
                                <ul class="item_list mt-10 subservice">
                                    <h6 class="fw-medium mb-1 color-primary">{{ $service['spaceService']->service_title }}
                                    </h6>
                                    @if (!empty($service['subServices']))
                                        @foreach ($service['subServices'] as $subService)
                                            <li class="d-flex justify-content-between">
                                                <p class="fw-regular mb-0 color-dark">
                                                    {{ $subService->space_type == 3
                                                        ? $subService->sub_service_title .
                                                            ' (' .
                                                            $subService->number_of_day .
                                                            ' ' .
                                                            ($subService->number_of_day > 1 ? __('Days') : __('Day')) .
                                                            ')'
                                                        : $subService->sub_service_title ?? '' }}
                                                </p>
                                                @if ($service['spaceService']->price_type === 'per person')
                                                    <span
                                                        class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($subService->price * $numberOfGuest, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                @else
                                                    <span
                                                        class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($subService->price, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                @endif

                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            @endforeach
                            @if (!empty($otherServices))
                                <ul class="item_list mt-10">

                                    @foreach ($otherServices as $otherService)
                                        <li class="d-flex justify-content-between">
                                            <p class="fw-regular mb-0 color-primary">
                                                {{ $otherService->space_type == 3
                                                    ? $otherService->service_title .
                                                        ' (' .
                                                        $otherService->numberOfCustomDay .
                                                        ' ' .
                                                        ($otherService->numberOfCustomDay > 1 ? __('Days') : __('Day')) .
                                                        ')'
                                                    : $otherService->service_title ?? '' }}
                                            </p>
                                            @if ($otherService->price_type === 'per person')
                                                <span
                                                    class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($otherService->price * $numberOfGuest, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                            @else
                                                <span
                                                    class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($otherService->price, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>

                        {{-- Payment Details Start --}}
                        <div class="form-block border radius-md mb-30">
                            <h5 class="pb-15 mb-15 border-bottom">{{ __('Payment Details') }}</h5>
                            <ul>
                                @if ($rent != 0)
                                    <li class="sub-total d-flex justify-content-between">
                                        @if ($type != null)
                                            <p class="fw-regular mb-0 color-dark">{{ __('Rent') . '/' . __($type) }}</p>
                                        @else
                                            <p class="fw-regular mb-0 color-dark">{{ __('Total Rent') }}</p>
                                        @endif
                                        <span
                                            class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($rent, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                    </li>
                                @endif
                                @if (isset($numberOfDay) && !empty($numberOfDay))
                                    <li class="sub-total d-flex justify-content-between">
                                        @if ($numberOfDay > 1)
                                            <p class="fw-regular mb-0 color-dark">{{ __('Number of Days') }}</p>
                                        @else
                                            <p class="fw-regular mb-0 color-dark">{{ __('Number of Day') }}</p>
                                        @endif
                                        <span class="price">{{ @$numberOfDay }}</span>
                                    </li>
                                @endif

                                <li class="sub-total d-flex justify-content-between">
                                    <p class="fw-regular mb-0 color-dark">{{ __('Services Total') }}</p>
                                    <span
                                        class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($serviceTotal, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                </li>
                                @if (isset($hour) && $hour > 1)
                                    <li class="sub-total d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('Hours') }}</p>
                                        <span class="price">{{ $hour }}</span>
                                    </li>
                                @endif

                                    <li id="discount-li" class="sub-total d-flex justify-content-between">
                                        <p class="fw-regular mb-0 color-dark">{{ __('Discount') }}</p>
                                        <span class="price"
                                            id="discount-amount">{{ $position == 'left' ? $symbol : '' }}{{ $discount > 0 ? $discount : '0.00' }}{{ $position == 'right' ? $symbol : '' }}</span>
                                    </li>

                                <li class="sub-total d-flex justify-content-between">
                                    <p class="fw-regular mb-0 color-dark">{{ __('Subtotal') }}</p>
                                    <span
                                        class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($subtotal, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <p class="fw-regular mb-0 color-dark"> {{ __('Tax') }}
                                        <span dir="ltr">({{ number_format($taxPercentage, 2, '.', '') }}%)</span> </p>
                                    <span id="tax-amount"
                                        class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($taxAmount, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                                </li>
                            </ul>
                            <hr>
                            <div class="total d-flex justify-content-between">
                                <p class="fw-medium mb-0 color-dark">{{ __('Total') }}</p>
                                <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
                                <span id="grand-total-amount"
                                    class="price">{{ $position == 'left' ? $symbol : '' }}{{ number_format($grandTotal, 2, '.', '') }}{{ $position == 'right' ? $symbol : '' }}</span>
                            </div>
                        </div>
                        {{-- Payment Details End --}}

                        <div class="mb-40">
                            <div class="input-inline border radius-sm">
                                <input class="form-control border-0" id="coupon-code"
                                    placeholder="{{ __('Enter Coupon Code') }}" type="text" name="coupon_code"
                                    autocomplete="off">
                                <button class="btn btn-lg btn-primary"
                                    onclick="applyCoupon(event)">{{ __('Apply') }}</button>
                            </div>
                        </div>

                        <div class="order-payment form-block border radius-md mb-30">
                            <h5 class="mb-20">{{ __('Payment Method') }}</h5>
                            <div class="form-group mb-20">
                                <select id="payment-gateway" class="niceselect form-control" name="gateway">
                                    <option value="" selected disabled>{{ __('Select a Payment Gateway') }}
                                    </option>

                                    @if (count($onlineGateways) > 0)
                                        @foreach ($onlineGateways as $onlineGateway)
                                        
                                            <option value="{{ $onlineGateway->keyword }}"
                                                {{ old('gateway') == $onlineGateway->keyword ? 'selected' : '' }}
                                                data-gateway_type="online">

                                                {{$onlineGateway->name}} 

                                            </option>
                                        @endforeach
                                    @endif

                                    @if (count($offlineGateways) > 0)
                                        @foreach ($offlineGateways as $offlineGateway)
                                        @dump($offlineGateway->name)
                                            <option value="{{ $offlineGateway->id }}"
                                                {{ old('gateway') == $offlineGateway->id ? 'selected' : '' }}
                                                data-gateway_type="offline"
                                                data-has_attachment="{{ $offlineGateway->has_attachment }}">
                                                
                                                {{ __($offlineGateway->name) }} 
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('gateway')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                @enderror
                                <div id="payment-gateway-error"></div>
                            </div>

                            <!-----------stripe------------->
                            <div id="stripe-element" class="mb-2 mt-2 d-none">

                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors -->
                            <div id="stripe-errors" role="alert" class="mb-2 text-danger"></div>
                            <!-----------stripe------------->

                            {{-- this code for iyzico --}}
                            <div class="mt-3 d-none" id="iyzico-payment-form">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('First Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="first_name_for_iyzico"
                                                autocomplete="off" placeholder="{{ __('Enter first name') }}"
                                                value="{{ old('first_name_for_iyzico') }}">
                                        </div>
                                        @error('first_name_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Last Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="last_name_for_iyzico"
                                                autocomplete="off" placeholder="{{ __('Enter last name') }}"
                                                value="{{ old('last_name_for_iyzico') }}">
                                        </div>
                                        @error('last_name_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Identity Number') . '*' }}</label>
                                            <input type="text" class="form-control" name="identity_number_for_iyzico"
                                                autocomplete="off" placeholder="{{ __('Enter identity number') }}"
                                                value="{{ old('identity_number_for_iyzico') }}">
                                        </div>
                                        @error('identity_number_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Email Address') . '*' }}</label>
                                            <input type="text" class="form-control" name="email_address_for_iyzico"
                                                placeholder="{{ __('Enter email address') }}"
                                                value="{{ old('email_address_for_iyzico') }}">
                                        </div>
                                        @error('email_address_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Phone Number') . '*' }}</label>
                                            <input type="text" class="form-control" name="phone_number_for_iyzico"
                                                placeholder="{{ __('Enter phone number') }}"
                                                value="{{ old('phone_number_for_iyzico') }}">
                                        </div>
                                        @error('phone_number_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Zip Code') . '*' }}</label>
                                            <input type="text" class="form-control" name="zip_code_for_iyzico"
                                                placeholder="{{ __('Enter zip code') }}"
                                                value="{{ old('zip_code_for_iyzico') }}">
                                        </div>
                                        @error('zip_code_for_iyzico')
                                            <p id="zipcodeForIyzico" class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Address') . '*' }}</label>
                                            <input type="text" class="form-control" name="address_for_iyzico"
                                                placeholder="{{ __('Enter address') }}"
                                                value="{{ old('address_for_iyzico') }}">
                                        </div>
                                        @error('address_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('City') . '*' }}</label>
                                            <input type="text" class="form-control" name="city_for_iyzico"
                                                placeholder="{{ __('Enter city') }}"
                                                value="{{ old('city_for_iyzico') }}">
                                        </div>
                                        @error('city_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Country') . '*' }}</label>
                                            <input type="text" class="form-control" name="country_for_iyzico"
                                                placeholder="{{ __('Enter country') }}"
                                                value="{{ old('country_for_iyzico') }}">
                                        </div>
                                        @error('country_for_iyzico')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @if (session('iyzico_validation_errors'))
                                        <div id="iyzico-validation-errors" class="d-none"></div>
                                    @endif
                                </div>
                            </div>


                            {{-- this code for authorizenet --}}
                            <div class="mt-3 d-none" id="authorizenet-form">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Card Number') . '*' }}</label>
                                            <input type="text" class="form-control" id="cardNumber"
                                                autocomplete="off" placeholder="{{ __('Enter Card Number') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Card Code') . '*' }}</label>
                                            <input type="text" class="form-control" id="cardCode" autocomplete="off"
                                                placeholder="{{ __('Enter Card Code') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Expiry Month') . '*' }}</label>
                                            <input type="text" class="form-control" id="expMonth"
                                                placeholder="{{ __('Enter Expiry Month') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Expiry Year') . '*' }}</label>
                                            <input type="text" class="form-control" id="expYear"
                                                placeholder="{{ __('Enter Expiry Year') }}">
                                        </div>
                                    </div>

                                    <input type="hidden" name="opaqueDataValue" id="opaqueDataValue">
                                    <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor">

                                    <div id="anetErrors"></div>
                                </div>
                            </div>

                            <div class="mt-3 d-none" id="freshpay-form">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Customer Number') . '*' }}</label>
                                            <input type="text" class="form-control" name="freshpay_customer_number"
                                                autocomplete="off" placeholder="{{ __('Enter customer number') }}"
                                                value="{{ old('freshpay_customer_number') }}">
                                        </div>
                                        @error('freshpay_customer_number')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <div class="form-group mb-30">
                                            <label>{{ __('Method') . '*' }}</label>
                                            <select class="form-control" name="freshpay_method">
                                                <option value="" selected disabled>{{ __('Select a method') }}</option>
                                                <option value="airtel" @selected(old('freshpay_method') == 'airtel')>Airtel</option>
                                                <option value="orange" @selected(old('freshpay_method') == 'orange')>Orange</option>
                                                <option value="mpesa" @selected(old('freshpay_method') == 'mpesa')>MPesa</option>
                                            </select>
                                        </div>
                                        @error('freshpay_method')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @foreach ($offlineGateways as $offlineGateway)
                                <div class="offline-gateway-info d-none"
                                    id="{{ 'offline-gateway-' . $offlineGateway->id }}">
                                    @if (!is_null($offlineGateway->short_description))
                                        <div class="form-group mb-4">
                                            <label>{{ __('Description') }}</label>
                                            <p>{{ $offlineGateway->short_description }}</p>
                                        </div>
                                    @endif

                                    @if (!is_null($offlineGateway->instructions))
                                        <div class="form-group mb-4">
                                            <label>{{ __('Instructions') }}</label>
                                            {!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}
                                        </div>
                                    @endif

                                    @if ($offlineGateway->has_attachment == 1)
                                        <div class="form-group mb-4">
                                            <label>{{ __('Attachment') . '*' }}</label>
                                            <br>
                                            <input id="attachment-{{ $offlineGateway->id }}" type="file"
                                                name="attachment[{{ $offlineGateway->id }}]">
                                                <p class="text-warning">{{'*' . __('Attachment image must be') . ' ' . __('jpg / jpeg / png') }}</p>
                                            @error('attachment.' . $offlineGateway->id)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            {{-- offline payment gateway instructions end --}}

                            <div class="text-center">
                                <button class="btn btn-lg btn-primary radius-sm w-100" type="submit"
                                    id="payment-form-btn">
                                    {{ __('Pay now') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout-area end -->

@endsection

@section('custom-script')
    <script type="text/javascript">
        const clientKey = '{{ $anetClientKey }}';
        const loginId = '{{ $anetLoginId }}';
        let stripe_key = "{{ $stripeKey }}";
        window.offlineValidationErrors = @json(session('offline_validation_errors', false));
        window.oldGatewayId = @json(old('gateway', ''));
        // Example JavaScript usage
        const stripeError = @json($stripeError);
        const anetCardError = @json($anetCardError);
        const anetYearError = @json($anetYearError);
        const anetMonthError = @json($anetMonthError);
        const anetExpirationDateError = @json($anetExpirationDateError);
        const anetCvvInvalidError = @json($anetCvvInvalidError);
        const paymentGatewayError = @json($paymentGatewayError);
        const firstNameError = @json($firstNameError);
        const phoneNumberError = @json($phoneNumberError);
        const emailAddressError = @json($emailAddressError);
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="{{ $anetSource }}" charset="utf-8"></script>

    @if (old('gateway') == 'stripe')
        <script>
            $(document).ready(function() {
                $('#stripe-element').removeClass('d-none');
            });
        </script>
    @elseif (old('gateway') == 'freshpay')
        <script>
            $(document).ready(function() {
                $('#freshpay-form').removeClass('d-none');
            });
        </script>
    @endif
    <script>
        var getCouponDataUrl = "{{ route('frontend.coupon.data') }}";
        var loginUrl = "{{ route('user.login') }}";
    </script>
    <script src="{{ asset('assets/frontend/js/space-confirm.js') }}"></script>
@endsection
