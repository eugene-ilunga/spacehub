
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title">{{ __('Category Section Title') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Title') }}</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ empty($data->title) ? '' : $data->title }}"
                                                placeholder="{{ __('Enter Title') }}">
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
