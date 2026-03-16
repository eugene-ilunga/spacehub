
<div class="col-md-6">
    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title ">{{ __('Information') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="form-group">
                        <label>{{ __('Website Title') . '*' }}</label>
                        <input type="text" class="form-control" name="website_title"
                            value="{{ !empty($data) ? $data->website_title : '' }}" placeholder="{{ __('Enter Website Title') }}">
                        @if ($errors->has('website_title'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('website_title') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" name="email_address"
                            value="{{ !empty($data) ? $data->email_address : '' }}" placeholder="{{ __('Enter Email Address') }}">
                        @if ($errors->has('email_address'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('email_address') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Contact Number') }}</label>
                        <input type="text" class="form-control" name="contact_number"
                            value="{{ !empty($data) ? $data->contact_number : '' }}" placeholder="{{ __('Enter Contact Number') }}">
                        @if ($errors->has('contact_number'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('contact_number') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <input type="text" class="form-control" name="address"
                            value="{{ !empty($data) ? $data->address : '' }}" placeholder="{{ __('Enter Address') }}">
                        @if ($errors->has('address'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('address') }}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Latitude') }}</label>
                        <input type="text" class="form-control" name="latitude"
                            value="{{ !empty($data) ? $data->latitude : '' }}" placeholder="{{ __('Enter Latitude') }}">
                        @if ($errors->has('latitude'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('latitude') }}</p>
                        @endif
                        <p class="mt-2 mb-0 text-warning">
                            {{ __('The value of the latitude will be helpful to show your location in the map') . '.' }}
                        </p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Longitude') }}</label>
                        <input type="text" class="form-control" name="longitude"
                            value="{{ !empty($data) ? $data->longitude : '' }}" placeholder="{{ __('Enter longitude') }}">
                        @if ($errors->has('longitude'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('longitude') }}</p>
                        @endif
                        <p class="mt-2 mb-0 text-warning">
                            {{ __('The value of the longitude will be helpful to show your location in the map') . '.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
