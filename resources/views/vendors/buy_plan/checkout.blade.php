@extends('vendors.layout')
@php
    Config::set('app.timezone', App\Models\BasicSettings\Basic::first()->timezone);
@endphp
@section('content')
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if (!empty($membership) && ($membership->package->term == 'lifetime' || $membership->is_trial == 1))
        <div class="alert bg-warning alert-warning text-white text-center">
            <h3>{{ __('If you purchase this package') }} <strong class="text-dark">({{ __($package->title) }})</strong>,
                {{ __('then your current package') }} <strong class="text-dark">{{ __($membership->package->title) }}@if ($membership->is_trial == 1)
                        <span class="badge badge-secondary">{{ __('Trial') }}</span>
                    @endif
                </strong>
                {{ __('will be replaced immediately') }}
            </h3>
        </div>
    @endif
    <div class="row justify-content-center align-items-center mb-1">
        <div class="col-md-1 pl-md-0">
        </div>
        <div class="col-md-6 pl-md-0 pr-md-0">
            <div class="card card-pricing card-pricing-focus card-secondary">
                <form id="my-checkout-form" action="{{ route('vendor.plan.checkout', ['language' => $defaultLang->code]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="paymentForPackage" value="{{ $payment_for }}">
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="seller_id" value="{{ Auth::guard('seller')->user()->id }}">
                    <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">
                    <div class="card-header">
                        <h4 class="card-title">{{ __($package->title) }}</h4>
                        <div class="card-price">
                            <span
                                class="price">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>

                            <span class="text">/{{ __($package->term) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="specification-list">
                            <li>
                                <span class="name-specification">{{ __('Membership') }}</span>
                                <span class="status-specification">{{ __('Yes') }}</span>
                            </li>
                            <li>
                                <span class="name-specification">{{ __('Start Date') }}</span>
                                @if (
                                    (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                        (!empty($membership) && $membership->is_trial == 1))
                                    <input type="hidden" name="start_date"
                                        value="{{ \Illuminate\Support\Carbon::yesterday()->format('d-m-Y') }}">
                                    <span
                                        class="status-specification">{{ \Illuminate\Support\Carbon::today()->format('d-m-Y') }}</span>
                                @else
                                    <input type="hidden" name="start_date"
                                        value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}">
                                    <span
                                        class="status-specification">{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}</span>
                                @endif
                            </li>
                            <li>
                                <span class="name-specification">{{ __('Expire Date') }}</span>
                                <span class="status-specification">
                                    @if ($package->term == 'monthly')
                                        @if (
                                            (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                                (!empty($membership) && $membership->is_trial == 1))
                                            {{ \Illuminate\Support\Carbon::parse(now())->addMonth()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Illuminate\Support\Carbon::parse(now())->addMonth()->format('d-m-Y') }}">
                                        @else
                                            {{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addMonth()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addMonth()->format('d-m-Y') }}">
                                        @endif
                                    @elseif($package->term == 'lifetime')
                                        {{ __('Lifetime') }}
                                        <input type="hidden" name="expire_date"
                                            value="{{ \Illuminate\Support\Carbon::maxValue()->format('d-m-Y') }}">
                                    @else
                                        @if (
                                            (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                                (!empty($membership) && $membership->is_trial == 1))
                                            {{ \Illuminate\Support\Carbon::parse(now())->addYear()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Illuminate\Support\Carbon::parse(now())->addYear()->format('d-m-Y') }}">
                                        @else
                                            {{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addYear()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addYear()->format('d-m-Y') }}">
                                        @endif
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span class="name-specification">{{ __('Total Cost') }}</span>
                                <input type="hidden" name="price" value="{{ $package->price }}">
                                <span class="status-specification">
                                    {{ $package->price == 0 ? __('Free') : format_price($package->price) }}
                                </span>
                            </li>
                            @if ($package->price != 0)
                                <li>
                                    <div class="form-group px-0">
                                        <label class="text-white">{{ __('Payment Method') }}</label>

                                        <select name="payment_method" class="form-control input-solid" id="payment-gateway">
                                            <option value="" disabled selected>{{ __('Select a Payment Method') }}
                                            </option>
                                            @foreach ($payment_methods as $payment_method)
                                                <option value="{{ $payment_method->name }}"
                                                    data-attachment="{{ @$payment_method->has_attachment }}"
                                                    data-payment_type="{{ @$payment_method->payment_type }}"
                                                    {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                                                    {{ __($payment_method->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('payment_method')
                                        <h4 class="text-danger ">{{ $message }}</h4>
                                    @enderror
                                </li>
                            @endif
                            <div id="instructions" class="text-left-instructions"></div>
                            <input type="hidden" name="is_receipt" value="0" id="is_receipt">

                            <div id="stripe-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors -->
                            <div id="stripe-errors" class="pb-2 text-danger text-left" role="alert"></div>

                            {{-- START: Authorize.net Card Details Form --}}
                            <div class="row gateway-details d-none pt-3" id="tab-anet">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <input class="form-control" type="text" id="anetCardNumber"
                                            placeholder="Card Number" disabled />
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="anetExpMonth"
                                            placeholder="Expire Month" disabled />
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="anetExpYear"
                                            placeholder="Expire Year" disabled />
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="anetCardCode"
                                            placeholder="Card Code" disabled />
                                    </div>
                                </div>
                                <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
                                <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
                                <ul id="anetErrors" class="d-none"></ul>
                            </div>
                            {{-- END: Authorize.net Card Details Form --}}

                            <div class="row gateway-details d-none pt-3" id="tab-freshpay">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <input class="form-control" type="text" name="freshpay_customer_number"
                                            placeholder="{{ __('Customer Number') }}" value="{{ old('freshpay_customer_number') }}"
                                            disabled />
                                        @error('freshpay_customer_number')
                                            <p class="text-danger mt-1 mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <select class="form-control" name="freshpay_method" disabled>
                                            <option value="" selected disabled>{{ __('Select Method') }}</option>
                                            <option value="airtel" @selected(old('freshpay_method') == 'airtel')>Airtel</option>
                                            <option value="orange" @selected(old('freshpay_method') == 'orange')>Orange</option>
                                            <option value="mpesa" @selected(old('freshpay_method') == 'mpesa')>MPesa</option>
                                        </select>
                                        @error('freshpay_method')
                                            <p class="text-danger mt-1 mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-light btn-block" type="submit"><b>{{ __('Checkout Now') }}</b></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    

    {{-- START: Authorize.net Scripts --}}
    @php
        $anet = App\Models\PaymentGateway\OnlineGateway::where('keyword', 'authorize.net')->first();
        $anerInfo = $anet->convertAutoData();
        $anetTest = $anerInfo['sandbox_status'];

        if ($anetTest == 1) {
            $anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
        } else {
            $anetSrc = 'https://js.authorize.net/v1/Accept.js';
        }
    @endphp
    <script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
    {{-- END: Authorize.net Scripts --}}

    <script>

        let payemnt_instruction_ulr = "{{ route('vendor.payment.instructions') }}";
        let offline = @json($offline);
        let stripe_key = "{{ $stripe_key }}";
        let public_key = "{{ $anerInfo['public_client_key'] }}";
        let login_id = "{{ $anerInfo['api_login_id'] }}";
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
    
    <script src="{{ asset('assets/admin/js/seller-checkout.js') }}"></script>
@endsection
