@extends('vendors.layout')

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
                <a href="#">{{ __('Select Type') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Select Space Type') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('vendor.space_management.space.index') }}?language={{ $defaultLang->code }}">
                        <span class="btn-label">
                            <i class="fas fa-backward mdb_12"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <form
                    action="{{ route('vendor.space_management.space.create', ['type' => $type, 'language' => $defaultLang->code]) }}"
                    method="get">
                    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                    <div class="card-body  pb-5" id="featureSpaceTypeContainer">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label for="">{{ __('Space Type') . '*' }}</label>
                                    @if (isset($outputFeatureArray) && !empty($outputFeatureArray))
                                        <select name="type" class="form-control select2" id="featureSpaceType">
                                            <option value="" selected disabled>{{ __('Select a space type') }}
                                            </option>
                                            @foreach ($outputFeatureArray as $key => $feature)
                                                <option value="{{ @$key }}">{{ __(@$feature) }}</option>
                                            @endforeach

                                        </select>
                                    @endif
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
        var getSpaceType = "{{ route('admin.space_management.space.select_space_type') }}";
    </script>
@endsection
