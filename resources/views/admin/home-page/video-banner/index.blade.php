<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="card-title">{{ __('Video Section Information') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">{{ __('Background Image') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (empty($homePageImages->video_banner_section_image) ||
                                    !file_exists(public_path('assets/img/' . $homePageImages->video_banner_section_image)))
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                    class="uploaded-background-img" id="video_banner_section_bg_img_preview">
                            @else
                                <img src="{{ asset('assets/img/' . $homePageImages->video_banner_section_image) }}"
                                    alt="image" class="uploaded-background-img"
                                    id="video_banner_section_bg_img_preview">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="background-img-input-preview"
                                    name="video_banner_section_image"
                                    data-preview-id="video_banner_section_bg_img_preview">
                                @error('video_banner_section_image')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-warning small mt-2">
                                {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">1920×600 px</strong>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{ __('Video Link') }}</label>
                        <input type="url" class="form-control" name="video_banner_video_link"
                            value="{{ empty($homePageInfo->video_banner_video_link) ? '' : $homePageInfo->video_banner_video_link }}"
                            placeholder="{{ __('Enter Video Link') }}">
                        @error('video_banner_video_link')
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
