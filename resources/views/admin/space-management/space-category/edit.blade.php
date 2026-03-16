<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Space Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form"
                    action="{{ route('admin.space_management.space-category.update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Image') . '*' }}</label>
                                <br>
                                <div class="thumb-preview">
                                    <img src="" alt="..." class="uploaded-img in_image">
                                </div>

                                <div class="mt-3">
                                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                                        {{ __('Choose Image') }}
                                        <input type="file" class="img-input" name="icon_image">
                                    </div>
                                </div>
                                <p class="text-warning mt-2">{{ __('Image Size') . ':' }} <span dir="ltr">{{'70 x 70 px' }}</span></p>
                                <p id="editErr_icon_image" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        @if ($themeVersion->theme_version == 2)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Background Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="" alt="..."
                                            class="uploaded-background-img uploaded-img ">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="background-img-input" name="bg_image">
                                        </div>
                                    </div>
                                    <p class="text-warning mt-2">{{ __('Image Size') . ':' }} <span
                                            dir="ltr">{{ '750 x 855 px' }}</span></p>
                                    <p id="editErr_bg_image" class="mt-2 mb-0 text-danger em"></p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Category Icon') . '*' }}</label>
                                <div class="btn-group d-block">
                                    <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                                        <i  class="" id="in_icon"></i>
                                    </button>
                                    <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="fa-car" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu"></div>
                                </div>

                                <input type="hidden" id="editInputIcon" name="icon">
                                <p id="editErr_icon" class="mt-1 mb-0 text-danger em"></p>

                                <div class="text-warning mt-2">
                                    <small>{{ __('Click on the dropdown icon to select a icon') .'.' }}</small>
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="form-group">
                        <label for="">{{ __('Category Name') . '*' }}</label>
                        <input type="text" id="in_name" class="form-control" name="name"
                            placeholder="{{ __('Enter Name') }}">
                        <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Category Description') }}</label>
                        <input type="text" id="in_category_description" class="form-control"
                            name="category_description" placeholder="{{ __('Enter category description') }}">
                        <p id="editErr_category_description" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Category Status') . '*' }}</label>
                                <select name="status" id="in_status" class="form-control">
                                    <option disabled>{{ __('Select a Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactive') }}</option>
                                </select>
                                <p id="editErr_status" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Category Serial Number') . '*' }}</label>
                                <input type="number" id="in_serial_number" class="form-control"
                                    name="serial_number" placeholder="{{ __('Enter Serial Number') }}">
                                <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <p class="text-warning ml-2 mb-1">
                            <small>{{ '*' . __('The higher the serial number is, the later the category will be shown') . '.'}}</small>
                        </p>
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
