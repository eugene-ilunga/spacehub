<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Coupon') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('admin.space_management.coupons.store') }}"
                    method="post">
                    @csrf
                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Name') . '*' }}</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ __('Enter Coupon Name') }}">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Code') . '*' }}</label>
                                <input type="text" class="form-control" name="code"
                                    placeholder="{{ __('Enter Coupon Code') }}">
                                <p id="err_code" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Coupon Type') . '*' }}</label>
                                <select name="coupon_type" class="form-control">
                                    <option selected disabled>{{ __('Select a Type') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                </select>
                                <p id="err_coupon_type" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Value') . '*' }}</label>
                                <input type="number" step="0.01" class="form-control" name="value"
                                    placeholder="{{ __('Enter Coupon Value') }}">
                                <p id="err_value" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="text" class="form-control checkInDateNotBooking" autocomplete="off"
                                    name="start_date" placeholder="{{ __('Enter Start Date') }}">
                                <p id="err_start_date" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }}</label>
                                <input type="text" class="form-control checkInDateNotBooking" autocomplete="off" name="end_date"
                                    placeholder="{{ __('Enter End Date') }}">
                                <p id="err_end_date" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') . '*' }}</label>
                                <input type="number" class="form-control" name="serial_number"
                                    placeholder="{{ __('Enter Serial Number') }}">
                                <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>
                                <p class="text-warning mt-2 mb-0">
                                    <small>{{ __('Coupons with higher serial numbers will appear later in the list') . '.' }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-6 ">
                            <div class="form-group">
                                <label for="">{{ __('Vendor') }}</label>
                                <select name="seller_id" class="form-control select2 vendorTypeForAddBooking">
                                    <option value="admin" selected>{{ __('Please Select') }}</option>
                                    @foreach ($sellers as $seller)
                                        <option value="{{ $seller->id }}">{{ $seller->username }}</option>
                                    @endforeach
                                </select>
                                <p class="text-warning">
                                    {{ __('if you do not select any vendor') . ', ' . __('then this space will be listed for admin') . '.' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-6" id="featureSpaceTypeContainerForAddBooking">
                            <div class="form-group">
                                <label for="">{{ __('Space Type') . '*' }}</label>
                                <select name="space_type" class="form-control select2"
                                    id="featureSpaceTypeForAddBooking">
                                    <option value="" selected disabled>{{ __('Select a space type') }}
                                    </option>
                                    <option value="fixed_time_slot_rental">{{ __('Fixed Timeslot Rental') }}
                                    </option>
                                    <option value="hourly_rental">{{ __('Hourly Rental') }}</option>
                                    <option value="multi_day_rental">{{ __('Multi-Day Rental') }}</option>
                                </select>
                                <p id="err_space_type" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6" id="spaceContainerForAddBooking">
                            <div class="form-group">
                                <label for="">{{ __('Spaces') . '*' }}</label>
                                <select name="spaces[]" class="form-control select2" multiple="multiple"
                                    id="spaceId">
                                    @foreach ($spaces as $space)
                                        <option value="{{ $space->id }}">
                                            {{ $space->space_title }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-warning mt-2 mb-0">
                                    <small>
                                        {{ __('This coupon can be applied to these spaces') . '.' }}<br>
                                        {{ __('Leave this field empty for all spaces') . '.' }}
                                    </small>
                                </p>
                                <p id="err_spaces" class="mt-2 mb-0 text-danger em"></p>
                            </div>
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
