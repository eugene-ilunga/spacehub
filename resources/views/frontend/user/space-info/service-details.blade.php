<div class="modal serviceShowModal fade" id="serviceShowModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Service Information') }}</h5>
                <button type="button" class="btn_close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            @if (empty($services))
                                <h3 class="text-center mt-2">{{ __('NO SERVICES FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Price Type') }}</th>
                                                <th scope="col">{{ __('Price') }}
                                                    ({{ $basic->base_currency_text_position == 'left'
                                                        ? $basic->base_currency_text . ' '
                                                        : ($basic->base_currency_text_position == 'right'
                                                            ? ' ' . $basic->base_currency_text
                                                            : '') }})
                                                </th>
                                                <th scope="col">{{ __('Variants') }}</th>
                                                <th scope="col">{{ __('Number Of Guests') }} </th>
                                                @if ($space_type == 2 || $space_type == 3)
                                                    <th scope="col">
                                                        {{ $space_type == 2 ? __('Hours') : ($space_type == 3 ? __('Days') : '') }}
                                                    </th>
                                                @endif

                                                <th scope="col">{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($services as $service)
                                                <tr>
                                                    <td>
                                                        {{ strlen(@$service['spaceService']->service_title) > 50
                                                            ? mb_substr(@$service['spaceService']->service_title, 0, 50, 'UTF-8') . '...'
                                                            : @$service['spaceService']->service_title }}
                                                    </td>
                                                    <td>
                                                        {{ __(ucfirst($service['spaceService']->price_type)) }}
                                                    </td>
                                                    @if (@$service['spaceService']->has_sub_services === 0)
                                                        <td dir="ltr">
                                                            {{ @$service['spaceService']->price }}
                                                        </td>
                                                    @else
                                                        <td>{{ '--' }}</td>
                                                    @endif

                                                    @if (@$service['spaceService']->has_sub_services === 0)
                                                        <td> {{ '--' }}</td>
                                                    @else
                                                        <td>
                                                            @foreach ($service['subServices'] as $subservice)
                                                                <div>
                                                                    <span class="fw-medium">{{ __('Title') }}
                                                                    </span>:
                                                                    <span class="ml-2">
                                                                        {{ @$subservice->sub_service_title }}
                                                                    </span>
                                                                    <br>
                                                                    <span class="fw-medium">{{ __('Price') }}
                                                                    </span>:
                                                                    <span dir="ltr" class="ml-2">
                                                                        {{ $position == 'left' ? $symbol : '' }}{{ @$subservice->price }}
                                                                        {{ $position == 'right' ? $symbol : '' }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    @endif
                                                    <td>{{ @$service['spaceService']->number_of_guest }}</td>
                                                    @if ($service['spaceService']->space_type == 2)
                                                        <td>{{ @$service['spaceService']->total_hour }}</td>
                                                    @elseif($service['spaceService']->space_type == 3)
                                                        <td>{{ @$subservice->number_of_custom_day }}</td>
                                                    @endif
                                                    <td dir="ltr"> {{ $position == 'left' ? $symbol : '' }}{{ number_format($service['global_service_total_price'], 2) }}
                                                        {{ $position == 'right' ? $symbol : '' }}</td>
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
