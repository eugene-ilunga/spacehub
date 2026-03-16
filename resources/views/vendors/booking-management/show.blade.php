@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Booking Details') }}</h4>
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
                <a href="#">{{ __('Booking Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('vendor.booking_record.index', ['language' => $defaultLang->code]) }}">{{ __('All  Bookings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Booking Details') }}</a>
            </li>
        </ul>
        <a href="{{ route('vendor.booking_record.index', ['language' => $defaultLang->code]) }}"
            class="btn btn-primary ml-auto">{{ __('Back') }}</a>
    </div>

    <div class="row">
        @php
            $position = $orderInfo->currency_symbol_position;
            $currency = $orderInfo->currency_symbol;
        @endphp

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Booking Information') }}</div>
                </div>
                <div class="card-body">
                    <div class="payment-information">
                        <div class="row mb-2">
                            <div class="col-lg-3">
                                <strong>{{ __('Booking No.') . ' :' }}</strong>
                            </div>

                            <div class="col-lg-9">{{ '#' . $orderInfo->booking_number }}</div>
                        </div>

                        @if ($orderInfo->booking_type != 3)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Booking Date') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ Carbon\Carbon::parse($orderInfo->booking_date)->format('F j, Y') }}</div>
                            </div>
                        @endif
                        @if ($orderInfo->booking_type == 3)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Booking Date') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ Carbon\Carbon::parse($orderInfo->created_at)->format('F j, Y') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Start Date') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ Carbon\Carbon::parse($orderInfo->start_date)->format('F j, Y') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('End Date') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ Carbon\Carbon::parse($orderInfo->end_date)->format('F j, Y') }}</div>
                            </div>
                        @endif

                        @php
                            $timeFormat = $basic->time_format ?? '12h';
                            if ($timeFormat == '12h') {
                                $orderInfo->start_time = \Carbon\Carbon::parse($orderInfo->start_time)->format('h:i A');
                                $orderInfo->end_time = \Carbon\Carbon::parse($orderInfo->end_time)->format('h:i A');
                                $orderInfo->end_time_without_interval = \Carbon\Carbon::parse(
                                    $orderInfo->end_time_without_interval,
                                )->format('h:i A');
                            } else {
                                $orderInfo->start_time = \Carbon\Carbon::parse($orderInfo->start_time)->format('H:i');
                                $orderInfo->end_time = \Carbon\Carbon::parse($orderInfo->end_time)->format('H:i');
                                $orderInfo->end_time_without_interval = \Carbon\Carbon::parse(
                                    $orderInfo->end_time_without_interval,
                                )->format('H:i');
                            }
                        @endphp

                        @if ($orderInfo->booking_type == 1 || $orderInfo->booking_type == 2)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Start Time') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9 ltr">{{ $orderInfo->start_time }}</div>
                            </div>
                            @if ($orderInfo->booking_type == 1)
                                <div class="row mb-2">
                                    <div class="col-lg-3">
                                        <strong>{{ __('End Time') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-9 ltr">{{ $orderInfo->end_time }}</div>
                                </div>
                            @endif
                            @if ($orderInfo->booking_type == 2)
                                <div class="row mb-2">
                                    <div class="col-lg-3">
                                        <strong>{{ __('End Time') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-9 ltr">{{ $orderInfo->end_time_without_interval }}</div>
                                </div>
                            @endif
                        @endif

                        <div class="row mb-2">
                            <div class="col-lg-3">
                                <strong>{{ __('Space') . ' :' }}</strong>
                            </div>

                            <div class="col-lg-9">
                                @if (!empty($orderInfo->space_slug))
                                    <a target="_blank"
                                        href="{{ route('space.details', ['slug' => $orderInfo->space_slug, 'id' => $orderInfo->space_id]) }}">
                                        {{ $orderInfo->space_title }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if (!is_null($orderInfo->number_of_guest))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Number Of Guest') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ $orderInfo->number_of_guest }}
                                </div>
                            </div>
                        @endif
                        @if (!is_null($services))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Services') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    <a href="#" data-toggle="modal" data-target="#createModal"
                                        class="btn btn-success btn-sm ">
                                        {{ __('View') }}</a>
                                </div>
                            </div>
                        @endif

                        @if ($orderInfo->service_total != 0)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Services Total') }} :
                                    </strong>
                                </div>
                                <div class="col-lg-9 ltr">
                                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->service_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                                </div>
                            </div>
                        @endif

                        @if ($rent != 0)
                            <div class="row mb-2">
                                @if ($type != null)
                                    <div class="col-lg-3">
                                        <strong>{{ __('Rent') . '/' . __($type) }} :
                                        </strong>
                                    </div>
                                @else
                                    <div class="col-lg-3">
                                        <strong>{{ __('Total Rent') }} :
                                        </strong>
                                    </div>
                                @endif
                                <div class="col-lg-9 ltr">
                                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $rent }}{{ $position == 'right' ? ' ' . $currency : '' }}
                                </div>
                            </div>
                        @endif
                        @if ($space_type == 3)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    @if ($orderInfo->number_of_day > 1)
                                        <strong>{{ __('Number of Days') }} :
                                        </strong>
                                    @else
                                        <strong>{{ __('Number of Day') }} :
                                        </strong>
                                    @endif
                                </div>

                                <div class="col-lg-9">
                                    {{ $orderInfo->number_of_day }}
                                </div>
                            </div>
                        @endif
                        @if ($space_type == 2)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    @if ($orderInfo->custom_hour > 1)
                                        <strong>{{ __('Hours') }} :
                                        </strong>
                                    @else
                                        <strong>{{ __('Hour') }} :
                                        </strong>
                                    @endif
                                </div>
                                <div class="col-lg-9">
                                    {{ $orderInfo->custom_hour }}
                                </div>
                            </div>
                        @endif

                        @if ($orderInfo->discount != 0)
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Discount') }} (<i class="fas fa-minus text-danger text-small"></i>) :
                                    </strong>
                                </div>
                                <div class="col-lg-9 ltr">
                                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                                </div>
                            </div>
                        @endif
                        @if (!is_null($orderInfo->grand_total))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Received Amount') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9 ltr">
                                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->sub_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                                </div>
                            </div>
                        @endif

                        @if (!is_null($orderInfo->tax))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Tax') }} <span dir="ltr">({{ $orderInfo->tax_percentage . '%' }})</span> <i
                                            class="fas fa-plus text-danger text-small"></i> : </strong>
                                </div>

                                <div class="col-lg-9">
                                    <span dir="ltr">
                                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}</span>
                                    {{ __('(Received by admin)') }}
                                </div>
                            </div>
                        @endif
                        @if (!is_null($orderInfo->grand_total))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Customer Paid') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9 ltr">
                                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->grand_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                                </div>
                            </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col-lg-3">
                                <strong>{{ __('Paid via') . ' :' }}</strong>
                            </div>

                            <div class="col-lg-9">
                                @if (is_null($orderInfo->payment_method))
                                    -
                                @else
                                    {{ __($orderInfo->payment_method) }}
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3">
                                <strong>{{ __('Payment Status') . ' :' }}</strong>
                            </div>

                            <div class="col-lg-9">
                                @if ($orderInfo->gateway_type == 'online')
                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                                @else
                                    @if ($orderInfo->payment_status == 'completed')
                                        <span class="badge badge-success">{{ __('Completed') }}</span>
                                    @elseif ($orderInfo->payment_status == 'pending')
                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <strong>{{ __('Booking Status') . ' :' }}</strong>
                            </div>

                            <div class="col-lg-9">
                                <span
                                    class="badge @if ($orderInfo->booking_status == 'pending') badge-primary @elseif ($orderInfo->booking_status == 'approved') badge-success @elseif ($orderInfo->booking_status == 'rejected') badge-danger @endif">{{ __(ucfirst($orderInfo->booking_status)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ __('Customer Information') }}
                    </div>
                </div>

                <div class="card-body">
                    <div class="payment-information">
                        @if (!empty($orderInfo->customer_name))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Name') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">
                                    {{ $orderInfo->customer_name ?? '' }}
                                </div>
                            </div>
                        @endif
                        @if (!empty($orderInfo->customer_email))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Email') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">{{ $orderInfo->customer_email ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->phone_number))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Phone Number') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-9">{{ $userInfo->phone_number ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->city))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('City') . ' :' }}</strong>
                                </div>
                                <div class="col-lg-9">{{ $userInfo->city ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->state))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('State') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-9">{{ $userInfo->state ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->zip_code))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Zip code') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-9">{{ $userInfo->zip_code ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->address))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Address') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-9">{{ $userInfo->address ?? '' }}</div>
                            </div>
                        @endif
                        @if (!empty($userInfo->country))
                            <div class="row mb-2">
                                <div class="col-lg-3">
                                    <strong>{{ __('Country') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-9">{{ $userInfo->country ?? '' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('vendors.booking-management.service-details')
@endsection
