@extends('admin.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Report') }}</h4>
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
      <a href="#">{{ __('Bookings & Requests') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Report') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Report') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header adad">
        <div class="row">
          <div class="col-lg-10">
            <form action="" method="GET">
              <input type="hidden" name="language" value="{{ $defaultLang->code }}">
              <div class="row no-gutters">
                <div class="col-lg-2">
                  <div class="form-group">
                    <label>{{ __('From') }}</label>
                    <input name="from" type="text" class="form-control checkInDateNotBooking"
                      placeholder="{{ __('Select Start Date') }}"
                      value="{{ !empty(request()->input('from')) ? request()->input('from') : '' }}" readonly
                      autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-2">
                  <div class="form-group">
                    <label>{{ __('To') }}</label>
                    <input name="to" type="text" class="form-control checkInDateNotBooking"
                      placeholder="{{ __('Select To Date') }}"
                      value="{{ !empty(request()->input('to')) ? request()->input('to') : '' }}" readonly
                      autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-2">
                  <div class="form-group">
                    <label>{{ __('Payment Gateways') }}</label>
                    <select class="form-control mdb_343" name="payment_gateway">
                      <option value="" {{ empty(request()->input('payment_gateway')) ? 'selected' : '' }}>
                        {{ __('All') }}
                      </option>

                      @if (count($onlineGateways) > 0)
                      @foreach ($onlineGateways as $onlineGateway)
                      <option value="{{ $onlineGateway->keyword }}" {{ request()->input('payment_gateway') ==
                        $onlineGateway->keyword ? 'selected' : '' }}>
                        {{ $onlineGateway->name }}
                      </option>
                      @endforeach
                      @endif

                      @if (count($offlineGateways) > 0)
                      @foreach ($offlineGateways as $offlineGateway)
                      <option value="{{ $offlineGateway->name }}" {{ request()->input('payment_gateway') ==
                        $offlineGateway->name ? 'selected' : '' }}>
                        {{ $offlineGateway->name }}
                      </option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                </div>

                <div class="col-lg-2">
                  <div class="form-group">
                    <label>{{ __('Payment Status') }}</label>
                    <select class="form-control mdb_343" name="payment_status">
                      <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                        {{ __('All') }}
                      </option>
                      <option value="completed" {{ request()->input('payment_status') == 'completed' ? 'selected' : ''
                        }}>
                        {{ __('Completed') }}
                      </option>
                      <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                        {{ __('Pending') }}
                      </option>
                      <option value="rejected" {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                        {{ __('Rejected') }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="col-lg-2">
                  <div class="form-group">
                    <label>{{ __('Booking Status') }}</label>
                    <select class="form-control mdb_343" name="booking_status">
                      <option value="" {{ empty(request()->input('booking_status')) ? 'selected' : '' }}>
                        {{ __('All') }}
                      </option>
                      <option value="pending" {{ request()->input('booking_status') == 'pending' ? 'selected' : '' }}>
                        {{ __('Pending') }}
                      </option>
                      <option value="approved" {{ request()->input('booking_status') == 'approved' ? 'selected' : '' }}>
                        {{ __('Approved') }}
                      </option>
                      <option value="rejected" {{ request()->input('booking_status') == 'rejected' ? 'selected' : '' }}>
                        {{ __('Rejected') }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-2">
                  <button type="submit" class="btn btn-primary btn-sm ml-lg-3 card-header-button">
                    {{ __('Submit') }}
                  </button>
                </div>
              </div>
            </form>
          </div>

          <div class="col-lg-2">
            <a href="{{ route('admin.booking_record.export_report') }}"
              class="btn btn-success btn-sm float-lg-right card-header-button">
              <i class="fas fa-file-export"></i> {{ __('Export') }}
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="row">
          <!-- Total Bookings Card -->
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card card-stats card-info card-round">
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="icon-big text-center">
                      <i class="fas fa-calendar-check"></i> <!-- Changed icon for bookings -->
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="numbers">
                      <p class="card-category">{{ __('Total Bookings') }}</p>
                      <h4 class="card-title">{{ $totalBookings }}</h4> <!-- Dynamic count -->
                      <p class="mt-2 mb-0 text-sm">
                        <span class="text-nowrap">{{ __('Earnings') }}:
                          {{ displayCurrency($totalEarnings, $settings) }}</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Hourly Spaces Card -->
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card card-stats card-warning card-round">
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="icon-big text-center">
                      <i class="fas fa-clock"></i> <!-- Hourly icon -->
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="numbers">
                      <p class="card-category">{{ __('Hourly Bookings') }}</p>
                      <h4 class="card-title">{{ $hourlyBookings }}</h4>
                      <p class="mt-2 mb-0 text-sm">
                        <span class="text-nowrap">{{ __('Earnings') }}:
                        {{ displayCurrency($hourlyEarnings, $settings) }}</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Timeslot Spaces Card -->
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card card-stats card-success card-round">
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="icon-big text-center">
                      <i class="fas fa-calendar-alt"></i> <!-- Timeslot icon -->
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="numbers">
                      <p class="card-category">{{ __('Timeslot Bookings') }}</p>
                      <h4 class="card-title">{{ $timeslotBookings }}</h4>
                      <p class="mt-2 mb-0 text-sm">
                        <span class="text-nowrap">{{ __('Earnings') }}:
                        {{ displayCurrency($timeslotEarnings, $settings) }}</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Multiday Spaces Card -->
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card card-stats card-orchid card-round">
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="icon-big text-center">
                      <i class="fas fa-calendar-day"></i> <!-- Multiday icon -->
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="numbers">
                      <p class="card-category">{{ __('Multiday Bookings') }}</p>
                      <h4 class="card-title">{{ $multidayBookings }}</h4>
                      <p class="mt-2 mb-0 text-sm text-white">
                        <span class="text-nowrap">{{ __('Earnings') }}:
                        {{ displayCurrency($multidayEarnings, $settings) }}</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            @if (count($orders) == 0)
            <h3 class="text-center mt-3">{{ __('NO BOOKING RECORDS FOUND') . '!' }}</h3>
            @else
            <div class="table-responsive">
              <table class="table table-striped mt-2">
                <thead>
                  <tr>
                    <th scope="col">{{ __('Booking No.') }}</th>
                    <th scope="col">{{ __('Customer Name') }}</th>
                    <th scope="col">{{ __('Customer Email Address') }}</th>
                    <th scope="col">{{ __('Spaces') }}</th>
                    <th scope="col">{{ __('Services') }}</th>
                    <th scope="col">{{ __('Tax') }}</th>
                    <th scope="col">{{ __('Total Price') }}</th>
                    <th scope="col">{{ __('Paid via') }}</th>
                    <th scope="col">{{ __('Payment Status') }}</th>
                    <th scope="col">{{ __('Booking Status') }}</th>
                    <th scope="col">{{ __('Booking Date') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orders as $order)
                  <tr>
                    <td>{{ '#' . $order->booking_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>
                      {{ strlen($order->space_title) > 20 ? mb_substr($order->space_title, 0, 20, 'UTF-8') . '...' :
                      $order->space_title }}
                    </td>
                    @php
                    $stageServices = json_decode($order->service_stage_info, true);
                    $otherServices = json_decode($order->other_service_info, true);

                    $services = [];
                    if (is_array($stageServices)) {
                    $services = array_merge($services, $stageServices);
                    }
                    if (is_array($otherServices)) {
                    $services = array_merge($services, $otherServices);
                    }

                    $servicesBySpaceService = [];

                    foreach ($services as $service) {
                    if (isset($service['spaceServiceId'])) {
                    $spaceServiceId = $service['spaceServiceId'];
                    if (!isset($servicesBySpaceService[$spaceServiceId])) {
                    $servicesBySpaceService[$spaceServiceId] = [
                    'spaceServiceId' => $spaceServiceId,
                    'subServices' => [],
                    ];
                    }
                    if (isset($service['subServiceId'])) {
                    $servicesBySpaceService[$spaceServiceId][
                    'subServices'
                    ][] = $service['subServiceId'];
                    }
                    }
                    }

                    $lang = \App\Models\Language::where('is_default', 1)->first();
                    $services = [];
                    foreach ($servicesBySpaceService as $spaceServiceInfo) {
                    $spaceServiceId = $spaceServiceInfo['spaceServiceId'];
                    $subServiceIds = $spaceServiceInfo['subServices'];

                    $spaceService = \App\Models\SpaceService::query()
                    ->select(
                    'space_services.id as service_id',
                    'space_services.price_type',
                    'space_services.price as service_price',
                    'space_services.has_sub_services',
                    'space_service_contents.title as service_title',
                    )
                    ->join(
                    'space_service_contents',
                    'space_services.id',
                    '=',
                    'space_service_contents.space_service_id',
                    )
                    ->where('space_service_contents.language_id', $lang->id)
                    ->where('space_services.id', $spaceServiceId)
                    ->first();

                    if ($spaceService) {
                    $subServices = \App\Models\SubService::query()
                    ->whereIn('sub_services.id', $subServiceIds)
                    ->join(
                    'sub_service_contents',
                    'sub_services.id',
                    '=',
                    'sub_service_contents.sub_service_id',
                    )
                    ->where(
                    'sub_service_contents.language_id',
                    $lang->id,
                    )
                    ->select(
                    'sub_services.id as sub_service_id',
                    'sub_services.price as sub_service_price',
                    'sub_service_contents.title as sub_service_title',
                    )
                    ->get();
                    $spaceService->subServices = $subServices;
                    $services[] = $spaceService;
                    }
                    }
                    @endphp

                    <td>
                      <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-success btn-sm ">
                        {{ __('view') }}</a>
                    </td>
                    <td>
                      @if (is_null($order->tax))
                      {{ '-' }}
                      @else
                      {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->tax }}{{
                      $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                      @endif
                    </td>
                    <td>
                      @if (is_null($order->grand_total))
                      {{ __('Requested') }}
                      @else
                      {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{
                      $order->grand_total }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol :
                      '' }}
                      @endif
                    </td>

                    <td>
                      {{ is_null($order->payment_method) ? '-' : $order->payment_method }}
                    </td>
                    <td>
                      @if ($order->payment_status == 'completed')
                      <span class="badge badge-success">{{ __('Completed') }}</span>
                      @elseif ($order->payment_status == 'pending')
                      <span class="badge badge-warning">{{ __('Pending') }}</span>
                      @else
                      <span class="badge badge-danger">{{ __('Rejected') }}</span>
                      @endif
                    </td>
                    <td>
                      @if ($order->booking_status == 'pending')
                      <span class="badge badge-warning">{{ __('Pending') }}</span>
                      @elseif ($order->booking_status == 'approved')
                      <span class="badge badge-success">{{ __('Approved') }}</span>
                      @else
                      <span class="badge badge-danger">{{ __('Rejected') }}</span>
                      @endif
                    </td>
                    @php
                    $bookingDate = Carbon\Carbon::parse($order->created_at)->format(
                    'M d, Y',
                    );
                    @endphp
                    <td>{{ $bookingDate }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="mt-3 text-center">
          <div class="d-inline-block mx-auto">
            @if (count($orders) > 0)
            {{ $orders->appends([
            'from' => request()->input('from'),
            'to' => request()->input('to'),
            'payment_gateway' => request()->input('payment_gateway'),
            'payment_status' => request()->input('payment_status'),
            'booking_status' => request()->input('booking_status'),
            ])->links() }}
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('admin.booking-management.report-details')
@endsection
