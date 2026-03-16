<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Logo') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label for="">{{ __('Website Logo') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (!empty($data->logo))
                                <img src="{{ asset('assets/img/' . $data->logo) }}" alt="logo"
                                    class="uploaded-img-logo">
                            @else
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                    class="uploaded-img-logo">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="img-input-logo" name="logo">
                            </div>
                        </div>
                        @if ($errors->has('logo'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('logo') }}</p>
                        @endif
                        <p class="text-warning mt-2 mb-0">
                            {{ __('Image Size') . ': ' .' ' . '133X32 px' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
