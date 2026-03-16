@extends('vendors.layout')

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
                <a href="#">{{ __('Booking Management') }}</a>
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
                <a href="#">{{ __('Select Space') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Select Space') }}</div>
                </div>
                <form action="{{ route('vendor.add_booking.index', ['language' => $defaultLang->code]) }}" method="GET">
                    <div class="pt-5 pb-5">
                        <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                        <input type="hidden" name="type" value="" id="spaceType">
                        <input type="hidden" name="vendorTypeForAddBooking" class="vendorTypeForAddBooking"
                            value="{{ $seller_id }}">
                        <input type="hidden" name="seller_id" class="vendorTypeForAddBooking" value="{{ $seller_id }}">

                        <div class="card-body p-0 " id="featureSpaceTypeContainerForAddBooking">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="">{{ __('Space Type') . '*' }}</label>
                                        <select name="type" class="form-control select2"
                                            id="featureSpaceTypeForAddBooking">
                                            <option value="" selected disabled>{{ __('Select a space type') }}
                                            </option>
                                            @foreach ($features as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach

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
                                                    <option value="{{ @$space->id }}">{{ @$space->space_title }}
                                                    </option>
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
        var selectSpace = "{{ __('Select a space type') }}";
        var noResultFound = "{{ __('No results found') }}";
        var selectSpace = "{{ __('Select a Space') }}";
        var getSpaceUrl = "{{ route('vendor.add_booking.get_space') }}";
    </script>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
@endsection
