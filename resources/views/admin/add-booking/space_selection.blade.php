@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Booking') }}</h4>
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
                <a href="#">{{ __('Bookings & Requests') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Booking') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Select Vendor') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Select Vendor') }}</div>
                </div>
                 <div class="row pt-4 card-body">
                    <div class="alert alert-warning col-lg-6 offset-lg-3" role="alert">
                        <span class="text-warning">{{ __('Cron Job Command') . ': ' }}</span> </br> <code>Curl -sS
                            {{ route('process.bookings') }}</code> <span
                            class="text-warning">{{ '(' . __('Run every 5 minutes for near-real-time processing') . ')' }}</span> </br>
                        <span class="text-warning">{{ __('Purpose') . ':' }}</span> </br>
                        <span
                            class="text-warning">{{ __('Sends invoice emails to customers after booking') . '. ' . __("Without this cron job, emails won’t send") . '.' }}</span>
                    </div>
                </div>
                <form action="{{ route('admin.add_booking.index', ['language' => $defaultLang->code]) }}"  method="GET">
                    <div class="pt-2 pb-5">
                        <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                        <input type="hidden" name="type" value="" id="spaceType">
                        <div class="card-body p-0 ">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="">{{ __('Vendor') }}</label>
                                        <select name="seller_id" class="form-control select2 vendorTypeForAddBooking">
                                            <option value="admin" selected>{{ __('Please Select') }}</option>
                                            @foreach ($sellers as $seller)
                                                <option value="{{ $seller->id }}">{{ $seller->username }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-warning mb-0">
                                            {{ __('if you do not select any vendor, then this space will be listed for admin') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 " id="featureSpaceTypeContainerForAddBooking">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="">{{ __('Space Type') . '*' }}</label>
                                        <select name="type" class="form-control select2" id="featureSpaceTypeForAddBooking">
                                            <option value="" selected disabled>{{ __('Select a space type') }}</option>
                                            <option value="fixed_time_slot_rental">{{ __('Fixed Timeslot Rental') }}</option>
                                            <option value="hourly_rental">{{ __('Hourly Rental') }}</option>
                                            <option value="multi_day_rental">{{ __('Multi-Day Rental') }}</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
    
                                </div>
                            </div>
                        </div>
    
                        <div class="card-body p-0 " id="spaceContainerForAddBooking">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="">{{ __('Spaces') . '*' }}</label>
                                        <select name="space" class="form-control select2" id="spaceId">
                                            <option value="" selected disabled>{{ __('Select a space') }}</option>
                                            @if (isset($spaces) && $spaces->isNotEmpty())
                                                @foreach ($spaces as $space)
                                                    <option value="{{ @$space->id }}">{{ @$space->space_title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('space')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="submitButton"
                                        class="btn btn-success">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('variable')
    <script>
        var selectSpace = "{{ __('Select a space type') }}"
        var getSpaceType = "{{ route('admin.space_management.space.select_space_type') }}";
        var getSpaceUrl = "{{ route('admin.add_booking.get_space') }}";
        var noResultFound = "{{ __('No results found') }}";
        var adminDashboard = "admin_dashboard";
    </script>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
@endsection
