
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Website Appearance') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label>{{ __('Primary Color') . '*' }}</label>
                        <input class="jscolor form-control" name="primary_color"
                            value="{{ !empty($data) ? $data->primary_color : '' }}">
                        @if ($errors->has('primary_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('primary_color') }}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Secondary Color') . '*' }}</label>
                        <input class="jscolor form-control" name="secondary_color"
                            value="{{ !empty($data) ? $data->secondary_color : '' }}">
                        @if ($errors->has('secondary_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('secondary_color') }}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Breadcrumb Section Overlay Color') . '*' }}</label>
                        <input class="jscolor form-control" name="breadcrumb_overlay_color"
                            value="{{ !empty($data) ? $data->breadcrumb_overlay_color : '' }}">
                        @if ($errors->has('breadcrumb_overlay_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_color') }}
                            </p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{ __('Breadcrumb Section Overlay Opacity') . '*' }}</label>
                        <input class="form-control" type="number" step="0.01" name="breadcrumb_overlay_opacity"
                            value="{{ $data->breadcrumb_overlay_opacity }}">
                        @if ($errors->has('breadcrumb_overlay_opacity'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_opacity') }}</p>
                        @endif
                        <p class="mt-2 mb-0 text-warning">
                            {{ __('This will decide the transparency level of the overlay color') . '.' }}
                            <br>
                            {{ __('Value must be between 0 to 1') . '.' }}<br>
                            {{ __('Transparency level will be lower with the increment of the value') . '.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
