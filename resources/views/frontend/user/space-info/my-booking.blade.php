@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_booking_page_title ?? __('Space Bookings') }}
    @else
        {{ __('My Bookings') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_booking_page_title ?? __('Space Bookings');
@endphp

@section('content')
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!-- Dashboard-area start -->
    <div class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('frontend.user.profile.side-navbar')
                <div class="col-lg-9">
                    <div class="account-info radius-md mb-40">
                        <div class="title">
                            <h4>{{ __('Recent Bookings') }}</h4>
                        </div>

                        <div class="main-info">
                            <div class="main-table">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Booking number') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Booking Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($bookingDetails))
                                                @foreach ($bookingDetails as $bookingDetail)
                                                    <tr>
                                                        <td>{{ '#' . $bookingDetail->booking_number ?? '' }}</td>
                                                        <td>{{ Carbon\Carbon::parse($bookingDetail->created_at)->format('Y-m-d') }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="{{ $bookingDetail->booking_status === 'pending' ? 'pending' : ($bookingDetail->booking_status === 'rejected' ? 'reject' : ($bookingDetail->booking_status === 'approved' ? 'complete' : 'default')) }}">
                                                                {{ __(ucfirst($bookingDetail->booking_status)) }}
                                                            </span>
                                                        </td>
                                                        <td><a href="{{ route('frontend.user.space-booking-details', ['id' => $bookingDetail->id]) }}"
                                                                class="btn">{{ __('Details') }}</a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard-area end -->
@endsection
