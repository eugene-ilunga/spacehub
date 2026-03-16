@foreach ($services as $service )
<div class="modal fade" id="optionModal-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Option Information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @php
                $subservices = DB::table('sub_services')

                    ->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
                    ->select('sub_services.image', 'sub_services.price', 'sub_service_contents.title')
                    ->where([
                        ['sub_services.service_id', $service->id],
                        ['sub_service_contents.language_id', $langIdForVariant],
                    ])
                    ->get();
            @endphp
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            @if (empty($subservices))
                                <h3 class="text-center mt-2">{{ __('NO OPTIONS FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Image') }}</th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">
                                                    {{ __('Price') }} ({{ $basic->base_currency_text }})

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subservices as $subservice)
                                                <tr>
                                                    <td>
                                                        @if (isset($subservice->image))
                                                            <img src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                                                                alt="{{ __('subservice image') }}" width="45">
                                                        @else
                                                            <img src="{{ asset('assets/img/noimage.jpg') }}"
                                                                alt="{{ __('subservice image') }}" width="45">
                                                        @endif

                                                    </td>
                                                    <td>
                                                        {{ strlen(@$subservice->title) > 50 ? mb_substr(@$subservice->title, 0, 50, 'UTF-8') . '...' : @$subservice->title }}
                                                    </td>
                                                    <td>{{ @$subservice->price }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
    
@endforeach
