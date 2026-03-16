@extends('admin.layout')

@section('content')
    <div class="mt-2 mb-4">
        <h2 class="pb-2">{{ __('Welcome back') . ', ' }} {{ $authAdmin->first_name . ' ' . $authAdmin->last_name . '!' }}</h2>
    </div>

    {{-- dashboard information start --}}
    @php
        if (!is_null($roleInfo)) {
            $rolePermissions = json_decode($roleInfo->permissions);
        }
    @endphp

    <div class="row dashboard-items">
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transactions', $rolePermissions)))
            <div class="col-sm-6 col-md-3">

                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-sack-dollar"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Lifetime Earnings') }}</p>
                                        <h4 class="card-title">{{ symbolPrice($settings->life_time_earning) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transactions', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-dark card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-usd-square"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Total Profit') }}</p>
                                        <h4 class="card-title">{{ symbolPrice($settings->total_profit) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
            </div>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transactions', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.dashboard.transaction') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-warning card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-exchange-alt"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{$total_transaction > 1 ?  __('Transactions') : __('Transaction') }}</p>
                                        <h4 class="card-title">{{ $total_transaction }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Logs', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.payment-log.index') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $memberships > 1 ?  __('Subscription Log') : __('Subscription Log') }}</p>
                                        <h4 class="card-title">{{ $memberships }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Space Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-success card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{$spaces > 1 ?  __('Spaces') :  __('Space')}}</p>
                                        <h4 class="card-title">{{ $spaces }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Service Orders', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.booking_record.index') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-danger card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $spaceBookings > 1 ?  __('Space Bookings') : __('Space Booking') }}</p>
                                        <h4 class="card-title">{{ $spaceBookings }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.support_tickets') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-ticket-alt"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $support_tickets > 1 ?  __('Support Tickets') : __('Support Ticket') }}</p>
                                        <h4 class="card-title">{{ $support_tickets }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.blog_management.posts', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-dark card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-blog"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $posts > 1 ?  __('Posts') : __('Post') }}</p>
                                        <h4 class="card-title">{{ $posts }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.end-user.vendor.registered_vendor') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-warning card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-users"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $sellers > 1 ?  __('Vendors') : __('Vendor')}}</p>
                                        <h4 class="card-title">{{ $sellers }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.registered_users') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-orchid card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="la flaticon-users"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ $users > 1 ? __('Users') : __('User') }}</p>
                                        <h4 class="card-title">{{ $users }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.subscribers') }}?language={{$defaultLang->code}}">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-bell"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{$subscribers > 1 ?  __('Subscribers') : __('Subscriber') }}</p>
                                        <h4 class="card-title">{{ $subscribers }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif


    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Subscriptions') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlySubscriptionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Space Bookings') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="serviceOrderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- dashboard information end --}}
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/admin/js/chart.min.js') }}"></script>

    <script>
        const monthArr = {!! json_encode($months) !!};
        const spaceBookingArr = {!! json_encode($totalSpaceBookings) !!};
        const subscriptionArr = {!! json_encode($subscriptionArr) !!};
        var monthlySpaceBookings = '{{ __('Monthly Space Bookings') }}';
        var monthlySubscriptions = '{{ __('Monthly Subscriptions') }}';
    </script>

    <script type="text/javascript" src="{{ asset('assets/admin/js/my-chart.js') }}"></script>
@endsection
