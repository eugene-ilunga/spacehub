<div class="modal fade spaceEditModal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true" data-reset="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit City') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                $countries = \App\Models\Country::where('language_id', $language->id)->get();
                $states = \App\Models\State::where('language_id', $language->id)->get();

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
                <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.location_management.city.update') }}"
                    method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="row">
                        <div class="col-md-12 edit-country-dropdown-container edit-city-dropdown-container">
                            <div class="form-group">
                                <label for="">{{ __('Country Name') . '*' }}</label>
                                <select name="country_id" class="form-control select2" id="in_country_id" >
                                    <option selected disabled>{{ __('Select a Country') }}</option>

                                </select>
                                <p id="editErr_country_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 edit-state-dropdown-container d-none">
                            <div class="form-group">
                                <label for="">{{ __('State Name') }} <span class="stateIsRequired">*</span></label>
                                <select name="state_id" class="form-control select2" id="in_state_id" >
                                    <option selected disabled>{{ __('Select a State') }}</option>

                                </select>
                                <p id="editErr_state_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('City Name') . '*' }}</label>
                        <input type="text" class="form-control" id="in_name" name="name"
                            placeholder="{{ __('Enter City Name') }}">
                        <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('City Status') . '*' }}</label>
                                <select name="status" class="form-control" id="in_status">
                                    <option selected disabled>{{ __('Select a Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactive') }}</option>
                                </select>
                                <p id="editErr_status" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="form-group">
                            <label for="">{{ __('Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">

                                <img src=" " alt="..." class="uploaded-img in_image">
                            </div>

                            <div class="mt-3">
                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="img-input" name="image">
                                </div>
                            </div>
                            <p class="text-warning mt-2">{{ __('Image Size') . ':' }} <span
                                    dir="ltr">{{ $sizeInfo }}</span></p>
                            <p id="editErr_image" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
                    {{ __('Update') }}
                </button>
            </div>
        </div>
    </div>

</div>
