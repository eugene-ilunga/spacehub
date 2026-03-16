<!DOCTYPE html>
<html>

<head lang="{{ $language->code }}" @if ($language->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ __('Invoice') . ' | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- css files --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
</head>

<body>
  <div class="service-order-invoice my-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="logo text-center mdf_3432">
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="website logo">
          </div>

          <div class="mb-3">
            <h2 class="text-center">
              {{ __('SPACE BOOKING INVOICE') }}
            </h2>
          </div>

          @php
            $position = $orderInfo->currency_text_position;
            $currency = $orderInfo->currency_text;
          @endphp

          <div class="row">
            <div class="col">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th>{{ __('Booking No') }}</th>
                    <td>{{ '#' . $orderInfo->booking_number ?? '' }}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Booking Date') }}</th>
                    <td>{{ date_format($orderInfo->created_at, 'M d, Y') }}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Customer Name') }}</th>
                    <td>{{ $orderInfo->customer_name ?? '' }}</td>
                  </tr>
                  <tr>
                    <th>{{ __('Vendor') }}</th>
                    <td>
                      @if ($orderInfo->seller_id != 0)
                        @if ($orderInfo->seller)
                          <a target="_blank"
                            href="{{ route('frontend.seller.details', @$orderInfo->seller->username) }}">{{ @$orderInfo->seller->username }}</a>
                        @endif
                      @else
                        <span class="badge badge-success">{{ __('admin') }}</span>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Customer Email') }}</th>
                    <td>{{ @$orderInfo->customer_email}}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Space') }}</th>
                    <td>{{ @$spaceTitle->title}}</td>
                  </tr>

                  @if (!is_null($orderInfo->tax ?? ''))
                    <tr>
                      <th>{{ __('Tax') }} ({{ $orderInfo->tax_percentage ?? '' . '%' }}) <i
                          class="fas fa-plus text-danger text-small"></i></th>
                      <td>
                        @if (is_null($orderInfo->tax ?? ''))
                          {{ __('Price Requested') }}
                        @else
                          {{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice(number_format($orderInfo->tax ?? '', 2)) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        @endif
                      </td>
                    </tr>
                  @endif

                  <tr>
                    <th>{{ __('Total') }}</th>
                    <td>
                      @if (is_null($orderInfo->grand_total))
                        {{ __('Price Requested') }}
                      @else
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice(number_format($orderInfo->grand_total, 2)) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Payment Method') }}</th>
                    <td>
                      @if (is_null($orderInfo->payment_method))
                        {{ __('None') }}
                      @else
                        {{ $orderInfo->payment_method }}
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Payment Status') }}</th>
                    <td>{{ ucfirst($orderInfo->payment_status) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
