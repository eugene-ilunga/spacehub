<div class="modal myModal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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
        <form id="my-checkout-form" action="{{ route('admin.space_management.space.checkout_for_featured_status') }}"
          method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="space_id" value="{{ @$space->id }}">
          <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">

          <div class="card-body p-0">
            <h3 class="mb-3">{{ __('Promotion List') . '*' }}</h3>
            <ul class="specification-list">
              @foreach ($featuredCharges as $key => $featuredCharge)
                <li>

                  <div class="d-inline">
                    <input type="radio" class="mr-1 feature-radio" name="feature_charge"
                      id="feature-{{ $featuredCharge->id }}" value="{{ $featuredCharge->id }}"
                      {{ $key == 0 ? 'checked' : '' }}>
                    <label for="feature-{{ $featuredCharge->id }}">{{ $featuredCharge->day }}
                      {{ __('days For') }} {{ $featuredCharge->price }}$</label>
                  </div>

                </li>
              @endforeach
            </ul>

            <div class="payment-wrapper">
              <div class="form-group px-0">
                <h3 class="mb-3">{{ __('Payment Method') . '*' }}</h3>
                <select name="payment_method" class="niceselect mb-20" id="payment-gateway" required>
                  <option value="" disabled selected>{{ __('Select a Payment Method') }}
                  </option>
                  @foreach ($payment_methods as $payment_method)
                    <option value="{{ $payment_method->name }}"
                      {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                      {{ __($payment_method->name) }}</option>
                  @endforeach
                </select>
              </div>
              
              <div id="instructions" class="text-left text-ltr-trl"></div>
              <input type="hidden" name="is_receipt" value="0" id="is_receipt">

              <div id="stripe-element">
                <!-- A Stripe Element will be inserted here. -->
              </div>
              <!-- Used to display form errors -->
              <div id="stripe-errors" class="pb-2 text-danger text-left" role="alert"></div>
            </div>

            <button class="btn btn-primary btn-block" type="submit"><b>{{ __('Checkout Now') }}</b></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

