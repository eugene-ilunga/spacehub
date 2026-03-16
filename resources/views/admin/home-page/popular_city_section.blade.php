<div class=" col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card-title">{{ __('Popular Cities Section Information') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{ __('Title') . '*' }}</label>
                        <input type="text" class="form-control" name="popular_city_section_title"
                            value="{{ empty($homePageInfo) ? '' : $homePageInfo->popular_city_section_title }}" placeholder="{{ __('Enter title') }}">
                            @error('popular_city_section_title')
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Text') . '*' }}</label>
                        <textarea class="form-control summernote" name="popular_city_section_text" placeholder="{{ __('Enter text') }}" data-height="300">{{ empty($homePageInfo) ? '' : $homePageInfo->popular_city_section_text }}</textarea>
                        @error('popular_city_section_text')
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Button Name')  . '*' }}</label>
                                <input type="text" class="form-control" name="popular_city_section_button_name"
                                    placeholder="{{ __('Enter Button Name') }}"
                                    value="{{ empty($homePageInfo) ? '' : $homePageInfo->popular_city_section_button_name }}">
                                    @error('popular_city_section_button_name')
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
