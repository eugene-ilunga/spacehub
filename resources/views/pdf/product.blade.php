<!DOCTYPE html>
<html>

<head lang="">
    {{-- required meta tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- title --}}
    <title>{{ __('Product Invoice') . ' | ' . config('app.name') }}</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

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
                            @if (!empty($invoiceData['logo']) && file_exists(public_path('assets/img/' . $invoiceData['logo'])))
                                <img src="{{ public_path('assets/img/' . $invoiceData['logo']) }}" alt="logo"
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
            <tr>
                <td><strong>{{ __('Date') . ': ' }}</strong>{{ $invoiceData['order_date'] }}</td>
                <td class="text-right"><strong>{{ __('Booking No') . ': ' }}</strong>{{ $invoiceData['order_number'] }}
                </td>
            </tr>
            </tr>
        </table>

        <!-- address-table -->
        <table class="address-table clearfix mb-20">
            <tr>
                <td class="address">
                    <p class="fw-bold">{{ __('To') . ': ' }}</p>
                    <p>{{ $invoiceData['billing_name'] }}</p>
                    <p>{{ $invoiceData['billing_email'] }}</p>
                    <p>{{ $invoiceData['billing_phone'] }}</p>
                </td>
                <td class="address text-right">
                    <p class="fw-bold">{{ __('Author') . ': ' }}</p>
                    <p>{{ $invoiceData['admin_name'] }}</p>
                    <p>{{ $invoiceData['admin_email'] }}</p>
                    <p>{{ $invoiceData['admin_phone'] }}</p>
                    <p>{{ $invoiceData['admin_address'] }}</p>
                </td>
            </tr>
        </table>

        <!-- product-table -->
        <table class="table-border product-table mb-10">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Qty') }}</th>
                    <th>{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceData['products'] as $product)
                    <tr>
                        <td>{{ $product['title'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>
                            @if ($invoiceData['currency_symbol_position'] == 'left')
                                {{ $invoiceData['currency_symbol'] }}{{ $product['price'] }}
                            @else
                                {{ $product['price'] }}{{ $invoiceData['currency_symbol'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="payment-table clearfix">
            <tr>
                <td>
                    <p><span>{{ __('Payment Method') }}:</span> {{ $invoiceData['payment_method'] }}</p>
                </td>
                <td class="total-cal text-right">
                    <p class="mb-1">
                        <span>{{ __('Invoice Subtotal') . ': ' }}</span>
                        @if ($invoiceData['currency_symbol_position'] == 'left')
                            {{ $invoiceData['currency_symbol'] }}{{ number_format($invoiceData['sub_total'], 2) }}
                        @else
                            {{ number_format($invoiceData['sub_total'], 2) }}{{ $invoiceData['currency_symbol'] }}
                        @endif
                    </p>
                    @if ($invoiceData['discount'] > 0)
                        <p class="mb-1">
                            <span>{{ __('Discount') . ': ' }}</span>
                            @if ($invoiceData['currency_symbol_position'] == 'left')
                                {{ $invoiceData['currency_symbol'] }}{{ number_format($invoiceData['discount'], 2) }}
                            @else
                                {{ number_format($invoiceData['discount'], 2) }}{{ $invoiceData['currency_symbol'] }}
                            @endif
                        </p>

                    @endif

                    <p class="mb-1">
                        <span>{{ __('Tax') }} ({{ number_format($invoiceData['tax_percentage'], 2) }}%):</span>
                        @if ($invoiceData['currency_symbol_position'] == 'left')
                            {{ $invoiceData['currency_symbol'] }}{{ number_format($invoiceData['tax_amount'], 2) }}
                        @else
                            {{ number_format($invoiceData['tax_amount'], 2) }}{{ $invoiceData['currency_symbol'] }}
                        @endif
                    </p>

                    <p class="mb-1">
                        <span>{{ __('Invoice Total') . ': ' }}</span>
                        @if ($invoiceData['currency_symbol_position'] == 'left')
                            {{ $invoiceData['currency_symbol'] }}{{ number_format($invoiceData['grand_total'], 2) }}
                        @else
                            {{ number_format($invoiceData['grand_total'], 2) }}{{ $invoiceData['currency_symbol'] }}
                        @endif
                    </p>

                    <p class="mb-1">
                        <span>{{ __('Customer Paid') . ': ' }}</span>
                        @if ($invoiceData['currency_symbol_position'] == 'left')
                            {{ $invoiceData['currency_symbol'] }}{{ number_format($invoiceData['customer_paid'], 2) }}
                        @else
                            {{ number_format($invoiceData['customer_paid'], 2) }}{{ $invoiceData['currency_symbol'] }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p class="mb-2">{{ __('Thank you for your purchase from') }} <a class="color-primary"
                    href="#">{{ $invoiceData['website_title'] }}</a>.</p>
            <p class="mb-2">{{ __('All amounts shown on this invoice are in') }} {{ $invoiceData['currency_text'] }}
                .</p>
        </div>
    </div>
</body>

</html>
