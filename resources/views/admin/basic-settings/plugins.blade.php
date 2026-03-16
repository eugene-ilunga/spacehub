@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Plugins') }}</h4>
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
                <a href="#">{{ __('Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Plugins') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">

        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_whatsapp') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('WhatsApp') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Status') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_status" value="1"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->whatsapp_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_status" value="0"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->whatsapp_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('whatsapp_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('whatsapp_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Number') . '*' }}</label>
                                    <input type="text" class="form-control" name="whatsapp_number"
                                        value="{{ !empty($data) ? $data->whatsapp_number : '' }}">

                                    @if ($errors->has('whatsapp_number'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('whatsapp_number') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Header Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="whatsapp_header_title"
                                        value="{{ !empty($data) ? $data->whatsapp_header_title : '' }}">

                                    @if ($errors->has('whatsapp_header_title'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('whatsapp_header_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Popup Status') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup_status" value="1"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->whatsapp_popup_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup_status" value="0"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->whatsapp_popup_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('whatsapp_popup_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('whatsapp_popup_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Popup Message') . '*' }}</label>
                                    <textarea class="form-control" name="whatsapp_popup_message" rows="3">{{ !empty($data) ? $data->whatsapp_popup_message : '' }}</textarea>

                                    @if ($errors->has('whatsapp_popup_message'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('whatsapp_popup_message') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_google_map_api_key') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('Google Map API Key') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Status') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_map_api_key_status" value="1"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->google_map_api_key_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_map_api_key_status" value="0"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->google_map_api_key_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                    <h5 class="mt-2 mb-1">{{ __('If the Google Maps API is disabled') . ':' }}</h5>
                                    <ul class="pl-20 mb-0">
                                        <li>
                                            <p class="mb-0 text-warning">
                                                {{ __('Address suggestions will not be available in the location search input') . '.' }}
                                            </p>
                                        </li>
                                        <li>
                                            <p class="mb-0 text-warning">
                                                {{ __('Radius-based location searches will be non-functional') . '.' }}
                                            </p>
                                        </li>
                                    </ul>

                                    @if ($errors->has('google_map_api_key_status'))
                                        <p class="mt-1 mb-0 text-danger">
                                            {{ $errors->first('google_map_api_key_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Google Map API Key') . '*' }}</label>
                                    <input type="text" class="form-control" name="google_map_api_key"
                                        value="{{ !empty($data) ? $data->google_map_api_key : '' }}">

                                    @if ($errors->has('google_map_api_key'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_map_api_key') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Radius') . ' ' }}{{ '(' . __('in meters') . ')' . '*' }}</label>
                                    <input type="text" class="form-control" name="google_map_radius"
                                        value="{{ $data->google_map_radius }}">
                                    <p class="mb-0 text-warning">
                                        {{ __('After a location is seached, all the available spaces which are located within this radius will be displayed') . '.' }}
                                    </p>

                                    @if ($errors->has('google_map_radius'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_map_radius') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_recaptcha') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('Google Recaptcha') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Status') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_recaptcha_status" value="1"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->google_recaptcha_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_recaptcha_status" value="0"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->google_recaptcha_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('google_recaptcha_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_recaptcha_status') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Site Key') . '*' }}</label>
                                    <input type="text" class="form-control" name="google_recaptcha_site_key"
                                        value="{{ !empty($data) ? $data->google_recaptcha_site_key : '' }}">

                                    @if ($errors->has('google_recaptcha_site_key'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_recaptcha_site_key') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Secret Key') . '*' }}</label>
                                    <input type="text" class="form-control" name="google_recaptcha_secret_key"
                                        value="{{ !empty($data) ? $data->google_recaptcha_secret_key : '' }}">

                                    @if ($errors->has('google_recaptcha_secret_key'))
                                        <p class="mt-1 mb-0 text-danger">
                                            {{ $errors->first('google_recaptcha_secret_key') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_disqus') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('Disqus') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Status') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="1"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->disqus_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="0"
                                                class="selectgroup-input"
                                                {{ !empty($data) && $data->disqus_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('disqus_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('disqus_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Short Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="disqus_short_name"
                                        value="{{ !empty($data) ? $data->disqus_short_name : '' }}">

                                    @if ($errors->has('disqus_short_name'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('disqus_short_name') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
