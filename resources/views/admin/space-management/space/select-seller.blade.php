@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
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
                <a href="#">{{ __('Space Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Spaces') }}</a>
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
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward mdb_12"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <form action="{{ route('admin.space_management.space.create') }}" method="get">
                    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                    <input type="hidden" name="type" value="" id="spaceType">
                    <div class="card-body pt-5 pb-1">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label for="">{{ __('Vendor') }}</label>
                                    <select name="seller_id" class="form-control select2 featureTypeForSpace">
                                        <option value="admin" selected>{{ __('Please Select') }}</option>
                                        @foreach ($sellers as $seller)
                                            <option value="{{ $seller->id }}">{{ $seller->username }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-warning">
                                        {{ __('if you do not select any vendor, then this space will be listed for admin') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body  pb-5" id="featureSpaceTypeContainer">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label for="">{{ __('Space Type') . '*' }}</label>
                                    <select name="type" class="form-control select2" id="featureSpaceType">
                                        <option value="" selected disabled>{{ __('Select a space type') }}</option>
                                        <option value="fixed_time_slot_rental">{{ __('Fixed Timeslot Rental') }}</option>
                                        <option value="hourly_rental">{{ __('Hourly Rental') }}</option>
                                        <option value="multi_day_rental">{{ __('Multi-Day Rental') }}</option>
                                    </select>
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
@section('script')
    <script>
        var selectSpace = "{{ __('Select a space type') }}"
        var getSpaceType = "{{ route('admin.space_management.space.select_space_type') }}";
    </script>
@endsection
