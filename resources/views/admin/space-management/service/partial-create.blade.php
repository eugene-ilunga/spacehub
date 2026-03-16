@extends('admin.layout')

@php
  $seller_id = $sellerId;
  $current_package = null;
  if ($seller_id != 0) {
      $current_package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller_id);
      $language = \App\Models\Language::where('is_default', 1)->select('id', 'code')->first();
      $remainingSpace = \App\Http\Helpers\SellerPermissionHelper::spaceCount($seller_id);
      $remainingAmenities = \App\Http\Helpers\SellerPermissionHelper::amenitiesCount($seller_id);
      $totalSliderImage = \App\Http\Helpers\SellerPermissionHelper::sliderImageCount($seller_id);
      $totalServices = \App\Http\Helpers\SellerPermissionHelper::serviceCount($seller_id);
      $totalOptions = \App\Http\Helpers\SellerPermissionHelper::optionCount($seller_id);
      if ($current_package) {
          $packageFeature = json_decode($current_package->package_feature, true);
      } else {
          $current_package = [];
      }
  }
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Spaces') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Space Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Spaces') }}</a>
    </li>
    <li class="separator">
        <i class="flaticon-right-arrow"></i>
    </li>
      @if (count($spaces) > 0)
        @foreach ($spaces as $space)
          <li class="nav-item">
            <a href="#">{{ $space->title }}</a>
          </li>
        @endforeach
      @endif
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Service') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Service') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.space_management.service.view_services', ['space_id' => request()->id, 'language' => request()->input('language')]) }}">
            <span class="btn-label">
              <i class="fas fa-backward mdb_12"></i>
            </span>
            {{ __('Back') }}
          </a>
          @if ($sellerId != 0)
            @if (!is_null($currentPackage))
              <a href="#" class="btn  btn-secondary mr-2  btn-sm btn-round float-right" data-toggle="modal"
                data-target="#packageLimitModal">
                @if (
                    $remainingSpace == 'downgraded' ||
                        count($remainingAmenities) > 0 ||
                        count($totalSliderImage) > 0 ||
                        count($totalServices) > 0 ||
                        count($totalOptions) > 0)
                  <i class="fas fa-exclamation-triangle text-danger"></i>
                @endif
                {{ __('Limit of') }} {{ @$seller->username }}
              </a>
            @endif
          @endif
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-xl-8">
              <div class="alert alert-danger pb-1 mdb_display_none" id="serviceErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <form id="serviceForm" action="{{ route('admin.space_management.service.store') }}"
                enctype="multipart/form-data" method="POST">
                @csrf

                <div id="slider-image-id"></div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Service Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                      <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Serial Number') . '*' }}</label>
                      <input class="form-control" type="number" name="serial_number"
                        placeholder="{{ __('Enter Serial Number') }}">
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('The higher the serial number is, the later the service will be shown') . '.' }}</small>
                      </p>
                    </div>
                  </div>
                </div>
                @if ($space_type == 3)
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>{{ __('Price will be Multiplied by Selected Days') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_custom_day" value="1" class="selectgroup-input">
                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="is_custom_day" value="0" class="selectgroup-input " checked>
                            <span class="selectgroup-button">{{ __('No') }}</span>
                          </label>
                        </div>
                        <p class="mt-2 mb-0 text-warning">
                          {{ '*' . __('Day value multiplied with selected services or varients price') . '.' }}
                        </p>

                        <p class="mt-1 mb-0 text-danger em" id="err_is_custom_day"></p>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Available Variant') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="has_sub_services" value="1"
                            class="selectgroup-input has_sub_services">
                          <span class="selectgroup-button">{{ __('Yes') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="has_sub_services" value="0"
                            class="selectgroup-input has_sub_services" checked>
                          <span class="selectgroup-button">{{ __('No') }}</span>
                        </label>
                      </div>
                      <p class="mt-2 mb-0 text-warning">
                        {{ '*' . __('The variant creation form appear below when select yes') }}
                      </p>

                      <p class="mt-1 mb-0 text-danger em" id="err_has_sub_service"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div id="priceTypeContainer" class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Price Type') . '*' }}</label>
                      <select name="price_type" class="form-control">
                        <option selected disabled>{{ __('Select Price Type') }}</option>
                        <option value="fixed">{{ __('Fixed') }}</option>
                        <option value="per person">{{ __('Per Person') }}</option>
                      </select>
                      <p id="err_price_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6" id="servicePriceAndType">
                    <div class="form-group">
                      <label for="">{{ __('Price') . ' ' }}({{ __('in') }}
                        {{ $websiteInfo->base_currency_text }})*</label>
                      <input type="number" class="form-control" name="price"
                        placeholder="{{ __('Enter price') }}">
                      <p id="err_price" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <input type="hidden" name="space_id" value="{{ $spaceId }}">
                  <input type="hidden" name="seller_id" value="{{ $sellerId }}">
                </div>
                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version {{ $language->direction == 1 ? 'rtl' : 'ltr' }}">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button"
                            class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : 'ltr' }}"
                            data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . ' ' . __('Language') }}
                            {{ $language->is_default == 1 ? __('(Default)') : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Service Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Service Title') }}">
                              </div>
                              <p id="err_{{ $language->code }}_title" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="col-lg-12 d-none" id="space_sub_service">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Users can select multiple variants') . '*' }}</label>
                      <select name="subservice_selection_type" class="form-control">
                        <option selected disabled>{{ __('Select a option') }}
                        </option>
                        <option value="multiple">{{ __('Yes') }}</option>
                        <option value="single">{{ __('No') }}</option>
                      </select>
                      <p id="err_subservice_selection_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="table-responsive">
                      <table class="table space-sub_service_table table-bordered ">
                        <thead>
                          <tr>
                            <th>{{ __('Variation Name') }}</th>
                            <th>{{ __('image') }}</th>
                            <th>{{ __('Price') . ' ' }}({{ __('in') }}
                              {{ $websiteInfo->base_currency_text }})*</th>
                            <th>{{ __('Status') }}</th>
                            <th><a href="javascript:void(0)" class="btn btn-success btn-sm addRow"><i
                                  class="fas fa-plus-circle"></i></a></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              @foreach ($languages as $language)
                                <div class="form-group">
                                  <label for="">{{ __('Name') . '*' }}
                                    ({{ $language->name }})
                                  </label>
                                  <input type="text" name="{{ $language->code }}_sub_service_name[]"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : 'ltr'  }}" placeholder="{{ __('Enter Name') }}">
                                  <p id="err_{{ $language->code }}_sub_service_name" class="mt-2 mb-0 text-danger em">
                                  </p>
                                </div>
                              @endforeach
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Thumbnail Image') }}</label>
                                <br>
                                <div class="thumb-preview remove">
                                  <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                    class="uploaded-img-1">
                                </div>
                                <div class="mt-3">
                                  <div role="button" class="btn btn-primary btn-sm upload-btn">
                                    {{ __('Choose Image') }}
                                    <input type="file" class="img-input-sub-service" name="sub_service_image[]">
                                  </div>
                                </div>
                                <p class="text-warning">{{ __('Image Size') . ':' }} <span dir="ltr">{{ '750 x 600 px' }}</span></p>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Price') . '*' }} </label>
                                <input type="text" name="sub_service_price[]" class="form-control"
                                  placeholder="{{ __('Enter price') }}">
                                <p id="err_sub_service_price" class="mt-2 mb-0 text-danger em"></p>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Variant Status') . '*' }}</label>
                                <select name="sub_service_status[]" class="form-control">
                                  <option value="" selected disabled>{{ __('Select a Status') }}
                                  </option>
                                  <option value="1">{{ __('Active') }}</option>
                                  <option value="0">{{ __('Deactive') }}</option>
                                </select>
                                <p id="err_sub_service_status" class="mt-2 mb-0 text-danger em"></p>
                              </div>
                            </td>
                            <td>
                              <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                        </tbody>

                      </table>
                    </div>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="serviceForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($sellerId != 0)
    @include('admin.partials.limit-check')
  @endif
@endsection

@section('script')
  <script>
    'use strict'
    var numberOfVariant = "{{ @$numberOfOption }}";
    var maxNumberOfOption = "{{ @$maxNumberOfOption }}";
    var limitFirstMsg = "{{__('You can add up to')}}";
    var limitLastMsg = "{{__('variants per service')}}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
@endsection
