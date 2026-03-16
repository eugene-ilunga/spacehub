<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ __('Banner Section Information') }}</div>
        </div>

        <div class="card-body">
            {{-- Images Row --}}
            <div class="row">
                {{-- Foreground Image --}}
                @if ($settings->theme_version != 2)
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>{{ __('Foreground Image') . '*' }}</label>
                            <div class="thumb-preview mb-2 ">
                                <img src="{{ !empty($homePageImages->banner_section_foreground_img) &&
                                file_exists(public_path('assets/img/' . $homePageImages->banner_section_foreground_img))
                                    ? asset('assets/img/' . $homePageImages->banner_section_foreground_img)
                                    : asset('assets/img/noimage.jpg') }}"
                                    alt="image" class="uploaded-background-img" id="banner_section_bg_img_preview">
                            </div>
                            <div>
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="background-img-input-preview"
                                        name="banner_section_foreground_img"
                                        data-preview-id="banner_section_bg_img_preview">
                                </div>
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">750×578 px</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Background Image --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Background Image') . '*' }}</label>
                        <div class="thumb-preview">
                            <img src="{{ !empty($homePageImages->banner_section_bg_img)
                                ? asset('assets/img/' . $homePageImages->banner_section_bg_img)
                                : asset('assets/img/noimage.jpg') }}"
                                alt="image" class="uploaded-background-img" id="banner_section_bg_img_preview_1">
                        </div>
                        <div class="mt-2">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="background-img-input-preview" name="banner_section_bg_img"
                                    data-preview-id="banner_section_bg_img_preview_1">
                            </div>
                            <p class="text-warning small mt-2">
                                {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">1920×600 px</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Text Inputs Row --}}
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Title') . '*' }}</label>
                        <input type="text" class="form-control" name="banner_section_title"
                            value="{{ $homePageInfo->banner_section_title ?? '' }}"
                            placeholder="{{ __('Enter title') }}">
                        @error('banner_section_title')
                            <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Button Name') }}</label>
                        <input type="text" class="form-control" name="banner_section_button_text"
                            value="{{ $homePageInfo->banner_section_button_text ?? '' }}"
                            placeholder="{{ __('Enter Button Name') }}">
                        @error('banner_section_button_text')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
