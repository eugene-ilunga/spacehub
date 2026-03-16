<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Update Favicon') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label for="">{{ __('Favicon') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (!empty($data->favicon))
                            <img src="{{ asset('assets/img/' . $data->favicon) }}" alt="favicon" class="uploaded-img">
                            @else
                            <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="img-input" name="favicon">
                            </div>
                        </div>
                        @if ($errors->has('favicon'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('favicon') }}</p>
                        @endif
                        <p class="text-warning mt-2 mb-0">{{ __('Upload 40X40 pixel size image or squre size image for best quality') . '.'}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
