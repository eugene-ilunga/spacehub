<div class="col-md-6">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-10">
          <div class="card-title">{{ __('Preloader Status') }}</div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-lg-12 offset-lg-12">
          <div class="form-group">
            <label for="">{{ __('Preloader Status') . '*' }}</label>
            <br>
            <div class="selectgroup w-100">
              <label class="selectgroup-item">
                <input type="radio" name="preloader_status" value="1" class="selectgroup-input" {{
                  isset($data->preloader_status) && $data->preloader_status == 1 ? 'checked' : '' }}>
                <span class="selectgroup-button">{{ __('Active') }}</span>
              </label>
              <label class="selectgroup-item">
                <input type="radio" name="preloader_status" value="0" class="selectgroup-input" {{
                  isset($data->preloader_status) && $data->preloader_status == 0 ? 'checked' : '' }}>
                <span class="selectgroup-button">{{ __('Deactive') }}</span>
              </label>
            </div>
            @if ($errors->has('preloader_status'))
            <p class="mt-2 mb-0 text-danger">{{ $errors->first('preloader_status') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
