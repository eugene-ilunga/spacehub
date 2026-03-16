@extends('vendors.layout')

@section('content')
    @php
        $statusMap = config('status_maps.quote_requests');
        $editableStatuses = config('status_maps.editable_quote_statuses');
        $currentStatus = request()->input('quote_status', '');
        $currentTitle = $statusMap[$currentStatus]['title'] ?? $statusMap['']['title'];
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ __($currentTitle) }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __('Bookings & Requests') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">{{ __('Quote Requests') }}</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"> <a href="#">{{ __($currentTitle) }}</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <form id="searchForm" action="{{ route('vendor.space.form.get_quote.index') }}" method="GET">
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
                                            <select class="form-control mdb_343" name="quote_status"
                                                onchange="this.form.submit()">
                                                <option value=""
                                                    {{ empty(request()->input('quote_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('quote_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="responded"
                                                    {{ request()->input('quote_status') == 'responded' ? 'selected' : '' }}>
                                                    {{ __('Responded') }}
                                                </option>
                                                <option value="in_progress"
                                                    {{ request()->input('quote_status') == 'in_progress' ? 'selected' : '' }}>
                                                    {{ __('In Progress') }}
                                                </option>
                                                <option value="closed"
                                                    {{ request()->input('quote_status') == 'closed' ? 'selected' : '' }}>
                                                    {{ __('Closed') }}
                                                </option>
                                                <option value="cancelled"
                                                    {{ request()->input('quote_status') == 'cancelled' ? 'selected' : '' }}>
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
                                data-href="{{ route('vendor.quote-request.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>

                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (isset($quoteRequests) && $quoteRequests->isEmpty())
                                <h3 class="text-center mt-3">{{ __('NO QUOTE REQUEST FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Rquest Id') }}</th>
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
                                            @foreach ($quoteRequests as $quoteRequest)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ @$quoteRequest->id }}">
                                                    </td>
                                                    <td>{{ @$quoteRequest->booking_number }}</td>
                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('space.details', ['slug' => @$quoteRequest->slug, 'id' => $quoteRequest->space_id]) }}">
                                                            {{ strlen(@$quoteRequest->title) > 30
                                                                ? mb_substr(__(@$quoteRequest->title), 0, 30, 'UTF-8') . '...'
                                                                : __(@$quoteRequest->title) }}</a>

                                                    </td>
                                                    <td>
                                                        @if ($quoteRequest->space_type == 1)
                                                            {{ __('Fixed Timeslot Rental') }}
                                                        @elseif ($quoteRequest->space_type == 2)
                                                            {{ __('Hourly Rental') }}
                                                        @elseif ($quoteRequest->space_type == 3)
                                                            {{ __('Multi-day Rental') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ @$quoteRequest->customer_name }}</td>
                                                    <td>{{ @$quoteRequest->customer_email }}</td>


                                                    <td>
                                                        @if (
                                                            $quoteRequest->status == 'pending' ||
                                                                $quoteRequest->status == 'responded' ||
                                                                $quoteRequest->status == 'in_progress')
                                                            <form id="tourRequestForm-{{ $quoteRequest->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('vendor.quote-request.status.update', ['id' => $quoteRequest->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <select
                                                                    class="form-control form-control-sm 
                                                                    @if ($quoteRequest->status == 'closed') bg-secondary 
                                                                    @elseif ($quoteRequest->status == 'pending') bg-warning text-dark @elseif ($quoteRequest->status == 'responded') bg-success
                                                                    @elseif ($quoteRequest->status == 'in_progress') bg-info 
                                                                    @else bg-danger @endif"
                                                                    name="quote_status" onchange="this.form.submit()">

                                                                    <option value="pending"
                                                                        {{ $quoteRequest->status == 'pending' ? 'selected' : '' }}>
                                                                        {{ __('Pending') }}
                                                                    </option>
                                                                    <option value="responded"
                                                                        {{ $quoteRequest->status == 'responded' ? 'selected' : '' }}>
                                                                        {{ __('Responded') }}
                                                                    </option>
                                                                    <option value="in_progress"
                                                                        {{ $quoteRequest->status == 'in_progress' ? 'selected' : '' }}>
                                                                        {{ __('In Progress') }}
                                                                    </option>
                                                                    <option value="closed"
                                                                        {{ $quoteRequest->status == 'closed' ? 'selected' : '' }}>
                                                                        {{ __('Closed') }}
                                                                    </option>


                                                                    <option value="cancelled"
                                                                        {{ $quoteRequest->status == 'cancelled' ? 'selected' : '' }}>
                                                                        {{ __('Cancelled') }}
                                                                    </option>

                                                                </select>
                                                            </form>
                                                        @else
                                                            @if ($quoteRequest->status == 'closed')
                                                                <span class="badge badge-secondary">{{ __('Closed') }}
                                                                </span>
                                                            @elseif ($quoteRequest->status == 'pending')
                                                                <span class="badge badge-warning">{{ __('Pending') }}
                                                                </span>
                                                            @elseif ($quoteRequest->status == 'in_progress')
                                                                <span class="badge badge-info">{{ __('In Progress') }}
                                                                </span>
                                                            @elseif ($quoteRequest->status == 'responded')
                                                                <span class="badge badge-info">{{ __('Responded') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger">{{ __('Cancelled') }}
                                                                </span>
                                                            @endif
                                                        @endif

                                                    </td>

                                                    <td>
                                                        @if (!is_null($quoteRequest->information))
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#quoteRequestModal-{{ $quoteRequest->id }}"
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
                                                            action="{{ route('vendor.quote-request.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ @$quoteRequest->id }}">

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
                            {{ $quoteRequests->appends([
                                    'requets_id' => request()->input('requets_id'),
                                    'status' => request()->input('quote_status'),
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('vendors.quote-request.quote-details')
@endsection
