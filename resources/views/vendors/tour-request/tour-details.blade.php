@foreach ($tourRequests as $tourRequest)
    <div class="modal fade" id="tourRequestModal-{{ $tourRequest->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Tour Request Details') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>{{ __('Username') }}</label>
                    <p>{{ !empty($tourRequest->customer_name) ? $tourRequest->customer_name : '-' }}</p>

                    <label>{{ __('Email') }}</label>
                    <p>{{ !empty($tourRequest->customer_email) ? $tourRequest->customer_email : '-' }}</p>
                    @php $informations = json_decode($tourRequest->information); @endphp

                    @if (!is_null($informations))
                        @foreach ($informations as $key => $information)
                            @php
                                $length = count((array) $informations);
                                $str = preg_replace('/_/', ' ', $key);
                                $label = mb_convert_case($str, MB_CASE_TITLE);
                            @endphp

                            @if ($information->type == 8)
                                <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                                    <div class="col-lg-4">
                                        <strong>{{ $label . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-8">
                                        <a href="{{ asset('assets/file/zip-files/' . $information->value) }}"
                                            download="{{ $information->originalName }}" class="btn btn-sm btn-primary">
                                            {{ __('Download') }}
                                        </a>
                                    </div>
                                </div>
                            @elseif ($information->type == 5)
                                <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                                    <div class="col-lg-4">
                                        <strong>{{ $label . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="click-show">
                                            <div class="text show-content">
                                                <p class="mb-1">{{ $information->value }}</p>
                                            </div>
                                            <div class="read-more-btn">Read More</div>
                                        </div>
                                    </div>
                                </div>

                                @include('vendors.tour-request.show-text')
                            @elseif ($information->type == 4)
                                <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                                    <div class="col-lg-4">
                                        <strong>{{ $label . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-8">
                                        @php
                                            $checkboxValues = $information->value;
                                            $allCheckboxOptions = '';
                                            $lastElement = end($checkboxValues);

                                            foreach ($checkboxValues as $value) {
                                                if ($value == $lastElement) {
                                                    $allCheckboxOptions .= $value;
                                                } else {
                                                    $allCheckboxOptions .= $value . ', ';
                                                }
                                            }
                                        @endphp

                                        {{ $allCheckboxOptions }}
                                    </div>
                                </div>
                            @else
                                <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                                    <div class="col-lg-4">
                                        <strong>{{ $label . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-8">{{ $information->value }}</div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
