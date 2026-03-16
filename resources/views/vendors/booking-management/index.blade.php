@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">
            @if (empty(request()->input('booking_status')))
                {{ __('All Bookings') }}
            @elseif (request()->input('booking_status') == 'pending')
                {{ __('Pending Bookings') }}
            @elseif (request()->input('booking_status') == 'approved')
                {{ __('Approved Bookings') }}
            @elseif (request()->input('booking_status') == 'rejected')
                {{ __('Rejected Bookings') }}
            @endif
        </h4>
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
                <a href="#">{{ __('Bookings & Requests') }}</a>
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
                <a href="#">
                    @if (empty(request()->input('booking_status')))
                        {{ __('All Bookings') }}
                    @elseif (request()->input('booking_status') == 'pending')
                        {{ __('Pending Bookings') }}
                    @elseif (request()->input('booking_status') == 'approved')
                        {{ __('Approved Bookings') }}
                    @elseif (request()->input('booking_status') == 'rejected')
                        {{ __('Rejected Bookings') }}
                    @endif
                </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <form id="searchForm" action="{{ route('vendor.booking_record.index') }}" method="GET">
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

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Payment') }}</label>
                                            <select class="form-control mdb_343" name="payment_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="approved"
                                                    {{ request()->input('payment_status') == 'approved' ? 'selected' : '' }}>
                                                    {{ __('Approved') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                                                    {{ __('Rejected') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Booking') }}</label>
                                            <select class="form-control mdb_343" name="booking_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('booking_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('booking_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>

                                                <option value="approved"
                                                    {{ request()->input('booking_status') == 'approved' ? 'selected' : '' }}>
                                                    {{ __('Approved') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request()->input('booking_status') == 'rejected' ? 'selected' : '' }}>
                                                    {{ __('Rejected') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-2">
                            <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right card-header-button"
                                data-href="{{ route('vendor.booking_record.bulk_delete') }}">
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
                                                <th scope="col">{{ __('Space Title') }}</th>
                                                <th scope="col">{{ __('Total Price') }}</th>
                                                <th scope="col">{{ __('Paid via') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>
                                                <th scope="col">{{ __('Booking Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $booking)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $booking->id }}">
                                                    </td>
                                                    <td>{{ '#' . $booking->booking_number }}</td>

                                                    @php $customerName = $booking->customer_name; @endphp

                                                    <td>{{ $customerName }}</td>
                                                    <td>
                                                        @if (!empty($booking->space_slug))
                                                            <a target="_blank"
                                                                href="{{ route('space.details', ['slug' => $booking->space_slug, 'id' => $booking->space_id]) }}">
                                                                {{ strlen($booking->space_title) > 60
                                                                    ? mb_substr($booking->space_title, 0, 60, 'UTF-8') . '...'
                                                                    : $booking->space_title }}
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
                                                        @if (is_null($booking->payment_method))
                                                            <span class="ml-4">-</span>
                                                        @else
                                                            {{ __($booking->payment_method) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($booking->payment_status == 'completed')
                                                            <span class="badge badge-success">{{ __('Completed') }}</span>
                                                        @elseif ($booking->payment_status == 'pending')
                                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        @if ($booking->booking_status == 'approved')
                                                            <span class="badge badge-success">{{ __('Approved') }}</span>
                                                        @elseif ($booking->booking_status == 'pending')
                                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Rejected') }}</span>
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
                                                                <a href="{{ route('vendor.booking_record.show', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
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
                                                                @php
                                                                    $chatPermission = App\Http\Helpers\SellerPermissionHelper::getPackageInfo(
                                                                        Auth::guard('seller')->user()->id,
                                                                        $booking->seller_membership_id,
                                                                    );
                                                                @endphp
                                                                @if ($chatPermission == true)
                                                                    <a href="{{ route('vendor.booking_record.message', ['id' => $booking->id]) }}"
                                                                        class="dropdown-item">
                                                                        {{ __('Chat with Customer') }}
                                                                    </a>
                                                                @endif

                                                                <a href="{{ '#emailModal-' . $booking->id }}"
                                                                    data-toggle="modal" class="dropdown-item">
                                                                    {{ __('Send via Mail') }}
                                                                </a>
                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('vendor.booking_record.delete', ['id' => $booking->id]) }}"
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
                                                @includeIf('vendors.booking-management.send-mail')
                                                @includeWhen($booking->receipt,'vendors.booking-management.show-receipt')
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mt-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $bookings->appends([
                                    'booking_number' => request()->input('booking_number'),
                                    'payment_status' => request()->input('payment_status'),
                                    'booking_status' => request()->input('booking_status'),
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
