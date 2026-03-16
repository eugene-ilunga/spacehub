<!DOCTYPE html>
<html>

<head lang="">
    {{-- required meta tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- title --}}
    <title>{{ __('Invoice') . ' | ' . config('app.name') }}</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ public_path('assets/img/' . $space['favicon']) }}">

    <!-- invoice Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/invoice-2.css') }}">

</head>

<body>

    <div class="invoice-container">

        <!-- header-table -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="header-logo">
                        <h4>
                            @if (!empty($space['logo']) && file_exists(public_path('assets/img/' . $space['logo'])))
                                <img src="{{ public_path('assets/img/' . $space['logo']) }}" alt="logo"
                                    class="uploaded-img-logo">
                            @else
                                <img src="{{ public_path('assets/img/noimage.jpg') }}" alt="logo"
                                    class="uploaded-img-logo">
                            @endif
                        </h4>
                    </div>
                </td>
                <td class="text-right invoice-title">
                    <h4>{{ __('Invoice') }}</h4>
                </td>
            </tr>
        </table>

        <!-- invoice-info-table -->
        <table class="invoice-info-table table-border mb-20">
            <tr>
                <td><strong>{{ __('Date') . ': ' }}</strong>{{ $space['space_type'] == 3 ? $space['created_at'] : $space['booking_date'] }}
                </td>
                <td><strong dir="ltr">{{ __('Booking No') . ': ' }}</strong>{{ $space['booking_number'] }} </td>
            </tr>
        </table>

        <!-- address-table -->
        <table class="address-table clearfix mb-20">
            <tr>
                <td class="address">
                    <p class="fw-bold">{{ __('To') . ': ' }}</p>
                    <p>{{ $space['customer_name'] }}</p>
                    <p>{{ $space['customer_email'] }}</p>
                    <p>{{ $space['customer_phone'] }}</p>
                </td>
                <td class="address text-right">
                    <p class="fw-bold">{{ __('Vendor') . ': ' }}</p>
                    <p>{{ $space['vendor_name'] }}</p>
                    <p>{{ $space['vendor_email'] }}</p>
                    <p>{{ $space['vendor_phone'] }}</p>
                    <p>{{ $space['vendor_address'] }}</p>
                </td>
            </tr>
        </table>
        @php
            $currencySymbol = $space['currency_symbol'];
            $symbolPosition = $space['currency_symbol_position'];

        @endphp

        <!-- product-table -->
        <table class="table-border product-table mb-10">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    @if ($space['space_type'] == 3)
                        {
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        }
                    @endif
                    @if ($space['space_type'] == 1 || $space['space_type'] == 2)
                        <th>{{ __('Start Time') }}</th>
                        <th>{{ __('End Time') }}</th>
                    @endif
                    <th>{{ __('Duration') }}</th>
                    <th>{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $space['name'] }}</td>
                    @if ($space['space_type'] == 3)
                        <td>{{ $space['start_date'] }}</td>
                        <td>{{ $space['end_date'] }}</td>
                    @endif
                    @if ($space['space_type'] == 1 || $space['space_type'] == 2)
                        <td>{{ $space['start_time'] }}</td>
                        <td>{{ $space['end_time'] }}</td>
                    @endif
                    <td>
                        @php
                            $duration = $space['duration'];
                            $isDaily = $space['space_type'] == 3;

                            $unit = $isDaily
                                ? ($duration == 1
                                    ? __('Day')
                                    : __('Days'))
                                : ($duration == 1
                                    ? __('Hour')
                                    : __('Hours'));
                        @endphp

                        {{ $duration . ' ' . $unit }}
                    </td>
                    <td>{{ format_currency($space['amount'], $currencySymbol, $symbolPosition) }}</td>
                </tr>

            </tbody>
        </table>

        <table class="payment-table clearfix">
            <tr>
                <td>
                    <p><span>{{ __('Payment Method') }}:</span> {{ $space['payment_method'] }}</p>
                </td>
                <td class="total-cal text-right">
                    <p class="mb-1"><span>{{ __('Invoice Total') . ': ' }}</span>
                        {{ format_currency($space['total'], $currencySymbol, $symbolPosition) }}</p>

                    @if ($space['discount'] > 0)
                        <p class="mb-1">
                            <span>{{ __('Discount') . ': ' }}</span>{{ format_currency($space['discount'], $currencySymbol, $symbolPosition) }}
                        </p>
                    @endif

                    <p class="mb-1">
                        <span>{{ __('Invoice Subtotal') . ': ' }}</span>{{ format_currency($space['subtotal'], $currencySymbol, $symbolPosition) }}
                    </p>

                    <p class="mb-1"><span>{{ __('Tax') }} ({{ $space['tax_percentage'] }}%):</span>
                        {{ format_currency($space['tax_amount'], $currencySymbol, $symbolPosition) }}</p>
                    <p class="mb-1">
                        <span>{{ __('Received Amount') . ': ' }}</span>{{ format_currency($space['received_amount'], $currencySymbol, $symbolPosition) }}
                    </p>
                    <p class="mb-1"><span>{{ __('Customer Paid') . ': ' }}</span>
                        {{ format_currency($space['amount'], $currencySymbol, $symbolPosition) }}</p>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p class="mb-2">
                {{ __('Thank you for booking with') }} {{ $space['vendor_name'] }} {{ __('through') }}
                <a class="color-primary" href="#">{{ $space['website_title'] }}</a>.
            </p>
            <p class="mb-2">
                {{ __('All amounts shown on this invoice are in') }} {{ $space['currency_text'] }}.
            </p>
        </div>
    </div>
</body>

</html>
