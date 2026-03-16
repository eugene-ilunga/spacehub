<div class="modal fade" id="createModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Space Subcategory') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form create"
                    action="{{ route('admin.space_management.sub-category.store') }}" method="post">
                    @csrf


                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Language') . '*' }}</label>
                                <select name="language_id" class="form-control" id="language-select">
                                    <option selected disabled>{{ __('Select a Language') }}</option>
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                                <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>

                        </div>

                    </div>
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Category') . '*' }}</label>
                                <select name="space_category_id" class="form-control" id="category-select">
                                    <option selected disabled>{{ __('Select a Category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" class="space-category">{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p id="err_space_category_id" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Subcategory Name') . '*' }}</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ __('Enter Subcategory Name') }}" autocomplete="off">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Subcategory Status') . '*' }}</label>
                                <select name="status" class="form-control">
                                    <option selected disabled>{{ __('Select a Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactive') }}</option>
                                </select>
                                <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('Subcategory Serial Number') . '*' }}</label>
                                <input type="number" class="form-control" name="serial_number"
                                    placeholder="{{ __('Enter Subcategory Serial Number') }}" autocomplete="off">
                                <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <p class="text-warning mt-2 mb-0">
                            <small>{{ __('The higher the serial number is, the later the subcategory will be shown') . '.' }}</small>
                        </p>
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
