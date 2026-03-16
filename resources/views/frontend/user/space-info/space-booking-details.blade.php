@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_booking_details_page_title ?? __('Booking Details') }}
    @else
        {{ __('Booking Details') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_booking_details_page_title ?? __('Booking Details');
@endphp

@section('content')
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    @php
        $timeFormat = $basicInfo->time_format ?? '12h';
        if ($timeFormat == '12h') {
            $bookingInfo->start_time = \Carbon\Carbon::parse($bookingInfo->start_time)->format('h:i A');
            $bookingInfo->end_time = \Carbon\Carbon::parse($bookingInfo->end_time)->format('h:i A');
            $bookingInfo->end_time_without_interval = \Carbon\Carbon::parse(
                $bookingInfo->end_time_without_interval,
            )->format('h:i A');
        } else {
            $bookingInfo->start_time = \Carbon\Carbon::parse($bookingInfo->start_time)->format('H:i');
            $bookingInfo->end_time = \Carbon\Carbon::parse($bookingInfo->end_time)->format('H:i');
            $bookingInfo->end_time_without_interval = \Carbon\Carbon::parse(
                $bookingInfo->end_time_without_interval,
            )->format('H:i');
        }
    @endphp

    <!-- Dashboard-area start -->
    <div class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">
                @include('frontend.user.profile.side-navbar')
                <div class="col-lg-9">
                    <div class="user-profile-details mb-40">
                        <div class="order-details radius-md">

                            <div class="title">
                                <h4>{{ __('My Booking details') }}</h4>
                            </div>
                            @php
                                $position = $bookingInfo->currency_symbol_position;
                                $symbol = $bookingInfo->currency_symbol;
                            @endphp
                            <div class="view-order-page mb-40">
                                <div class="order-info-area">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <div class="order-info mb-20">
                                                <h6>{{ __('Booking') . '#' }} {{ $bookingInfo->booking_number }}
                                                    <span class="badge
                                                        @if (strtolower($bookingInfo->booking_status) === 'pending') bg-warning 
                                                        @elseif(strtolower($bookingInfo->booking_status) === 'approved') bg-success
                                                        @elseif(strtolower($bookingInfo->booking_status) === 'rejected') bg-danger @endif">
                                                        [{{ __(ucfirst($bookingInfo->booking_status)) }}]
                                                    </span>
                                                </h6>
                                                <p class="m-0">{{ __('Booking Date') . ':' }}
                                                    {{ \Carbon\Carbon::parse($bookingInfo->booking_date)->format('d-M-Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        @if (!is_null($bookingInfo->invoice))
                                            @php
                                                $slug = @$spaceContent->slug;
                                                $date = $bookingInfo->created_at->toDateString();
                                            @endphp
                                            <div class="col-lg-4">
                                                <div class="printit mb-20">
                                                    <a href="{{ asset('assets/file/invoices/space/' . $bookingInfo->invoice) }}"
                                                        download="{{ $slug . '-' . $date . '.pdf' }}"
                                                        class="btn btn-md radius-sm"><i
                                                            class="fas fa-print"></i>{{ ' ' . __('Print') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="billing-add-area mb-10">
                                <div class="row">
                                    <div class="billing-add-item col-xl-6 col-lg-6">
                                        <div class="vendor-information mb-30">
                                            <h5>{{ __('Vendor Information') }}</h5>
                                            <div class="main-info">
                                                @if (!empty($seller))
                                                    <ul class="list">
                                                        <li>
                                                            <span>{{ __('Name') . ':' }}</span>
                                                            <a class="link-underline" href="{{ $seller->url ?? '#' }}"
                                                                target="_blank">
                                                                {{ ucfirst(@$seller->username) }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <span>{{ __('Email') . ':' }}</span>
                                                            <span class="email-text">{{ @$seller->email }}</span>
                                                        </li>
                                                        <li>
                                                            <span>{{ __('Phone') . ':' }}</span>
                                                            <span>{{ @$seller->phone }}</span>
                                                        </li>
                                                        @if ($seller->address != null)
                                                            <li>
                                                                <span>{{ __('Address') . ':' }}</span>
                                                                <span>{{ @$seller->address }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($seller->city != null)
                                                            <li>
                                                                <span>{{ __('City') . ':' }}</span>
                                                                <span>{{ ucfirst($seller->city) }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($seller->state != null)
                                                            <li>
                                                                <span>{{ __('State') . ':' }}</span>
                                                                <span>{{ ucfirst($seller->state) }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($seller->country != null)
                                                            <li>
                                                                <span>{{ __('Country') . ':' }}</span>
                                                                <span>{{ ucfirst($seller->country) }}</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="billing-add-item col-xl-6 col-lg-6">
                                    
                                        <div class="customer-information mb-30">
                                            <h5>{{ __('Customer Information') }}</h5>
                                            <div class="main-info">
                                                <ul class="list">
                                                    <li>
                                                        <span>{{ __('Name') . ':' }}</span>
                                                        <span>
                                                            @if (!empty($user))
                                                                @if (@$user->first_name && @$user->last_name)
                                                                    <h6 class="mb-0">
                                                                        {{ $user->first_name . ' ' . $user->last_name }}
                                                                    </h6>
                                                                @else
                                                                    <h6 class="mb-0">{{ $user->username }}</h6>
                                                                @endif
                                                        </span>
                                                    </li>
                                                    @if ($user->email_address != null)
                                                        <li>
                                                            <span>{{ __('Email') . ':' }}</span>
                                                            <span class="email-text">{{ @$user->email_address }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($user->phone_number != null)
                                                        <li>
                                                            <span>{{ __('Phone') . ':' }}</span>
                                                            <span>{{ @$user->phone_number }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($user->state != null)
                                                        <li>
                                                            <span>{{ __('State') . ':' }}</span>
                                                            <span>{{ @$user->state }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($user->city != null)
                                                        <li>
                                                            <span>{{ __('City') . ':' }}</span>
                                                            <span>{{ @$user->city }}</span>
                                                        </li>
                                                    @endif
                                                    @if (@$user->zip_code)
                                                        <li>
                                                            <span>{{ __('Zip Code') . ':' }}</span>
                                                            <span>{{ @$user->zip_code }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($user->country != null)
                                                        <li>
                                                            <span>{{ __('Country') . ':' }}</span>
                                                            <span>{{ @$user->country }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($user->address != null)
                                                        <li>
                                                            <span>{{ __('Address') . ':' }}</span>
                                                            <span>{{ @$user->address }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="billing-add-item col-xl-6 col-lg-6">
                                        <div class="booking-information mb-30">
                                            <h5>{{ __('Booking Information') }}</h5>
                                            <div class="main-info">
                                                <ul class="list">
                                                    <li><span>{{ __('Booking No') . '.' }} </span>
                                                        <span
                                                            class="badge bg-danger">{{ @$bookingInfo->booking_number }}</span>
                                                    </li>
                                                    @if ($bookingInfo->booking_type == 3)
                                                        <li>
                                                            <span>{{ __('Booking Date') . ':' }}</span>
                                                            <span class="amount">
                                                                {{ Carbon\Carbon::parse($bookingInfo->created_at)->format('F j, Y') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span>{{ __('Start Date') . ':' }}</span>
                                                            <span>{{ Carbon\Carbon::parse($bookingInfo->start_date)->format('F  j, Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span>{{ __('End Date') . ':' }}</span>
                                                            <span>{{ Carbon\Carbon::parse($bookingInfo->end_date)->format('F j, Y') }}</span>
                                                        </li>
                                                    @endif

                                                    @if ($bookingInfo->booking_type == 1 || $bookingInfo->booking_type == 2)
                                                        <li>
                                                            <span>{{ __('Start Time') . ':' }}</span>
                                                            <span dir="ltr">{{ @$bookingInfo->start_time }}</span>
                                                        </li>
                                                        @if ($bookingInfo->booking_type == 1)
                                                            <li>
                                                                <span>{{ __('End Time') . ':' }}</span>
                                                                <span dir="ltr">{{ @$bookingInfo->end_time }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($bookingInfo->booking_type == 2)
                                                            <li>
                                                                <span>{{ __('End Time') . ':' }}</span>
                                                                <span dir="ltr">{{ @$bookingInfo->end_time_without_interval }}</span>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    <li>
                                                        <span>{{ __('Title') . ':' }}</span>
                                                        <a class="badge bg-danger link-underline"
                                                            href="{{ route('space.details', ['slug' => $spaceContent->slug, 'id' => $spaceContent->space_id]) }}"
                                                            target="_blank">{{ @$spaceContent->title }}
                                                        </a>
                                                    </li>

                                                    @if ($bookingInfo->number_of_guest != null)
                                                        <li>
                                                            <span>{{ $bookingInfo->number_of_guest > 1
                                                                ? __('Number Of Guests') . ':'
                                                                : __('Number of Guest') }}</span>
                                                            <span
                                                                class="amount">{{ @$bookingInfo->number_of_guest }}</span>
                                                        </li>
                                                    @endif

                                                    @if (!is_null($services))
                                                        <li>
                                                            <span>{{ __('Services') . ':' }}</span>
                                                            <a class="badge bg-danger" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#serviceShowModal">{{ __('View') }}</a>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <span>{{ __('Booked By') . ':' }}</span>
                                                        <span>{{ is_null($bookingInfo->booked_by)
                                                            ? __('Website')
                                                            : ($bookingInfo->booked_by == 'admin'
                                                                ? __('Admin')
                                                                : __('Vendor')) }}</span>
                                                    </li>

                                                    <li>
                                                        <span>{{ __('Address') . ':' }}</span>
                                                        <span>{{ $spaceContent->address }}</span>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="billing-add-item col-xl-6 col-lg-6">
                                        <div class="payment-information mb-30">
                                            <h5>{{ __('Payment Summary') }}</h5>

                                            <div class="main-info">
                                                <ul class="list">
                                                    @if ($rent != 0)
                                                        @if ($type != null)
                                                            <li>
                                                                <span>{{ __('Rent') . '/' . __($type) . ':' }}</span>
                                                                <span dir="ltr"
                                                                    class="amount">{{ $position == 'left' ? $symbol : '' }}{{ $rent }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <span>{{ __('Total Rent') . ':' }}</span>
                                                                <span dir="ltr"
                                                                    class="amount">{{ $position == 'left' ? $symbol : '' }}{{ $rent }}{{ $position == 'right' ? $symbol : '' }}</span>
                                                            </li>
                                                        @endif
                                                    @endif

                                                    @if ($space_type == 3)
                                                        @if ($bookingInfo->number_of_day > 1)
                                                            <li>
                                                                <span>{{ $bookingInfo->number_of_day > 1 ? __('Number of Days') . ':' : __('Number of Day') . ':' }}</span>
                                                                <span
                                                                    class="amount">{{ $bookingInfo->number_of_day }}</span>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <span>{{ __('Total Rent') . ':' }}</span>
                                                                <span dir="ltr" class="amount">
                                                                    {{ $position == 'left' ? $symbol : '' }}{{ $rent }}{{ $position == 'right' ? $symbol : '' }}
                                                                </span>
                                                            </li>
                                                        @endif
                                                    @endif

                                                    @if ($space_type == 2)
                                                        <li>
                                                            <span>{{ $bookingInfo->custom_hour > 1 ? __('Hours') . ':' : __('Hour') . ':' }}</span>
                                                            <span class="amount"> {{ $bookingInfo->custom_hour }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($bookingInfo->service_total != 0)
                                                        <li>
                                                            <span>{{ __('Services Total') . ':' }}</span>
                                                            <span dir="ltr" class="amount">
                                                                {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $bookingInfo->service_total }}
                                                                {{ $position == 'right' ? ' ' . $symbol : '' }}
                                                            </span>
                                                        </li>
                                                    @endif

                                                    @if ($bookingInfo->discount != 0)
                                                        <li>
                                                            <span>{{ __('Discount') }} (<i
                                                                    class="fas fa-minus text-danger text-small"></i>)
                                                                :</span>
                                                            <span dir="ltr" class="amount">
                                                                {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $bookingInfo->discount }}{{ $position == 'right' ? ' ' . $symbol : '' }}
                                                            </span>
                                                        </li>
                                                    @endif

                                                    @if (!is_null($bookingInfo->sub_total))
                                                        <li>
                                                            <span>{{ __('Subtotal') . ':' }}</span>
                                                            <span dir="ltr" class="amount">
                                                                {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $bookingInfo->sub_total }}{{ $position == 'right' ? ' ' . $symbol : '' }}
                                                            </span>
                                                        </li>
                                                    @endif

                                                    @if (!is_null($bookingInfo->tax))
                                                        <li>
                                                            <span>{{ __('Tax') . ':' }}</span>
                                                            <span dir="ltr" class="amount">
                                                                {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $symbol : '' }}</span>
                                                        </li>
                                                    @endif

                                                    @if (!is_null($bookingInfo->grand_total))
                                                        <li>
                                                            <span>{{ __('Paid Amount') . ':' }}</span>
                                                            <span dir="ltr" class="amount">
                                                                {{ $position == 'left' ? $symbol : '' }}{{ @$bookingInfo->grand_total }}{{ $position == 'right' ? $symbol : '' }}
                                                            </span>
                                                        </li>
                                                    @endif
                                                    <li class="payment-method">
                                                        <span>{{ __('Payment Method') }}</span>
                                                        <span
                                                            class="fs-14">{{ __($bookingInfo->payment_method) }}</span>
                                                    </li>
                                                    <li>
                                                        <span>{{ __('Payment Status') }}</span>
                                                        <span class="badge 
                                                                @if (strtolower($bookingInfo->payment_status) === 'completed') bg-success      
                                                                @elseif(strtolower($bookingInfo->payment_status) === 'pending') bg-warning
                                                                @elseif(strtolower($bookingInfo->payment_status) === 'rejected') bg-danger @endif ">{{ __(ucfirst($bookingInfo->payment_status)) }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="edit-account-info mt-15">
                                <a href="{{ route('user.space_bookings') }}" class="btn btn-md btn-primary radius-sm"
                                    title="{{ __('Go Back') }}" target="_self">{{ __('Go Back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard-area end -->
    @include('frontend.user.space-info.service-details')
@endsection
