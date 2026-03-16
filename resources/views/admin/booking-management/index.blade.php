@extends('admin.layout')

@section('content')

    @php
        $bookingStatusMap = config('status_maps.booking_records');
        $paymentStatusMap = config('status_maps.payment_statuses');
        $editableBookingStatuses = config('status_maps.editable_booking_statuses');
        $editablePaymentStatuses = config('status_maps.editable_payment_statuses');

        $currentBookingStatus = request()->input('booking_status', '');
        $currentPaymentStatus = request()->input('payment_status', '');

        $bookingStatusData = $bookingStatusMap[$currentBookingStatus] ?? $bookingStatusMap[''];
        $paymentStatusData = $paymentStatusMap[$currentPaymentStatus] ?? $paymentStatusMap[''];
    @endphp

    <div class="page-header">
        <h4 class="page-title">{{ __($bookingStatusData['title']) }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __('Bookings & Requests') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a href="#">{{ __('Booking Management') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __($bookingStatusData['title']) }}</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <form id="searchForm" action="{{ route('admin.booking_record.index') }}" method="GET">
                                <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Booking Number') }}</label>
                                            <input name="booking_number" type="text" class="form-control"
                                                placeholder="{{ __('Search Here') . '...' }}" autocomplete="off"
                                                value="{{ !empty(request()->input('booking_number')) ? request()->input('booking_number') : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Customer Name') }}</label>
                                            <input name="customer_name" type="text" class="form-control"
                                                placeholder="{{ __('Enter Name') }}" autocomplete="off"
                                                value="{{ !empty(request()->input('customer_name')) ? request()->input('customer_name') : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Vendor') }}</label>
                                            <select class="form-control mdb_343 select2" name="seller"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('seller')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="admin" @selected(request()->input('seller') == 'admin')>
                                                    {{ __('admin') }}
                                                </option>
                                                @foreach ($sellers as $seller)
                                                    <option @selected(request()->input('seller') == $seller->id) value="{{ $seller->id }}">
                                                        {{ $seller->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Payment') }}</label>
                                            <select class="form-control mdb_343" name="payment_status"
                                                onchange="this.form.submit()">
                                                @foreach ($paymentStatusMap as $value => $status)
                                                    <option value="{{ $value }}" @selected(request()->input('payment_status') == $value)>
                                                        {{ __($status['dropdown']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Booking') }}</label>
                                            <select class="form-control mdb_343" name="booking_status"
                                                onchange="this.form.submit()">
                                                @foreach ($bookingStatusMap as $value => $status)
                                                    <option value="{{ $value }}" @selected(request()->input('booking_status') == $value)>
                                                        {{ __($status['dropdown']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-2">
                            <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right card-header-button"
                                data-href="{{ route('admin.booking_record.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($bookings) === 0)
                                <h3 class="text-center mt-3">{{ __('NO BOOKING RECORDS FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Booking No.') }}</th>
                                                <th scope="col">{{ __('Customer Name') }}</th>
                                                <th scope="col">{{ __('Vendor') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Total Price') }}</th>
                                                <th scope="col">{{ __('Paid via') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>
                                                <th scope="col">{{ __('Booking Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $booking)
                                                @php
                                                    $bookingStatusData =
                                                        $bookingStatusMap[$booking->booking_status] ??
                                                        $bookingStatusMap['rejected'];
                                                    $paymentStatusData =
                                                        $paymentStatusMap[$booking->payment_status] ??
                                                        $paymentStatusMap['rejected'];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $booking->id }}">
                                                    </td>
                                                    <td>{{ '#' . $booking->booking_number }}</td>

                                                    @php
                                                        $customerName = $booking->customer_name;
                                                        $customer_id = $booking->user_id;
                                                    @endphp

                                                    <td>
                                                        <a
                                                            href="{{ route('admin.user_management.user.details', ['id' => $customer_id, 'language' => $defaultLang->code]) }}">
                                                            {{ @$customerName }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if (!is_null($booking->seller_id))
                                                            <a
                                                                href="{{ route('admin.end-user.vendor.details', ['id' => $booking->seller_id, 'language' => $defaultLang->code]) }}">{{ @$booking->seller->username }}</a>
                                                        @else
                                                            <span class="badge badge-success">{{ __('admin') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($booking->space_slug))
                                                            <a target="_blank"
                                                                href="{{ route('space.details', ['slug' => $booking->space_slug, 'id' => $booking->space_id]) }}">
                                                                {!! truncate_text(@$booking->space_title, 90) !!}
                                                            </a>
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (is_null($booking->grand_total))
                                                            {{ __('Requested') }}
                                                        @else
                                                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->grand_total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ __($booking->payment_method) ?? '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($booking->gateway_type == 'online' && empty($booking->booked_by))
                                                            <span class="badge badge-success">{{ __('Completed') }}</span>
                                                        @else
                                                            @if (in_array($booking->payment_status, $editablePaymentStatuses))
                                                                <form id="paymentStatusForm-{{ $booking->id }}"
                                                                    class="d-inline-block"
                                                                    action="{{ route('admin.booking_record.update_payment_status', ['id' => $booking->id]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select
                                                                        class="form-control form-control-sm {{ $paymentStatusData['select_class'] }}"
                                                                        name="payment_status"
                                                                        onchange="this.form.submit()">
                                                                        @foreach ($paymentStatusMap as $value => $status)
                                                                            @if ($value !== '')
                                                                                <option value="{{ $value }}"
                                                                                    @selected($booking->payment_status === $value)>
                                                                                    {{ __($status['label']) }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </form>
                                                            @else
                                                                <span class="badge {{ $paymentStatusData['badge'] }}">
                                                                    {{ __($paymentStatusData['label']) }}
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (in_array($booking->booking_status, $editableBookingStatuses))
                                                            <form id="bookingStatusForm-{{ $booking->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('admin.booking_record.update_booking_status', ['id' => $booking->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <select
                                                                    class="form-control form-control-sm {{ $bookingStatusData['select_class'] }}"
                                                                    name="booking_status" onchange="this.form.submit()">
                                                                    @foreach ($bookingStatusMap as $value => $status)
                                                                        @if ($value !== '')
                                                                            <option value="{{ $value }}"
                                                                                @selected($booking->booking_status === $value)>
                                                                                {{ __($status['label']) }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </form>
                                                        @else
                                                            <span class="badge {{ $bookingStatusData['badge'] }}">
                                                                {{ __($bookingStatusData['label']) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <a href="{{ route('admin.booking_record.show', ['id' => $booking->id]) }}?language={{ $defaultLang->code }}"
                                                                    class="dropdown-item">
                                                                    {{ __('Details') }}
                                                                </a>

                                                                @if (!is_null($booking->receipt))
                                                                    <a href="#" class="dropdown-item"
                                                                        data-toggle="modal"
                                                                        data-target="#receiptModal-{{ $booking->id }}">
                                                                        {{ __('Receipt') }}
                                                                    </a>
                                                                @endif

                                                                @if (!is_null($booking->invoice))
                                                                    <a href="{{ asset('assets/file/invoices/space/' . $booking->invoice) }}"
                                                                        class="dropdown-item" target="_blank">
                                                                        {{ __('Invoice') }}
                                                                    </a>
                                                                @endif

                                                                <a href="{{ '#emailModal-' . $booking->id }}"
                                                                    data-toggle="modal" class="dropdown-item">
                                                                    {{ __('Send via Mail') }}
                                                                </a>
                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('admin.booking_record.delete', ['id' => $booking->id]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <button type="submit" class="deleteBtn">
                                                                        {{ __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Email Modal -->

                                                @includeIf('admin.booking-management.send-mail', ['booking' => $booking])
                                                @includeWhen(
                                                    $booking->receipt,
                                                    'admin.booking-management.show-receipt',
                                                    ['booking' => $booking]
                                                )
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer ltr">
                    <div class="mt-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $bookings->appends([
                                    'booking_number' => request()->input('booking_number'),
                                    'payment_status' => request()->input('payment_status'),
                                    'booking_status' => request()->input('booking_status'),
                                    'seller' => request()->input('seller'),
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
