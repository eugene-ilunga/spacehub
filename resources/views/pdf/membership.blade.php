<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Invoice') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/invoice-2.css') }}">
</head>

<body>
    <div class="invoice-container">

        <!-- header-table -->
        <table class="header-table mb-20">
            <tr>
                <td>
                    @if (!empty($data['logo']) && file_exists(public_path('assets/img/' . $data['logo'])))
                        <img loading="lazy" src="{{ public_path('assets/img/' . $data['logo']) }}" height="40"
                            class="d-inline-block">
                    @else
                        <img loading="lazy" src="{{ public_path('assets/img/noimage.jpg') }}" height="40"
                            class="d-inline-block">
                    @endif
                </td>
                <td class="text-right strong invoice-heading">{{ __('INVOICE') }}</td>
            </tr>
        </table>

        <!-- address-table -->
        <table class="address-table clearfix mb-20">
            <tr>
                <td class="address">
                    <p class="fw-bold">{{ __('Bill to') . ':' }}</p>
                    <p><strong>{{ __('Username') . ':' }} </strong>{{ $data['username'] }}</p>
                    <p><strong>{{ __('Email') . ':' }} </strong> {{ $data['email'] }}</p>
                    <p><strong>{{ __('Phone') . ':' }} </strong> {{ $data['phone'] }}</p>
                </td>

                <td class="address text-right">
                    <p class="strong ">{{ __('Order Details') . ': ' }}</p>
                    <p class=" small"><strong>{{ __('Order ID') . ': ' }}</strong> #{{ $data['order_id'] }}</p>
                    <p class=" small"><strong>{{ __('Total') . ': ' }}</strong>
                        {{ $data['base_currency_text_position'] == 'left' ? $data['base_currency_text'] : '' }}
                        {{ $data['amount'] }}
                        {{ $data['base_currency_text_position'] == 'right' ? $data['base_currency_text'] : '' }}</p>


                    <p class=" small"><strong>{{ __('Payment Method') . ': ' }}</strong>
                        {{ __($data['payment_method']) }}</p>


                    <p class=" small"><strong>{{ __('Payment Status') . ': ' }}</strong>{{ __('Completed') }}</p>

                    <p class=" small"><strong>{{ __('Order Date') . ': ' }}</strong>
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

                    @if (isset($data['purpose']) && $data['purpose'] == 'feature')
                        <p class=" small"><strong>{{ __('Days') . ': ' }}</strong>
                            {{ $data['day'] }}</p>
                    @endif

                </td>
            </tr>
        </table>

        <!-- order-table -->
        <table class="table-border order-table mb-20">
            <thead>
                <tr class="info-titles">
                    <th width="24%">
                        @if (isset($data['purpose']) && $data['purpose'] == 'feature')
                            {{ __('Space Title') }}
                        @else
                            {{ __('Package Title') }}
                        @endif
                    </th>
                    <th width="19%">{{ __('Start Date') }}</th>
                    <th width="19%">{{ __('Expire Date') }}</th>
                    <th width="19%">{{ __('Currency') }}</th>
                    <th width="19%">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td class="text-left">
                        @if (isset($data['purpose']) && $data['purpose'] == 'feature')
                            {{ $data['space_title'] }}
                        @else
                            {{ __($data['package_title']) }}
                        @endif
                    </td>
                    <td>
                        @if (isset($data['purpose']) && $data['purpose'] == 'feature' && $data['start_date'] == null)
                            {{ __('Not Started') }}
                        @else
                            {{ $data['start_date'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($data['purpose']) && $data['purpose'] == 'feature' && $data['expire_date'] == null)
                            {{ __('Not Activated') }}
                        @else
                            {{ \Carbon\Carbon::parse($data['expire_date'])->format('Y') == '9999' ? __('Lifetime') : $data['expire_date'] }}
                        @endif
                    </td>
                    <td>{{ $data['base_currency_text'] }}</td>
                    <td>
                        {{ $data['amount'] == 0 ? __('Free') : $data['amount'] }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td class="text-right regards">{{ __('Thanks & Regards') . ',' }}</td>
            </tr>
            <tr>
                <td class="text-right strong regards">{{ __($data['website_title']) }}</td>
            </tr>
        </table>

    </div>

</body>

</html>
