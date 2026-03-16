
 <div class="col-md-12">
            <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Theme & Home') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="row mt-2 justify-content-center">
                                        <div class="col-md-3">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="1"
                                                       class="imagecheck-input"
                                                        {{ !empty($data) && $data->theme_version == 1 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/img/themes/1.png') }}" alt="theme 1"
                                                         class="imagecheck-image">
                                                </figure>
                                            </label>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="2"
                                                       class="imagecheck-input"
                                                        {{ !empty($data) && $data->theme_version == 2 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/img/themes/2.png') }}" alt="theme 2"
                                                         class="imagecheck-image">
                                                </figure>
                                            </label>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="3"
                                                       class="imagecheck-input"
                                                        {{ !empty($data) && $data->theme_version == 3 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/img/themes/3.png') }}" alt="theme 3"
                                                         class="imagecheck-image">
                                                </figure>
                                            </label>
                                        </div>

                                        @if ($errors->has('theme_version'))
                                            <p class="mb-0 ml-3 text-danger">{{ $errors->first('theme_version') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
