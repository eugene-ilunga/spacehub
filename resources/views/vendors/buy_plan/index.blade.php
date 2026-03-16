@extends('vendors.layout')
@php
    Config::set('app.timezone', App\Models\BasicSettings\Basic::first()->timezone);
@endphp
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buy_plan.css') }}">
@endsection

@php
    $seller = Auth::guard('seller')->user();
    $package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller->id);
@endphp
@section('content')
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
                {{ __('You have requested a package which needs an action (Approval / Rejection) by admin') .
                    '. ' . __('You will be notified via mail once an action is taken') .
                    '.' }}
            </div>
            <div class="alert alert-warning text-dark">
                <strong>{{ __('Pending Package') . ':' }} </strong> {{ __($pendingPackage->title) }}
                <span class="badge badge-secondary">{{ __($pendingPackage->term) }}</span>
                <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
            </div>
        @else
            <div class="alert alert-warning text-dark">
                {{ __('Your membership is expired') .
                    '. ' .
                    __('Please purchase a new package / extend the current package') .
                    '.' }}
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
                                    '. ' .
                                    __('You will be notified via mail once an action is taken') .
                                    '.' }}</strong>
                            <br>
                        @elseif ($next_membership->status == 1)
                            <strong
                                class="text-danger">{{ __('You have another package to activate after the current package expires') .
                                    '. ' .
                                    __('You cannot purchase / extend any package, until the next package is activated') .
                                    '.' }}</strong>
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
    <div class="row mb-5 justify-content-center">
        @foreach ($packages as $key => $package)
            <div class="col-md-3 pr-md-0 mb-5">
                <div class="card-pricing2 @if (isset($current_package->id) && $current_package->id === $package->id) card-success @else card-primary @endif">
                    <div class="pricing-header">
                        <h3 class="fw-bold d-inline-block">
                            {{$package->price == 0 ? __('Free') : __($package->title) }}
                        </h3>
                        @if (isset($current_package->id) && $current_package->id === $package->id)
                            <h3 class="badge badge-danger d-inline-block float-right ml-2">{{ __('Current') }}</h3>
                        @endif
                        @if ($package_count >= 2)
                            @if ($next_package)
                                @if ($next_package->id == $package->id)
                                    <h3 class="badge badge-warning d-inline-block float-right ml-2">{{ __('Next') }}
                                    </h3>
                                @endif
                            @endif
                        @endif
                        <span class="sub-title"></span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span
                                class="amount">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                            <span class="month">/{{ __($package->term) }}</span>
                        </div>
                    </div>

                    <ul class="pricing-content">
                        @foreach ($allPfeatures as $feature)
                            @if ($feature == 'spaces')
                                <li>
                                    {{ $package->number_of_space == 1 ? __('Space Included') : __('Spaces Included') }}
                                    :
                                    {{ $package->number_of_space == 999999 ? __('Unlimited') : $package->number_of_space }}
                                </li>
                            @elseif ($feature == 'slider_images_per_space')
                                <li>
                                    {{ $package->number_of_slider_image_per_space == 1
                                        ? __('Slider Image Per Space')
                                        : __('Slider Images Per Space') }}
                                    :
                                    {{ $package->number_of_slider_image_per_space == 999999
                                        ? __('Unlimited')
                                        : __('Up to') . ' ' . $package->number_of_slider_image_per_space }}
                                </li>
                            @elseif ($feature == 'services_per_space')
                                <li>
                                    {{ $package->number_of_service_per_space == 1
                                        ? __('Service Per Space')
                                        : __('Services Per Space') }}
                                    :
                                    {{ $package->number_of_service_per_space == 999999
                                        ? __('Unlimited')
                                        : __('Up to') . ' ' . $package->number_of_service_per_space }}
                                </li>
                            @elseif ($feature == 'variants_per_service')
                                <li>
                                    {{ $package->number_of_option_per_service == 1
                                        ? __('Variant Per Service')
                                        : __('Variants Per Service') }}
                                    :
                                    {{ $package->number_of_option_per_service == 999999
                                        ? __('Unlimited')
                                        : __('Up to') . ' ' . $package->number_of_option_per_service }}
                                </li>
                            @elseif ($feature == 'amenities_per_space')
                                <li>
                                    {{ $package->number_of_amenities_per_space == 1
                                        ? __('Amenity Per Space')
                                        : __('Amenities Per Space') }}
                                    :
                                    {{ $package->number_of_amenities_per_space == 999999
                                        ? __('Unlimited')
                                        : __('Up to') . ' ' . $package->number_of_amenities_per_space }}
                                </li>

                                @php
                                    $features = json_decode($package->package_feature, true);
                                @endphp
                            @elseif ($feature == 'support_tickets')
                                <li
                                    class="{{ is_array($features) && in_array('Support Tickets', $features) ? ' ' : 'disable' }}">
                                    {{ __('Support Tickets') }}</li>
                            @elseif ($feature == 'add_booking')
                                <li
                                    class="{{ is_array($features) && in_array('Add Booking', $features) ? ' ' : 'disable' }}">
                                    {{ __('Manual Booking Entry from Dashboard') }}</li>
                            @elseif ($feature == 'fixed_timeslot_rental')
                                <li
                                    class="{{ is_array($features) && in_array('Fixed Timeslot Rental', $features) ? ' ' : 'disable' }}">
                                    {{ __('Fixed Timeslot Rental') }}</li>
                            @elseif ($feature == 'hourly_rental')
                                <li
                                    class="{{ is_array($features) && in_array('Hourly Rental', $features) ? ' ' : 'disable' }}">
                                    {{ __('Hourly Rental') }}</li>
                            @elseif ($feature == 'multi_day_rental')
                                <li
                                    class="{{ is_array($features) && in_array('Multi Day Rental', $features) ? ' ' : 'disable' }}">
                                    {{ __('Multi-Day Rental') }}</li>
                            @endif
                        @endforeach

                        @if ($package->custom_features != '')
                            @php
                                $features = explode("\n", $package->custom_features);
                            @endphp
                            @if (count($features) > 0)
                                @foreach ($features as $key => $value)
                                    <li>{{ $value }} </li>
                                @endforeach
                            @endif
                        @endif
                    </ul>
                    @php
                        $hasPendingMemb = \App\Http\Helpers\SellerPermissionHelper::hasPendingMembership(Auth::id());
                    @endphp

                    @if ($package_count < 2 && !$hasPendingMemb)
                        <div class="px-4">
                            @if (isset($current_package->id) && $current_package->id === $package->id)
                                @if ($package->term != 'lifetime' || $current_membership->is_trial == 1)
                                    <a href="{{ route('vendor.plan.extend.checkout', [$package->id, 'language' => $defaultLang->code]) }}"
                                        class="btn btn-success btn-lg w-75 fw-bold mb-3">{{ __('Extend') }}</a>
                                @endif
                            @else
                                <!-- Buy Now Button -->
                                <a href="javascript:void(0);" id="buyNowButton"
                                    class="btn btn-primary btn-block btn-lg fw-bold mb-3 buy-now-button"
                                    data-package_id="{{ $package->id }}" data-lang_code="{{ $defaultLang->code }}">
                                    {{ __('Buy Now') }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="packageMismatchModal" tabindex="-1" aria-labelledby="packageMismatchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageMismatchModalLabel">{{ __('Package Mismatch') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body large-font">
                    {{ @$message }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <a href="#" id="proceedToCheckout" class="btn btn-primary">{{ __('Proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        'use strict'
        var previousPackageId = '{{ @$previousPackageId }}';
        var buyPlanUrl = "{{ route('vendor.plan.checkout') }}";
    </script>

    <script type="text/javascript" src="{{ asset('assets/admin/js/mismatch-package.js') }}"></script>
@endsection
