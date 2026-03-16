<div class="col-md-6">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-10">
          <div class="card-title">{{ __('Update Preloader') }}</div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-lg-6 offset-lg-3">
          <div class="form-group">
            <label for="">{{ __('Preloader') }} {{ $data->preloader_status == 1 ? '*' : '' }}</label>
            <br>
            <div class="thumb-preview">
              @if (!empty($data->preloader))
              <img src="{{ asset('assets/img/' . $data->preloader) }}" alt="{{ __('preloader') }}"
                class="uploaded-preloader-img">
              @else
              <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-preloader-img">
              @endif
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="preloader-img-input" name="preloader">
              </div>
            </div>
            @if ($errors->has('preloader'))
            <p class="mt-2 mb-0 text-danger">{{ $errors->first('preloader') }}</p>
            @endif
            <p class="text-warning mt-2 mb-0">{{ 'JPG, PNG, JPEG, GIF, SVG ' . ' ' .__('images are allowed') . '.'}}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
