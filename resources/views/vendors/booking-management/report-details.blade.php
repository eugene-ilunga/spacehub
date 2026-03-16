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
                        ({{ $basic->base_currency_text_position == 'left' ? $basic->base_currency_text . ' ' : ($basic->base_currency_text_position == 'right' ? ' ' . $basic->base_currency_text : '') }})
                      </th>
                      <th scope="col">{{ __('Variants') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($services as $service)


                      <tr>
                        <td>
                          {{ strlen($service->service_title) > 50 ? mb_substr($service->service_title, 0, 50, 'UTF-8') . '...' : $service->service_title }}
                        </td>
                        <td>{{$service->price_type ?? ''}}</td>
                        <td>{{$service->has_sub_services === 0 ? $service->service_price : '--'}}</td>
                        @if($service->has_sub_services === 0)
                          <td> {{'--'}}</td>
                        @else
                          <td>
                            @foreach ($service->subServices as $subservice)
                              <div>
                                <strong>{{__('Title')}} </strong>: <span class="ml-2">{{@$subservice->sub_service_title }}</span>
                                <br>
                                <strong>{{__('Price')}} </strong>: <span class="ml-2"> 
                                  {{ $basic->base_currency_symbol_position
                            == 'left' ?
                            $basic->base_currency_symbol : '' }}{{@$subservice->sub_service_price }} {{
                            $basic->base_currency_symbol_position == 'right' ?
                            $basic->base_currency_symbol : '' }}
                                </span>
                              </div>
                              <br>
                            @endforeach
                          </td>
                        @endif
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


