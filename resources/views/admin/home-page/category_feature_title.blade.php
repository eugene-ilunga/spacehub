<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card-title">{{ __('Category and Feature Section Title') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Category Title') }}</label>
                                <input type="text" class="form-control" name="category_section_title"
                                    value="{{ empty($homePageInfo->category_section_title) ? '' : $homePageInfo->category_section_title }}"
                                    placeholder="{{ __('Enter Category Title') }}">
                                @error('category_section_title')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">{{ __('Feature Title') }}</label>
                                        <input type="text" class="form-control" name="featured_section_title"
                                            value="{{ empty($homePageInfo->featured_section_title) ? '' : $homePageInfo->featured_section_title }}"
                                            placeholder="{{ __('Enter Feature Title') }}">
                                        @error('featured_section_title')
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
    </div>
</div>
