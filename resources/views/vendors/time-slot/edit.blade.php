<div class="modal fade time_slot_modal" id="editModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Time Slot') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                $isShowTimeSlotRent = isset($spaceContent) && $spaceContent->use_slot_rent == 1 ? '1' : '0';
            @endphp

            <div class="modal-body">
                <form autocomplete="off" id="ajaxEditForm" class="modal-form"
                    action="{{ route('vendor.manage_schedule.time_slot.update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    <input type="hidden" name="global_day_id" value="{{ $day_id ?? '' }}">
                    <input type="hidden" name="space_id" value="{{ $space_id ?? '' }} ">
                    <input type="hidden" name="seller_id" value="{{ $seller_id ?? '' }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label for="">{{ __('Start Time') . '*' }}</label>
                                <input type="text" name="start_time" class="form-control time-24slot ltr"
                                    placeholder="{{ __('Choose Start Time') }}" id="in_start_time">
                                <p id="editErr_start_time" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label for="">{{ __('End Time') . '*' }}</label>
                                <input type="text" name="end_time" class="form-control time-24slot ltr"
                                    placeholder="{{ __('Choose End Time') }}" id="in_end_time">
                                <p id="editErr_end_time" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="{{ $isShowTimeSlotRent ? 'col-lg-6' : 'col-lg-12' }}">
                            <div class="form-group">
                                <label for="">{{ __('Number of Booking') }}</label>
                                <input type="number" name="number_of_booking" class="form-control"
                                    placeholder="{{ __('Enter number of booking') }}" id="in_number_of_booking">
                                <p id="editErr_number_of_booking" class="mt-1 mb-0 text-danger em"></p>
                                <p class="text-warning mt-2 mb-0">
                                    <small>{{ __('The number of spaces available for this time slot') . '.' }}</small>
                                </p>
                            </div>
                        </div>

                        @if ($isShowTimeSlotRent)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ __('Rent') }}
                                        ({{ $settings->base_currency_text }})*</label>
                                    <input type="number" name="time_slot_rent" class="form-control"
                                        placeholder="{{ __('Enter rent for this slot') }}" id="in_time_slot_rent">
                                    <p id="editErr_time_slot_rent" class="mt-1 mb-0 text-danger em"></p>
                                    <p class="text-warning mt-2 mb-0">
                                        <small>{{ '*' . __('If the Get Quote option is enabled for the space, the slot rent will be considered negotiable') . '.' }}</small>
                                    </p>
                                </div>

                            </div>
                        @endif

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
