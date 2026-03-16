<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLongTitle">{{ __('Send Request for Feature') }}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="my-checkout-form" action="{{ route('vendor.plan.checkout') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="paymentForFeature" value="{{ @$space->id }}">
                    <input type="hidden" name="space_id" value="{{ @$space->id }}">
                    <input type="hidden" name="seller_id" value="{{ Auth::guard('seller')->user()->id }}">
                    <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">

                    
                    <h3 class="mb-3">{{ __('Promotion List') . '*'}}</h3>
                    <ul class="specification-list mb-0">

                        @foreach ($featuredCharges as $featuredCharge)
                            <li class="list-group-item mb-3">
                                <div class="d-inline">

                                    <input type="radio" class="form-check-input ml-0 feature-radio"
                                        name="feature_charge" id="featureCharge-{{ $featuredCharge->id }}" 
                                        value="{{ $featuredCharge->id }}" {{ var_dump(old('feature_charge')) }}
                                            {{ old('feature_charge') == $featuredCharge->id ? 'checked' : '' }}           
                                        >
                                    <label
                                        for="featureCharge-{{ $featuredCharge->id }}">{{ $featuredCharge->day }}
                                        <span>{{' '. __('days For') . ' '}}</span> {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : ''}}{{ $featuredCharge->price }}{{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : ''}}</label>
                                </div>
                            </li>
                        @endforeach
                        @error('feature_charge')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </ul>

                    <div class="payment-wrapper">
                        <div class="form-group px-0">
                            <h3 class="mb-3">{{ __('Payment Method') . '*' }}</h3>
                            <select name="payment_method" class="niceselect mb-20" id="payment-gateway">
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

                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="instructions" class="text-ltr-rtl"></div>
                        <input type="hidden" name="is_receipt" value="0" id="is_receipt">

                        <div id="stripe-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                        <!-- Used to display form errors -->
                        <div id="stripe-errors" class="pb-2 text-danger d-none text-left" role="alert"></div>

                        {{-- START: Authorize.net Card Details Form --}}
                        <div class="row gateway-details d-none pt-3" id="tab-anet" >
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
                    </div>

                    <button class="btn btn-primary btn-block" type="submit"><b>{{ __('Checkout Now') }}</b></button>
                    
                </form>
            </div>
            @if ($errors->any())
                <div id="validation-errors" class="d-none"></div>
            @endif
        </div>
    </div>
</div>
@php
    
@endphp
@section('script')
    <script src="https://js.stripe.com/v3/"></script>

    <script>
    "use strict";
      let offline = @php echo json_encode($offline) @endphp;
      let getOfflinePaymentUrl = "{{ route('vendor.payment.instructions') }}";
  </script>
   <script src="{{ asset('assets/admin/js/offline-payment.js') }}"></script>

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
        // let offline = @json($offline);
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
