@extends('admin.layout')

@section('content')
    @php
        $statusMap = [
            '' => __('All Requests'),
            'pending' => __('Pending Requests'),
            'processing' => __('Processing Requests'),
            'approved' => __('Approved Requests'),
            'rejected' => __('Rejected Requests'),
        ];
        $currentStatus = request()->input('feature_status', '');
        $currentTitle = $statusMap[$currentStatus] ?? $statusMap[''];
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ $currentTitle }} </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Space Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Featured Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $currentTitle }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <form id="searchForm" action="{{ route('admin.feature_record.index') }}" method="GET">
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
                                            <label>{{ __('Vendor') }}</label>

                                            <select class="form-control mdb_343 select2" name="seller"
                                                onchange="this.form.submit()">
                                                <option value="" @selected(!request()->filled('seller'))>
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

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Payment') }}</label>
                                            <select class="form-control mdb_343" name="payment_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="completed"
                                                    {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                                                    {{ __('Completed') }}
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
                                            <select class="form-control mdb_343" name="feature_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('feature_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('feature_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="approved"
                                                    {{ request()->input('feature_status') == 'approved' ? 'selected' : '' }}>
                                                    {{ __('Approved') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request()->input('feature_status') == 'rejected' ? 'selected' : '' }}>
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
                                data-href="{{ route('admin.feature_record.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($bookings) === 0)
                                <h3 class="text-center mt-3">{{ __('NO RECORDS FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Booking No.') }}</th>
                                                <th scope="col">{{ __('Vendor') }}</th>
                                                <th scope="col">{{ __('Space Title') }}</th>
                                                <th scope="col">{{ __('Total Price') }}</th>
                                                <th scope="col">{{ __('Paid via') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>
                                                <th scope="col">{{ __('Booking Status') }}</th>
                                                <th scope="col">{{ __('Days') }}</th>
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
                                                    <td>
                                                        @if ($booking->seller_id)
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
                                                                {!! truncate_text($booking->space_title ?? '', 60) !!}
                                                            </a>
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!is_null($booking->grand_total))
                                                            {{ __('Requested') }}
                                                        @else
                                                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (is_null($booking->payment_method))
                                                            <span class="ml-4">-</span>
                                                        @else
                                                            {{ $booking->payment_method }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($booking->gateway_type == 'online')
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Completed') }}</span>
                                                            </h2>
                                                        @else
                                                            @if ($booking->payment_status == 'pending')
                                                                <form id="paymentStatusForm-{{ $booking->id }}"
                                                                    class="d-inline-block"
                                                                    action="{{ route('admin.feature_record.update_payment_status', ['id' => $booking->id]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select
                                                                        class="form-control form-control-sm
                                @if ($booking->payment_status == 'completed') bg-success
                                @elseif ($booking->payment_status == 'pending') bg-warning text-dark
                               @else bg-danger @endif"
                                                                        name="payment_status"
                                                                        onchange="this.form.submit()">
                                                                        <option value="completed"
                                                                            {{ $booking->payment_status == 'completed' ? 'selected' : '' }}>
                                                                            {{ __('Completed') }}
                                                                        </option>
                                                                        <option value="pending"
                                                                            {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>
                                                                            {{ __('Pending') }}
                                                                        </option>
                                                                        <option value="rejected"
                                                                            {{ $booking->payment_status == 'rejected' ? 'selected' : '' }}>
                                                                            {{ __('Rejected') }}
                                                                        </option>
                                                                    </select>
                                                                </form>
                                                            @else
                                                                @if ($booking->payment_status == 'completed')
                                                                    <span
                                                                        class="badge badge-success">{{ __('Completed') }}</span>
                                                                @elseif ($booking->payment_status == 'pending')
                                                                    <span
                                                                        class="badge badge-warning">{{ __('Pending') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge badge-danger">{{ __('Rejected') }}</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($booking->booking_status == 'pending')
                                                            <form id="bookingStatusForm-{{ $booking->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('admin.feature_record.update_booking_status', ['id' => $booking->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <select
                                                                    class="form-control form-control-sm @if ($booking->booking_status == 'approved') bg-success @elseif ($booking->booking_status == 'pending') bg-warning text-dark @else bg-danger @endif"
                                                                    name="booking_status" onchange="this.form.submit()">
                                                                    <option value="pending"
                                                                        {{ $booking->booking_status == 'pending' ? 'selected' : '' }}>
                                                                        {{ __('Pending') }}
                                                                    </option>
                                                                    <option value="approved"
                                                                        {{ $booking->booking_status == 'approved' ? 'selected' : '' }}>
                                                                        {{ __('Approved') }}
                                                                    </option>
                                                                    <option value="rejected"
                                                                        {{ $booking->booking_status == 'rejected' ? 'selected' : '' }}>
                                                                        {{ __('Rejected') }}
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        @elseif($booking->booking_status == 'rejected')
                                                            <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                                        @elseif ($booking->end_date < Carbon\Carbon::now())
                                                            <span class="badge badge-secondary">{{ __('Expired') }}</span>
                                                        @else
                                                            @if ($booking->booking_status == 'approved')
                                                                <span
                                                                    class="badge badge-success">{{ __('Approved') }}</span>
                                                            @elseif ($booking->booking_status == 'pending')
                                                                <span
                                                                    class="badge badge-warning">{{ __('Pending') }}</span>
                                                            @elseif ($booking->booking_status == 'rejected')
                                                                <span
                                                                    class="badge badge-danger">{{ __('Rejected') }}</span>
                                                            @endif
                                                        @endif
                                                    </td>


                                                    @if ($booking->booking_status == 'approved')
                                                        <td>
                                                            {{ @$booking->featureCharge->day . ' ' }}{{ __('days') . ' ' }}{{ ' (' . \Carbon\Carbon::parse($booking->start_date)->format('j F, Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($booking->end_date)->format('j F, Y') . ')' }}
                                                        </td>
                                                    @else
                                                        <td>{{ @$booking->featureCharge->day }}</td>
                                                    @endif

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

                                                                @if (!is_null($booking->attachment))
                                                                    <a href="#" class="dropdown-item"
                                                                        data-toggle="modal"
                                                                        data-target="#receiptModalForFeatire-{{ $booking->id }}">
                                                                        {{ __('Receipt') }}
                                                                    </a>
                                                                @endif
                                                                @if (!is_null($booking->invoice))
                                                                    <a target="_blank" class="dropdown-item" href="{{ asset('assets/file/invoices/space/featured/' . $booking->invoice) }}">{{ __('Invoice') }}</a>
                                                                    
                                                                @endif

                                                                <a href="#" class="dropdown-item"
                                                                    data-toggle="modal"
                                                                    data-target="#detailsModal-{{ $booking->id }}">
                                                                    {{ __('Details') }}
                                                                </a>
                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('admin.feature_record.delete', ['id' => $booking->id]) }}"
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

                                                <!-- details Modal -->
                                                @includeWhen($booking->attachment,'admin.featured-management.show-receipt')

                                                @includeIf('admin.featured-management.details')
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
                                    'feature_status' => request()->input('feature_status'),
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
