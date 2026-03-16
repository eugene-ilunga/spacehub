@extends('vendors.layout')


@section('content')

    @php
        $statusMap = config('status_maps.tour_requests');
        $editableStatuses = config('status_maps.editable_tour_statuses');
        $currentStatus = request()->input('tour_status', '');
        $currentTitle = $statusMap[$currentStatus]['title'] ?? $statusMap['']['title'];
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ __($currentTitle) }} </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __('Bookings & Requests') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __('Tour Requests') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __($currentTitle) }}</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <form id="searchForm" action="{{ route('vendor.space.form.tour_request.index') }}"
                                method="GET">
                                <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Request Id') }}</label>
                                            <input name="request_id" type="text" class="form-control"
                                                placeholder="{{ __('Enter request id') }}" autocomplete="off"
                                                value="{{ !empty(request()->input('request_id')) ? request()->input('request_id') : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Status') }}</label>
                                            <select class="form-control mdb_343" name="tour_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('tour_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('tour_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="confirmed"
                                                    {{ request()->input('tour_status') == 'confirmed' ? 'selected' : '' }}>
                                                    {{ __('Confirmed') }}
                                                </option>
                                                <option value="closed"
                                                    {{ request()->input('tour_status') == 'closed' ? 'selected' : '' }}>
                                                    {{ __('Closed') }}
                                                </option>
                                                <option value="cancelled"
                                                    {{ request()->input('tour_status') == 'cancelled' ? 'selected' : '' }}>
                                                    {{ __('Cancelled') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-2">
                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('vendor.tour-request.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>

                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (isset($tourRequests) && $tourRequests->isEmpty())
                                <h3 class="text-center mt-3">{{ __('NO TOUR REQUEST FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Request Id') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Space Type') }}</th>
                                                <th scope="col">{{ __('Customer Name') }}</th>
                                                <th scope="col">{{ __('Email Address') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Details') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tourRequests as $tourRequest)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $tourRequest->id }}">
                                                    </td>
                                                    <td>{{ @$tourRequest->booking_number }}</td>
                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('space.details', ['slug' => @$tourRequest->slug, 'id' => $tourRequest->space_id]) }}">
                                                            {{ strlen(@$tourRequest->title) > 30
                                                                ? mb_substr(@$tourRequest->title, 0, 30, 'UTF-8') . '...'
                                                                : @$tourRequest->title }}</a>
                                                    </td>
                                                    <td>
                                                        @if ($tourRequest->space_type == 1)
                                                            {{ __('Fixed Timeslot Rental') }}
                                                        @elseif ($tourRequest->space_type == 2)
                                                            {{ __('Hourly Rental') }}
                                                        @elseif ($tourRequest->space_type == 3)
                                                            {{ __('Multi-day Rental') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ @$tourRequest->customer_name }}</td>
                                                    <td>{{ @$tourRequest->customer_email }}</td>
                                                    <td>
                                                        @if ($tourRequest->status == 'pending' || $tourRequest->status == 'confirmed')
                                                            <form id="tourRequestForm-{{ $tourRequest->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('vendor.tour-request.status.update', ['id' => $tourRequest->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <select
                                                                    class="form-control form-control-sm 
                                                                    @if ($tourRequest->status == 'confirmed') bg-success 
                                                                    @elseif ($tourRequest->status == 'pending') bg-warning text-dark @elseif ($tourRequest->status == 'closed') bg-secondary 
                                                                    @else bg-danger @endif"
                                                                    name="tour_status" onchange="this.form.submit()">
                                                                    <option value="pending"
                                                                        {{ $tourRequest->status == 'pending' ? 'selected' : '' }}>
                                                                        {{ __('Pending') }}
                                                                    </option>
                                                                    <option value="confirmed"
                                                                        {{ $tourRequest->status == 'confirmed' ? 'selected' : '' }}>
                                                                        {{ __('Confirmed') }}
                                                                    </option>

                                                                    <option value="closed"
                                                                        {{ $tourRequest->status == 'closed' ? 'selected' : '' }}>
                                                                        {{ __('Closed') }}
                                                                    </option>
                                                                    <option value="cancelled"
                                                                        {{ $tourRequest->status == 'cancelled' ? 'selected' : '' }}>
                                                                        {{ __('Cancelled') }}
                                                                    </option>

                                                                </select>
                                                            </form>
                                                        @else
                                                            @if ($tourRequest->status == 'confirmed')
                                                                <span class="badge badge-success">{{ __('Confirmed') }}
                                                                </span>
                                                            @elseif ($tourRequest->status == 'pending')
                                                                <span class="badge badge-warning">{{ __('Pending') }}
                                                                </span>
                                                            @elseif ($tourRequest->status == 'closed')
                                                                <span class="badge badge-secondary">{{ __('Closed') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger">{{ __('Cancelled') }}
                                                                </span>
                                                            @endif
                                                        @endif

                                                    </td>

                                                    <td>
                                                        @if (!is_null($tourRequest->information))
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#tourRequestModal-{{ $tourRequest->id }}"
                                                                class="btn btn-success btn-sm">
                                                                {{ __('View') }}</a>
                                                        @else
                                                            <p class="ml-4">
                                                                {{ __('---') }}
                                                            </p>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('vendor.tour-request.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ @$tourRequest->id }}">

                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm deleteBtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
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

                <div class="card-footer ltr">
                    <div class="mt-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $tourRequests->appends([
                                    'requets_id' => request()->input('requets_id'),
                                    'status' => request()->input('tour_status'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('vendors.tour-request.tour-details')
@endsection
