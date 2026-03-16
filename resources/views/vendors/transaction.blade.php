@extends('vendors.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Transactions') }}</h4>
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
                <a href="#">{{ __('Transactions') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ __('Transactions') }}</div>
                        </div>
                        <div class="col-lg-4">
                            <form action="" method="get">
                                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                                <input type="text" value="{{ request()->input('transaction_id') }}" name="transaction_id"
                                    placeholder="{{ __('Enter transaction id') }}" class="form-control">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($transactions) == 0)
                                <h3 class="text-center mt-3">{{ __('NO TRANSACTION FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Transaction ID') }}</th>
                                                <th scope="col">{{ __('Transaction Type') }}</th>
                                                <th scope="col">{{ __('Payment Method') }}</th>
                                                <th scope="col">{{ __('Pre Balance') }}</th>
                                                <th scope="col">{{ __('Amount') }}</th>
                                                <th scope="col">{{ __('After Balance') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td>#{{ $transaction->transcation_id }}</td>
                                                    <td>
                                                        @if ($transaction->transcation_type == 1)
                                                            {{ __('Space Booking') }}
                                                        @elseif ($transaction->transcation_type == 2)
                                                            {{ __('Withdraw') }}
                                                        @elseif ($transaction->transcation_type == 3)
                                                            {{ __('Balance Added') }}
                                                        @elseif ($transaction->transcation_type == 4)
                                                            {{ __('Balance Subtracted') }}
                                                        @elseif ($transaction->transcation_type == 5)
                                                            {{ __('Package Purchase') }}
                                                        @elseif ($transaction->transcation_type == 6)
                                                            {{ __('Space Feature') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($transaction->transcation_type == 2)
                                                            @php
                                                                $method = $transaction->method()->first();
                                                            @endphp
                                                            @if ($method)
                                                                {{ __($method->name) }}
                                                            @else
                                                                {{ '-' }}
                                                            @endif
                                                        @else
                                                            {{ $transaction->payment_method != null ? __($transaction->payment_method) : '-' }}
                                                        @endif
                                                    </td>

                                                    <td class="ltr">
                                                        @if ($transaction->pre_balance != null)
                                                            {{ $transaction->currency_symbol_position == 'left' ? $transaction->currency_symbol : '' }}
                                                            {{ $transaction->pre_balance }}
                                                            {{ $transaction->currency_symbol_position == 'right' ? $transaction->currency_symbol : '' }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>

                                                    <td class="ltr">
                                                        @if (
                                                            $transaction->transcation_type == 2 ||
                                                                $transaction->transcation_type == 4 ||
                                                                $transaction->transcation_type == 5 ||
                                                                $transaction->transcation_type == 6)
                                                            <span class="text-danger">{{ '(-)' }}</span>
                                                        @else
                                                            <span class="text-success">{{ '(+)' }}</span>
                                                        @endif

                                                        {{ $transaction->currency_symbol_position == 'left' ? $transaction->currency_symbol : '' }}
                                                        {{ $transaction->grand_total - $transaction->tax }}
                                                        {{ $transaction->currency_symbol_position == 'right' ? $transaction->currency_symbol : '' }}
                                                    </td>
                                                    <td class="ltr">
                                                        @if ($transaction->after_balance != null)
                                                            {{ $transaction->currency_symbol_position == 'left' ? $transaction->currency_symbol : '' }}
                                                            {{ $transaction->after_balance }}
                                                            {{ $transaction->currency_symbol_position == 'right' ? $transaction->currency_symbol : '' }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($transaction->payment_status == 'completed')
                                                            <span class="badge badge-success">{{ __('Paid') }}</span>
                                                        @elseif($transaction->payment_status == 'declined')
                                                            <span class="badge badge-warning">{{ __('Declined') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($transaction->transcation_type == 1)
                                                            @php
                                                                $booking = \App\Models\SpaceBooking::where(
                                                                    'booking_number',
                                                                    $transaction->booking_number,
                                                                )->select('invoice')->first();
                                                            @endphp
                                                            @if ($booking)
                                                                <a target="_blank" class="btn btn-secondary btn-sm mr-1"
                                                                    href="{{ asset('assets/file/invoices/space/' . $booking->invoice) }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endif
                                                        @elseif ($transaction->transcation_type == 6)
                                                            @php
                                                                $featureBooked = \App\Models\SpaceFeature::where('booking_number', $transaction->booking_number)->select('invoice')->first();
                                                            @endphp
                                                            @if ($featureBooked)
                                                                <a target="_blank" class="btn btn-secondary btn-sm mr-1" href="{{ asset('assets/file/invoices/space/featured/' . $featureBooked->invoice) }}"> <i class="fas fa-eye"></i></a>
                                                                
                                                            @endif
                                                        
                                                        @elseif ($transaction->transcation_type == 5)
                                                            @php
                                                                $lastMembership = \App\Models\Membership::where('transaction_id', $transaction->booking_number)->select('invoice')->first();
                                                            @endphp
                                                            @if ($lastMembership)
                                                                <a target="_blank" class="btn btn-secondary btn-sm mr-1" href="{{ asset('assets/file/invoices/membership/'. $lastMembership->invoice) }}"> <i class="fas fa-eye"></i></a>
                                                            @endif
                                                        @endif
                                                        
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="pl-3 pr-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $transactions->appends([
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
