<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card-title">{{ __('Hero Section Information') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-lg-8  @if ($settings->theme_version != 3) offset-lg-2 @endif">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="">{{ __('Background Image') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            @if (empty($heroImgs->hero_section_background_img))
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                    class="uploaded-background-img" id="hero_section_bg_img_preview">
                            @else
                                <img src="{{ asset('assets/img/' . $heroImgs->hero_section_background_img) }}"
                                    alt="image" class="uploaded-background-img" id="hero_section_bg_img_preview">
                            @endif
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="background-img-input-preview"
                                    name="hero_section_background_img" data-preview-id="hero_section_bg_img_preview">
                                @error('hero_section_background_img')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-warning small mt-2">
                                {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">1920×800 px</strong>
                            </p>

                        </div>

                    </div>
                    @if ($settings->theme_version == 2)
                        <div class="form-group col-lg-6">
                            <label for="">{{ __('Foreground Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">
                                @if (empty($heroImgs->hero_section_foreground_img))
                                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                        class="uploaded-img">
                                @else
                                    <img src="{{ asset('assets/img/' . $heroImgs->hero_section_foreground_img) }}"
                                        alt="image" class="uploaded-img">
                                @endif
                            </div>

                            <div class="mt-3">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="img-input" name="hero_section_foreground_img">
                                    @error('hero_section_foreground_img')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">800×670 px</strong>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($settings->theme_version == 3)
                    <div class="row">
                        {{-- Foreground Image Right --}}
                        <div class="form-group col-md-6">
                            <label>{{ __('Foreground Image Right') . '*' }}</label>
                            <div class="thumb-preview mb-2">
                                <img id="hero_section_foreground_img_right_preview"
                                    src="{{ !empty($heroImgs->hero_section_foreground_img_theme_3)
                                        ? asset('assets/img/' . $heroImgs->hero_section_foreground_img_theme_3)
                                        : asset('assets/img/noimage.jpg') }}"
                                    alt="image" class="uploaded-img-1">
                            </div>
                            <div class="mt-2">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="background-img-input-preview"
                                        name="hero_section_foreground_img_theme_3"
                                        data-preview-id="hero_section_foreground_img_right_preview">
                                </div>
                                @error('hero_section_foreground_img_theme_3')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">415×580 px</strong>
                                </p>
                            </div>
                        </div>

                        {{-- Foreground Image Left --}}
                        <div class="form-group col-md-6">
                            <label>{{ __('Foreground Image Left') . '*' }}</label>
                            <div class="thumb-preview mb-2">
                                <img id="hero_section_foreground_img_left_preview"
                                    src="{{ !empty($heroImgs->hero_section_foreground_img_theme_3_left)
                                        ? asset('assets/img/' . $heroImgs->hero_section_foreground_img_theme_3_left)
                                        : asset('assets/img/noimage.jpg') }}"
                                    alt="image" class="uploaded-img-1">
                            </div>
                            <div class="mt-2">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="background-img-input-preview"
                                        name="hero_section_foreground_img_theme_3_left"
                                        data-preview-id="hero_section_foreground_img_left_preview">
                                </div>
                                @error('hero_section_foreground_img_left')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                                <p class="text-warning small mt-2">
                                    {{ '*' . __('Recommended Image Size') . ':' }} <strong dir="ltr">415×580 px</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">{{ __('Title') }}</label>
                    <input type="text" class="form-control" name="hero_section_title"
                        placeholder="{{ __('Enter title') }}"
                        value="@if (!empty($homePageInfo)) {{ $homePageInfo->hero_section_title }} @endif">
                    @error('hero_section_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">{{ __('Text') }}</label>
                    <textarea class="form-control" name="hero_section_text" rows="5" placeholder="{{ __('Enter text') }}">
                    @if (!empty($homePageInfo))
                        {{ $homePageInfo->hero_section_text }}
                    @endif
                    </textarea>
                    @error('hero_section_text')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
