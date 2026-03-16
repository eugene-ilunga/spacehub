@extends('vendors.layout')

@section('content')
    <div class="mt-2 mb-4">
        <h2 class="pb-2">{{ __('Welcome back') . ', ' }} {{ Auth::guard('seller')->user()->username . '!' }}</h2>
    </div>

    @if (Auth::guard('seller')->user()->status == 0 && $admin_setting->seller_admin_approval == 1)
        <div class="mt-2 mb-4">
            <div class="alert alert-danger text-dark">
                {{ $admin_setting->admin_approval_notice != null
                    ? $admin_setting->admin_approval_notice
                    : __('Your account is deactive') . '!' }}
            </div>
        </div>
    @endif

    @php
        $seller = Auth::guard('seller')->user();
        $package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller->id);
    @endphp

    @if (is_null($package))
        @php
            $pendingMemb = \App\Models\Membership::query()
                ->where([['seller_id', '=', $seller->id], ['status', 0]])
                ->whereYear('start_date', '<>', '9999')
                ->orderBy('id', 'DESC')
                ->first();
            $pendingPackage = isset($pendingMemb)
                ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id)
                : null;
        @endphp

        @if ($pendingPackage)
            <div class="alert alert-warning text-dark">
                {{ __('You have requested a package which needs an action (Approval / Rejection) by admin') . '. ' .__('You will be notified via mail once an action is taken') .'.' }}
            </div>
            <div class="alert alert-warning text-dark">
                <strong>{{ __('Pending Package') . ':' }} </strong> {{ __($pendingPackage->title) }}
                <span class="badge badge-secondary">{{ __($pendingPackage->term) }}</span>
                <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
            </div>
        @else
            <div class="alert alert-warning text-dark">
                {{ __('Your membership is expired') .'. ' . __('Please purchase a new package / extend the current package') .'.' }}
            </div>
        @endif
    @else
        <div class="row justify-content-center align-items-center mb-1">
            <div class="col-12">
                <div class="alert border-left border-primary text-dark">
                    @if ($package_count >= 2 && $next_membership)
                        @if ($next_membership->status == 0)
                            <strong
                                class="text-danger">{{ __('You have requested a package which needs an action (Approval / Rejection) by admin') .
                                    '. ' . __('You will be notified via mail once an action is taken') .'.' }}</strong>
                            <br>
                        @elseif ($next_membership->status == 1)
                            <strong
                                class="text-danger">{{ __('You have another package to activate after the current package expires') .'. ' .
                                    __('You cannot purchase / extend any package') .
                                    ', ' . __('until the next package is activated') .'.' }}</strong>
                            <br>
                        @endif
                    @endif

                    <strong>{{ __('Current Package') . ':' }} </strong> {{ __($current_package->title) }}
                    <span class="badge badge-secondary">{{ __($current_package->term) }}</span>
                    @if ($current_membership->is_trial == 1)
                        ({{ __('Expire Date') . ':' }}
                        {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                        <span class="badge badge-primary">{{ __('Trial') }}</span>
                    @else
                        ({{ __('Expire Date') . ':' }}
                        {{ $current_package->term === 'lifetime'
                            ? __('Lifetime')
                            : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                    @endif

                    @if ($package_count >= 2 && $next_package)
                        <div>
                            <strong>{{ __('Next Package To Activate') . ':' }} </strong> {{ __($next_package->title) }}
                            <span class="badge badge-secondary">{{ __($next_package->term) }}</span>
                            @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                                (
                                {{ __('Activation Date') . ':' }}
                                {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
                                {{ __('Expire Date') . ':' }}
                                {{ $next_package->term === 'lifetime'
                                    ? __('Lifetime')
                                    : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }}
                                )
                            @endif
                            @if ($next_membership->status == 0)
                                <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- dashboard information start --}}
    <div class="row dashboard-items">
        <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-secondary card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fal fa-dollar-sign"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('My Balance') }}</p>
                                    <h4 class="card-title ltr">
                                        {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                                        {{ Auth::guard('seller')->user()->amount }}
                                        {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('vendor.transaction', ['language' => $defaultLang->code]) }}">
                <div class="card card-stats card-warning card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-exchange"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">
                                        {{ $transactions > 1 ? __('Transactions') : __('Transaction') }}
                                    </p>
                                    <h4 class="card-title">
                                        {{ $transactions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}">
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
                                    <p class="card-category">{{ $spaces > 1 ? __('Spaces') : __('Space') }}</p>
                                    <h4 class="card-title">{{ $spaces }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-md-4">
            <a href="{{ route('vendor.booking_record.index', ['language' => $defaultLang->code]) }}">
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
                                    <p class="card-category">
                                        {{ $spaceBookings > 1
                                            ? __('Space Bookings')
                                            : __('Space Booking') }}
                                    </p>
                                    <h4 class="card-title">{{ $spaceBookings }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @if ($current_package != '[]')
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('vendor.subscription_log', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">
                                            {{ __('Subscription Log') }}
                                        </p>
                                        <h4 class="card-title">{{ $payment_logs }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if ($canAccessSupportTicket == true)
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('vendor.support_ticket', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-dark card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-headset"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">
                                            {{ $support_tickets_count > 1 ? __('Support Tickets') : __('Support Tickets') }}
                                        </p>
                                        <h4 class="card-title">{{ $support_tickets_count }}</h4>
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
                    <div class="card-title">{{ __('Month Wise Total Incomes') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="serviceIncomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Number of Space Bookings') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="serviceOrderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/admin/js/chart.min.js') }}"></script>

    <script>
        const monthArr = {!! json_encode($months) !!};
        const spaceBookingArr = {!! json_encode($totalSpaceBookings) !!};
        const spaceIncomeArr = {!! json_encode($totalSpaceIncomes) !!};
        var monthlySpaceBookings = "{{ __('Monthly Space Bookings') }}";
        var monthWiseTotalIncome = "{{ __('Month Wise Total Incomes') }}";
    </script>

    <script type="text/javascript" src="{{ asset('assets/admin/js/my-chart.js') }}"></script>
@endsection
