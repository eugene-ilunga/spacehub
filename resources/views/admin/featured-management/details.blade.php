<div class="modal fade" id="detailsModal-{{ $booking->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLongTitle">{{ __('Booking Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form create" method="post">
                    @csrf
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <h3>{{ __('Vendor Information') }}</h3>
                            <ul>
                                <li>{{ __('Name') . ':' }} <span>{{ @$booking->sellerInfo->username }}</span></li>
                                <li>{{ __('Email') . ':' }} <span>{{ @$booking->sellerInfo->email }}</span></li>
                                <li>{{ __('Phone') . ':' }} <span>{{ @$booking->sellerInfo->phone }}</span></li>
                            </ul>


                        </div>

                        <div class="col-md-12">
                            <h3>{{ __('Payment Information') }}</h3>
                            <ul>
                                <li>{{ __('Payment Status') . ' :' }}
                                    <span>{{ __(ucfirst($booking->payment_status)) }}</span></li>
                                <li>{{ __('Payment Method') . ' :' }} <span>{{ __($booking->payment_method) }}</span>
                                </li>
                                <li>{{ __('Total') . ' :' }} <span>{{ $booking->total ?? 0.0 }}</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <h3>{{ __('Feature Information') }}</h3>
                            <ul>
                                <li>{{ __('Space Title') . ':' }} <span>{{ @$booking->space_title }}</span></li>
                                <li>{{ __('Total Days') . ':' }} <span>{{ @$booking->days }}</span></li>
                                <li>{{ __('Start Date') . ':' }}
                                    <span>{{ @$booking->booking_status == 'approved' ? \Carbon\Carbon::parse($booking->start_date)->format('j F, Y') : __($booking->booking_status) }}</span>
                                </li>
                                <li>{{ __('End Date') . ' :' }}
                                    <span>{{ @$booking->booking_status == 'approved' ? \Carbon\Carbon::parse($booking->end_date)->format('j F, Y') : __($booking->booking_status) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    {{ __('Close') }}
                </button>

            </div>
        </div>
    </div>
