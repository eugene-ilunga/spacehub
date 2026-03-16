<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="card-title">{{ __('Testimonial Section Information') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($settings->theme_version == 1)
                    <div class="col-lg-6 ">
                        <div class="form-group">
                            <label for="">{{ __('Background Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">
                                @if (empty($homePageImages->testimonial_bg_img) ||
                                        !file_exists(public_path('assets/img/' . $homePageImages->testimonial_bg_img)))
                                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                        class="uploaded-background-img" id="testimonial_bg_img_preview">
                                @else
                                    <img src="{{ asset('assets/img/' . $homePageImages->testimonial_bg_img) }}"
                                        alt="image" class="uploaded-background-img" id="testimonial_bg_img_preview">
                                @endif
                            </div>

                            <div class="mt-3">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="background-img-input-preview" name="testimonial_bg_img"
                                        data-preview-id="testimonial_bg_img_preview">
                                    @error('testimonial_bg_img')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">1440×600 px</strong>
                                </p>
                            </div>

                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="testimonial_title"
                            value="{{ empty($homePageInfo->testimonial_title) ? '' : $homePageInfo->testimonial_title }}"
                            placeholder="{{ __('Enter title') }}">
                        @error('testimonial_title')
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
