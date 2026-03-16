<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Footer Content') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-9 offset-lg-1">
                    <div class="form-group">
                        <label>{{ __('Footer Text Color') . '*' }}</label>
                        <input class="jscolor form-control" name="footer_background_color"
                            value="{{ !is_null($data) ? $data->footer_background_color : '' }}">
                        <p id="err_footer_background_color" class="em text-danger mt-2 mb-0"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('About Company') . '*' }}</label>
                        <textarea class="form-control" name="about_company" rows="5" cols="80">{{ !is_null($data) ? $data->about_company : '' }}</textarea>
                        @error('about_company')
                            <p id="err_about_company" class="em text-danger mt-2 mb-0">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label>{{ __('Copyright Text') . '*' }}</label>
                        <textarea class="form-control" name="copyright_text" rows="3">{{ !is_null($data) ? $data->copyright_text : '' }}</textarea>
                        @error('copyright_text')
                             <p id="err_copyright_text" class="em text-danger mt-2 mb-0">{{ $message }}</p>
                        @enderror
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
