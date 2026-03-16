@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
    @else
        {{ __('Dashboard') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_dashboard_page_title ?? __('Dashboard');
@endphp

@section('content')
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!-- Dashboard-area start -->
    <div class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('frontend.user.profile.side-navbar')
                <div class="col-lg-9">
                    <div class="user-profile-details mb-30">
                        <div class="account-info radius-md">
                            <div class="title">
                                <h4>{{ __('Account Information') }}</h4>
                            </div>
                            <div class="main-info">
                                @if ($authUser->first_name != null && $authUser->last_name != null)
                                    <h6>{{ $authUser->first_name . ' ' . $authUser->last_name }}</h6>
                                @else
                                    <h6>{{ $authUser->username }}</h6>
                                @endif
                                <ul class="list">
                                    <li><span>{{ __('Username') . ':' }}</span> <span>{{ $authUser->username ?? '' }}</span>
                                    </li>
                                    <li><span>{{ __('Email') . ':' }}</span>
                                        <span>{{ $authUser->email_address ?? '' }}</span></li>
                                    @if ($authUser->phone_number != null)
                                        <li><span>{{ __('Phone') . ':' }}</span>
                                            <span>{{ $authUser->phone_number ?? '' }}</span></li>
                                    @endif
                                    @if ($authUser->state != null)
                                        <li><span>{{ __('State') . ':' }}</span> <span>{{ $authUser->state ?? '' }}</span>
                                        </li>
                                    @endif
                                    @if ($authUser->city != null)
                                        <li><span>{{ __('City') . ':' }}</span> <span>{{ $authUser->city ?? '' }}</span>
                                        </li>
                                    @endif
                                    @if ($authUser->zip_code != null)
                                        <li><span>{{ __('Zip Code') . ':' }}</span>
                                            <span>{{ $authUser->zip_code ?? '' }}</span></li>
                                    @endif
                                    @if ($authUser->address != null)
                                        <li><span>{{ __('Address') . ':' }}</span>
                                            <span>{{ $authUser->address ?? '' }}</span></li>
                                    @endif
                                    @if ($authUser->country != null)
                                        <li><span>{{ __('Country') . ':' }}</span>
                                            <span>{{ $authUser->country ?? '' }}</span></li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-box radius-md border shadow-md p-30 mb-30 color-1">
                                <div class="card-icon mb-15">
                                    <i class="fal fa-shopping-bag"></i>
                                </div>
                                <div class="card-info">
                                    <h3 class="mb-0">{{ $numOfSpaceBooking ?? '' }}</h3>
                                    <p class="mb-0">{{ __('Total Bookings') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-box radius-md border shadow-md p-30 mb-30 color-2">
                                <div class="card-icon mb-15">
                                    <i class="fal fa-clipboard-list-check"></i>
                                </div>
                                <div class="card-info">
                                    <h3 class="mb-0">{{ @$spaceReview }}</h3>
                                    <p class="mb-0">{{ __('Total Add Review') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-box radius-md border shadow-md p-30 mb-30 color-3">
                                <div class="card-icon mb-15">
                                    <i class="far fa-users"></i>
                                </div>
                                <div class="card-info">
                                    <h3 class="mb-0">{{ $totalRevenue ?? '' }}</h3>
                                    <p class="mb-0">{{ __('Total Revenue') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                                {{ __(ucfirst($bookingDetail->booking_status)) ?? '' }}
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
