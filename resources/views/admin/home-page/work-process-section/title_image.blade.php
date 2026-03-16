<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="card-title">{{ __('Work Process Section Information') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($settings->theme_version == 2)
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">{{ __('Background Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">
                                @if (empty($homePageImages->work_process_background_img) ||
                                        !file_exists(public_path('assets/img/' . $homePageImages->work_process_background_img)))
                                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                        class="uploaded-background-img" id="work_process_background_img_preview">
                                @else
                                    <img src="{{ asset('assets/img/' . $homePageImages->work_process_background_img) }}"
                                        alt="image" class="uploaded-background-img"
                                        id="work_process_background_img_preview">
                                @endif
                            </div>

                            <div class="mt-3">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class=" background-img-input-preview"
                                        name="work_process_background_img"
                                        data-preview-id="work_process_background_img_preview">
                                    @error('work_process_background_img')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">1920×900 px</strong>
                                </p>
                            </div>
                            @error('work_process_background_img')
                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="workprocess_section_title"
                            value="{{ empty($homePageInfo->workprocess_section_title) ? '' : $homePageInfo->workprocess_section_title }}"
                            placeholder="{{ __('Enter title') }}">
                        @error('workprocess_section_title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
