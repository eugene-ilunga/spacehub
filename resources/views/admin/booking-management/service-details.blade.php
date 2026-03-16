<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Service Information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                                                    ({{ $orderInfo->currency_text_position == 'left'
                                                        ? $orderInfo->currency_text . ' '
                                                        : ($orderInfo->currency_text_position == 'right'
                                                            ? ' ' . $orderInfo->currency_text
                                                            : '') }})
                                                </th>
                                                <th scope="col">{{ __('Variants') }}</th>
                                                <th scope="col">{{ __('Number of Guests') }} </th>
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
                                                    <td>{{ __(ucfirst($service['spaceService']->price_type)) }}</td>
                                                    @if (@$service['spaceService']->has_sub_services === 0)
                                                        <td >{{ @$service['spaceService']->price }}</td>
                                                    @else
                                                        <td>{{ '--' }}</td>
                                                    @endif

                                                    @if (@$service['spaceService']->has_sub_services === 0)
                                                        <td> {{ '--' }}</td>
                                                    @else
                                                        <td>
                                                            @foreach ($service['subServices'] as $subservice)
                                                                <div>
                                                                    <strong>{{ __('Title') }} </strong>: <span
                                                                        class="ml-2 ">
                                                                        {{ @$subservice->sub_service_title }}
                                                                    </span>
                                                                    <br>
                                                                    <strong>{{ __('Price') }} </strong>: <span
                                                                        class="ml-2" dir="ltr">
                                                                        {{ $orderInfo->currency_symbol_position == 'left' ? $orderInfo->currency_symbol : '' }}
                                                                        {{ @$subservice->price }}
                                                                        {{ $orderInfo->currency_symbol_position == 'right' ? $orderInfo->currency_symbol : '' }}
                                                                    </span>
                                                                </div>
                                                                <br>
                                                            @endforeach
                                                        </td>
                                                    @endif
                                                    <td>{{ @$service['spaceService']->number_of_guest }}</td>
                                                    @if ($service['spaceService']->space_type == 2)
                                                        <td>{{ @$service['spaceService']->total_hour }}</td>
                                                    @elseif($service['spaceService']->space_type == 3)
                                                        <td>{{ @$subservice->number_of_custom_day }}</td>
                                                    @endif
                                                    <td class="ltr"> {{ $orderInfo->currency_symbol_position == 'left' ? $orderInfo->currency_symbol : '' }}{{ number_format(@$service['global_service_total_price'], 2) }}
                                                        {{ $orderInfo->currency_symbol_position == 'right' ? $orderInfo->currency_symbol : '' }}
                                                    </td>
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
