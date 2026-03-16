<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add City') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @php
                if ($settings->theme_version == 1) {
                    $sizeInfo = '750×855 px';
                } elseif ($settings->theme_version == 2) {
                    $sizeInfo = '750×450 px';
                } elseif ($settings->theme_version == 3) {
                    $sizeInfo = '1024×683 px';
                } else {
                    $sizeInfo = '750×855 px';
                }
            @endphp

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form create"
                    action="{{ route('admin.location_management.city.store') }}" method="post">
                    @csrf

                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Language') . '*' }}</label>
                                <select name="language_id" class="form-control ">
                                    <option selected disabled>{{ __('Select a Language') }}</option>
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                                <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 create-country-dropdown-container create-city-dropdown-container d-none">
                            <div class="form-group">
                                <label for="">{{ __('Country Name') . '*' }}</label>
                                <select name="country_id" class="form-control select2">
                                    <option selected disabled>{{ __('Select a Country') }}</option>

                                </select>
                                <p id="err_country_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 create-state-dropdown-container d-none">
                            <div class="form-group">
                                <label for="">{{ __('State Name')}}<span class="stateIsRequired">*</span></label>
                                <select name="state_id" class="form-control select2">
                                    <option selected disabled>{{ __('Select a State') }}</option>

                                </select>
                                <p id="err_state_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('City Name') . '*' }}</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ __('Enter City Name') }}">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('City Status') . '*' }}</label>
                                <select name="status" class="form-control">
                                    <option selected disabled>{{ __('Select a Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactive') }}</option>
                                </select>
                                <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="form-group">
                            <label for="">{{ __('Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">
                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                            </div>

                            <div class="mt-3">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="img-input" name="image">
                                </div>
                            </div>
                            <p class="text-warning mt-2">{{ __('Image Size') . ':' }} <span
                                    dir="ltr">{{ $sizeInfo }}</span></p>
                            <p id="err_image" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
