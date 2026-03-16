<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Charge') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.feature_record.charge.update') }}"
                    method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="form-group">

                        <label for="">{{ __('Day') . '*' }}</label>
                        <input type="number" class="form-control" id="in_number_of_day" name="number_of_day"
                            placeholder="{{ __('Enter number of day') }}">

                        <p id="editErr_number_of_day" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">
                                    @if ($basic->base_currency_text_position == 'left')
                                        ({{ $basic->base_currency_text }}) {{ __('Price') }}
                                    @elseif($basic->base_currency_text_position == 'right')
                                        {{ __('Price') }} ({{ $basic->base_currency_text }})
                                    @else
                                        {{ __('Price') }}
                                    @endif

                                </label>
                                <input type="number" class="form-control" id="in_charge_price" name="charge_price"
                                    placeholder="{{ __('Enter price') }}">
                                <p id="editErr_charge_price" class="mt-2 mb-0 text-danger em"></p>
                            </div>
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
