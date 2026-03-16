<div class="modal fade" id="createModal" tabindex="-1" role="dialog" arititletotala-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Package') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                    action="{{ route('admin.package.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">{{ __('Package Title') }}*</label>
                        <input id="title" type="text" class="form-control" name="title"
                            placeholder="{{ __('Enter Package title') }}" value="">
                        <p id="err_title" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="price">{{ __('Price') }} ({{ $settings->base_currency_text }})*</label>
                        <input id="price" type="number" class="form-control" name="price"
                            placeholder="{{ __('Enter Package price') }}" value="">
                        <p class="text-warning">
                            <small>{{ __('If price is 0') . ',' . ' ' . __('than it will appear as free') }}</small>
                        </p>
                        <p id="err_price" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Icon') . '*' }}</label>
                        <div class="btn-group d-block">
                            <button type="button" class="btn btn-primary iconpicker-component">
                                <i class="fas fa-gift"></i>
                            </button>
                            <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                data-selected="fa-car" data-toggle="dropdown"></button>
                            <div class="dropdown-menu"></div>
                        </div>
                        <input type="hidden" id="inputIcon" name="icon">
                        <p id="err_icon" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="term">{{ __('Package Term') }}*</label>
                        <select id="term" name="term" class="form-control" required>
                            <option value="" selected disabled>{{ __('Choose a Package term') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                            <option value="lifetime">{{ __('Lifetime') }}</option>
                        </select>
                        <p id="err_term" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Package Features') }}</label>
                        <div class="selectgroup selectgroup-pills">

                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Support Tickets"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Support Tickets') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Add Booking" class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Manual Booking Entry from Dashboard') }}</span>
                            </label>

                            @if (isset($settings) &&
                            $settings->fixed_time_slot_rental == 1 &&
                            $settings->hourly_rental == 1 &&
                            $settings->multi_day_rental == 1)
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Fixed Timeslot Rental"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Fixed Timeslot Rental') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Hourly Rental"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Hourly Rental') }}</span>

                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Multi Day Rental"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Multi Day Rental') }}</span>

                            </label>
                            @else
                            @if (isset($settings) && $settings->fixed_time_slot_rental == 1)
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Fixed Timeslot Rental"
                                    class="selectgroup-input" checked>
                                <span class="selectgroup-button">{{ __('Fixed Timeslot Rental') }}</span>
                            </label>
                            @endif
                            @if (isset($settings) && $settings->hourly_rental == 1)
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Hourly Rental" class="selectgroup-input"
                                    checked>
                                <span class="selectgroup-button">{{ __('Hourly Rental') }}</span>
                            </label>
                            @endif

                            @if (isset($settings) && $settings->multi_day_rental == 1)
                            <label class="selectgroup-item">
                                <input type="checkbox" name="features[]" value="Multi Day Rental"
                                    class="selectgroup-input" checked>
                                <span class="selectgroup-button">{{ __('Multi Day Rental') }}</span>
                            </label>
                            @endif

                            @endif


                        </div>
                        <p id="err_features" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Number of Spaces') }} *</label>
                        <input type="number" class="form-control" name="number_of_space"
                            placeholder="{{ __('Enter Number of spaces') }}">
                        <p class="text-warning">
                            {{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}</p>
                        <p id="err_number_of_space" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Number of Services per Space') }} *</label>
                        <input type="number" class="form-control" name="number_of_service_per_space"
                            placeholder="{{ __('Enter Number of Services per Space') }}">
                        <p class="text-warning">
                            {{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}</p>
                        <p id="err_number_of_service_per_space" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Number of Variants per Service') }} *</label>
                        <input type="number" class="form-control" name="number_of_option_per_service"
                            placeholder="{{ __('Enter Number of Variants') }}">
                        <p class="text-warning">
                            {{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}</p>
                        <p id="err_number_of_option_per_service" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Number of Slider images per Space') }} *</label>
                        <input type="number" class="form-control" name="number_of_slider_image_per_space"
                            placeholder="{{ __('Enter Number of Slider images per Space') }}">
                        <p class="text-warning">
                            {{ __('Enter 999999') . ',' . ' ' . __('then it will appear as unlimited') }}</p>
                        <p id="err_number_of_slider_image_per_space" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Number of Amenities') }} *</label>
                        <input type="number" class="form-control" name="number_of_amenities_per_space"
                            placeholder="{{ __('Enter Number of Amenities per Space') }}">
                        <p class="text-warning">
                            {{ __('Enter 999999') . ' ' . __(',') . ' ' . __('then it will appear as unlimited') }}</p>
                        <p id="err_number_of_amenities_per_space" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="status">{{ __('Status') }}*</label>
                        <select id="status" class="form-control" name="status">
                            <option value="" selected disabled>{{ __('Select a Status') }}</option>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Deactive') }}</option>
                        </select>
                        <p id="err_status" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Popular') }}</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="recommended" value="1" class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Yes') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="recommended" value="0" class="selectgroup-input" checked>
                                <span class="selectgroup-button">{{ __('No') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Custom Features') }}</label>
                        <textarea class="form-control" name="custom_features" rows="5"
                            placeholder="{{ __('Enter Custom Features') }}"></textarea>
                        <p class="text-warning">
                            <small>{{ __('Enter new line to seperate features') }}</small>
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
