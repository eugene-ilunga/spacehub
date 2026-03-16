<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title">{{ __('Footer Logo') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">{{ __('Logo') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (!empty($footerLogo->footer_logo))
                                <img src="{{ asset('assets/img/' . $footerLogo->footer_logo) }}" alt="logo"
                                    class="uploaded-img">
                            @else
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="img-input" name="footer_logo">
                            </div>
                        </div>
                        <p class="text-warning small mt-2">{{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">{{ '124×24 px' }}</strong></p>
                        @if ($errors->has('footer_logo'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('footer_logo') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="">{{ __('Background Image') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (empty($footerLogo->footer_section_bg_img))
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                    class="uploaded-background-img" id="footer_section_bg_img_preview">
                            @else
                                <img src="{{ asset('assets/img/' . $footerLogo->footer_section_bg_img) }}"
                                    alt="image" class="uploaded-background-img" id="footer_section_bg_img_preview">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="background-img-input-preview" name="footer_section_bg_img"
                                    data-preview-id="footer_section_bg_img_preview">
                                @error('footer_section_bg_img')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-warning small mt-2">{{ '*' . __('Recommended Image Size') . ':' }}
                                <strong dir="ltr">1920×600 px</strong></p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
