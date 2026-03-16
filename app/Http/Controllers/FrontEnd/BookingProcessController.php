<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\CouponUsage;
use App\Models\GlobalDay;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceCoupon;
use App\Models\SpaceService;
use App\Models\SubService;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingProcessController extends Controller
{

  public function getBookingData(Request $request)
  {

    // Get space type based on spaceId
    $spaceType = Space::where('id', $request->spaceId)->select('space_type', 'max_guest', 'min_guest')->firstOrFail();
    $request['max_guest'] = $spaceType->max_guest;
    $request['min_guest'] = $spaceType->min_guest;
    // Handle space type 3 (multi-day booking)
    if ($spaceType->space_type == 3) {
      session()->forget('hourlyBookingData');
      session()->forget('timeSlotBookingData');
      return $this->handleMultiDayBooking($request);
    }

    // Handle space type 2 (hourly booking)
    if ($spaceType->space_type == 2) {
      session()->forget('multiDayBookingData');
      session()->forget('timeSlotBookingData');
      return $this->handleHourlyBooking($request);
    }

    // Handle space type 1 (time slot booking)
    if ($spaceType->space_type == 1) {
      session()->forget('multiDayBookingData');
      session()->forget('hourlyBookingData');
      return $this->handleTimeSlotBooking($request);
    }

    return response()->json(['message' => __('Invalid space type') . '.'], 400);
  }

  private function handleMultiDayBooking(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'bookingDate' => 'required',
      'numberOfGuest' => [
        'required',
        'integer',
        'min:1',
        'between:' . $request->min_guest . ',' . $request->max_guest,
      ],
    ], [
      'bookingDate.required' => __('Date is required.'),
      'numberOfGuest.required' => __('Number of guests is required.'),
      'numberOfGuest.integer' => __('The number of guests must be a number') . '.',
      'numberOfGuest.min' => __('The number of guests must be at least 1') . '.',
      'numberOfGuest.between' => __('The number of guests must be between') . ' ' . $request->min_guest . ' ' . __('and') . ' ' . $request->max_guest . '.',
    ]);


    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $value = $request->bookingDate;
    $spaceId = $request->spaceId;
    $dateArray = explode('-', $value);
    $date1 = $dateArray[0];
    $date2 = $dateArray[1];

    // Get all dates between the start & end date
    $allDates = SpaceBooking::getAllDates($date1, $date2, 'Y-m-d');
    $space = Space::select('similar_space_quantity')->findOrFail($spaceId);
    $quantity = $space->similar_space_quantity;

    // Get existing bookings
    $bookings = SpaceBooking::where([
      ['space_id', '=', $spaceId],
      ['booking_type', '=', 3]
    ])->whereIn(
      'booking_status',
      ['pending', 'approved']
    )
      ->select('start_date', 'end_date')
      ->get();

    $bookedDates = [];
    foreach ($allDates as $date) {
      $bookingCount = 0;
      foreach ($bookings as $existBooking) {
        $existStartDate = Carbon::parse($existBooking->start_date);
        $existEndDate = Carbon::parse($existBooking->end_date);
        $individualRequestDate = Carbon::parse($date);

        if ($individualRequestDate->betweenIncluded($existStartDate, $existEndDate)) {
          $bookingCount++;
        }
      }
      if ($bookingCount >= $quantity) {
        $bookedDates[] = $date;
      }
    }

    if (count($bookedDates) > 0) {
      return response()->json([
        'message' => __('Unfortunately') . ', ' . __('the requested date overlaps with an existing booking') . '. ' . __('Please choose a different date that is available') . '.'
      ]);
    }

    $data = $this->prepareBookingData($request);
    $request->session()->put('multiDayBookingData', $data);

    return response()->json([
      'redirectUrl' => route('frontend.booking.checkout.index'),
      'data' => $data
    ]);
  }

  private function handleHourlyBooking(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'bookingDate' => 'required|date',
      'numberOfGuest' => [
        'required',
        'integer',
        'min:1',
        'between:' . $request->min_guest . ',' . $request->max_guest,
      ],
      'startTime' => [
        'required',
        function ($attribute, $value, $fail) {
          if ($value === ':undefined') {
            $fail(__('Start time is required') . '.');
          }
        },
      ],
      'hours' => 'required|integer|min:1',
    ], [
      'bookingDate.required' => __('Date is required.'),
      'bookingDate.date' => __('The booking date must be a valid date') . '.',
      'numberOfGuest.required' => __('Number of guests is required.'),
      'numberOfGuest.integer' => __('The number of guests must be a number') . '.',
      'numberOfGuest.min' => __('The number of guests must be at least 1') . '.',
      'startTime.required' => __('Start time is required') . '.',
      'hours.required' => __('Hours is required.'),
      'hours.integer' => __('The number of hours must be a number') . '.',
      'numberOfGuest.between' => __('The number of guests must be between') . ' ' . $request->min_guest . ' ' . __('and') . ' ' . $request->max_guest . '.',
      'hours.min' => __('The number of hours must be at least 1') . '.',
    ]);


    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $space = Space::where('id', $request->spaceId)->firstOrFail();
    $space['booking_date'] = $request->bookingDate;

    // Apply time/date conversions BEFORE you prepare booking data
    $adminTimezone = now()->timezoneName;
    $startTime = $this->convertTo24hInAdminTimezone($request->startTime, $adminTimezone);
    $endTime = $this->convertTo24hInAdminTimezone($request->endTime, $adminTimezone);
    $endTimeWithoutInterval = $this->convertTo24hInAdminTimezone($request->endTimeWithoutInterval, $adminTimezone);

    $data = $this->prepareBookingData($request);

    $data['start_time'] = $startTime;
    $data['end_time'] = $endTime;
    $data['end_time_without_interval'] = $endTimeWithoutInterval;

    $request->session()->put('hourlyBookingData', $data);

    return response()->json([
      'redirectUrl' => route('frontend.booking.checkout.index'),
      'data' => $data
    ]);
  }

  private function handleTimeSlotBooking(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'bookingDate' => 'required|date',
      'numberOfGuest' => [
        'required',
        'integer',
        'min:1',
        'between:' . $request->min_guest . ',' . $request->max_guest,
      ],
      'timeSlotId' => 'required',
    ], [
      'bookingDate.required' => __('Date is required.'),
      'bookingDate.date' => __('The booking date must be a valid date') . '.',
      'numberOfGuest.required' => __('Number of guests is required.'),
      'numberOfGuest.integer' => __('The number of guests must be a number') . '.',
      'numberOfGuest.min' => __('The number of guests must be at least 1') . '.',
      'numberOfGuest.between' => __('The number of guests must be between') . ' ' . $request->min_guest . ' ' . __('and') . ' ' . $request->max_guest . '.',
      'timeSlotId.required' => __('Time slot is required.'),
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $time_slot_id = $request->timeSlotId;
    $booking_date = Carbon::parse($request->bookingDate)->format('Y-m-d');
    if (isset($time_slot_id) && !empty($time_slot_id)) {
      $timeSlot = TimeSlot::findOrFail($time_slot_id);
    }

    $bookingsCount = SpaceBooking::query()
      ->join('time_slots', 'time_slots.id', '=', 'space_bookings.time_slot_id')
      ->where([
        ['space_bookings.booking_date', $booking_date],
        ['space_bookings.time_slot_id', $time_slot_id],
      ])
      ->whereIn('booking_status', ['pending', 'approved'])
      ->count();

    if ($bookingsCount >= $timeSlot->number_of_booking) {
      return response()->json([
        'message' => __('Unfortunately') . ', ' . __('the requested time slot is no longer available') . '. ' . __('We recommend selecting another time') . '.'
      ]);
    }
    $timeSlotData = TimeSlot::select('start_time', 'end_time')->findOrFail($time_slot_id);
    $startTime = $timeSlotData->start_time;
    $endTime = $timeSlotData->end_time;

    $request['startTime'] = $startTime ?? null;
    $request['endTime'] = $endTime ?? null;


    $data = $this->prepareBookingData($request);
    $request->session()->put('timeSlotBookingData', $data);

    return response()->json([
      'redirectUrl' => route('frontend.booking.checkout.index'),
      'data' => $data
    ]);
  }

  private function prepareBookingData(Request $request)
  {

    $adminTimezone = now()->timezoneName;
    $bookingDateDb = $this->parseBookingDate($request->bookingDate, $adminTimezone);
    return [
      'space_id' => $request->spaceId,
      'seller_id' => $request->sellerId,
      'total_price' => $request->totalPrice,
      'booking_date' => $request->bookingDate,
      'booking_date' => $bookingDateDb,
      'number_of_guest' => $request->numberOfGuest,
      'start_date' => $request->startDate ?? null,
      'end_date' => $request->endDate ?? null,
      'number_of_day' => $request->numberOfDay ?? null,
      'start_time' => $request->startTime ?? null,
      'end_time' => $request->endTime ?? null,
      'end_time_without_interval' => $request->endTimeWithoutInterval ?? null,
      'total_hour' => $request->totalHour ?? null,
      'custom_hour' => $request->hours ?? null,
      'time_slot_id' => $request->timeSlotId ?? null,
      'space_services_with_subservice' => $request->spaceServicesWithSubservice,
      'space_services_without_subservice' => $request->spaceServicesWithoutSubservice,
      'sub_service_ids' => $request->subserviceIds,
      'service_total' => $request->serviceTotal,
    ];
  }

  public function index(Request $request)
  {
    $isGuestCheckout = $request->input('guest') == 1;
    if (!Auth::guard('web')->check() && !$isGuestCheckout) {
      return redirect()->route('user.login', ['redirectPath' => 'checkout']);
    }

    $bookingData = null;
    // Check all possible booking session types
    $sessionTypes = ['hourlyBookingData', 'multiDayBookingData', 'timeSlotBookingData'];
    foreach ($sessionTypes as $type) {
      if ($request->session()->has($type)) {
        $bookingData = $request->session()->get($type);
        break;
      }
    }

    if (!isset($bookingData)) {
      return redirect()->route('index');
    }

    // Validate space exists
    $space = Space::where([
      ['id', $bookingData['space_id']],
      ['seller_id', $bookingData['seller_id']]
    ])->first();

    if (!$space) {
      $request->session()->forget($sessionTypes);
      Session::flash('error', __('The space you booked is no longer available'));
      return redirect()->route('space.index');
    }

    $spaceId = $bookingData['space_id'];
    $sellerId = $bookingData['seller_id'];
    $serviceTotal = $bookingData['service_total'];
    $numberOfGuest = $bookingData['number_of_guest'];
    $bookingDate = $bookingData['booking_date'];
    $timeSlotId = $bookingData['time_slot_id'];
    $startTime = isset($bookingData['start_time']) ? $bookingData['start_time'] : null;
    $endTime = isset($bookingData['end_time']) ? $bookingData['end_time'] : null;
    $endTimeWithoutInterval = isset($bookingData['end_time_without_interval']) ? $bookingData['end_time_without_interval'] : null;

    $startDate = isset($bookingData['start_date']) ? $bookingData['start_date'] : null;
    $endDate = isset($bookingData['end_date']) ? $bookingData['end_date'] : null;
    $numberOfDay = isset($bookingData['number_of_day']) ? $bookingData['number_of_day'] : null;

    $misc = new MiscellaneousController();
    $data['currencyInfo'] = $this->getCurrencyInfo();
    $data['breadcrumb'] = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $data['pageHeading'] = $misc->getPageHeading($language) ?? __('Checkout');
  

    $taxPercentage = Basic::query()->select('basic_settings.tax')->first();
    $spaceServicesWithSubserviceData = $bookingData['space_services_with_subservice'];
    $spaceServicesWithoutSubserviceData = $bookingData['space_services_without_subservice'];
    $spaceServicesWithSubservice = [];
    $spaceServicesWithoutSubservice = [];
    $subtotal = 0;
    $spaceServiceMap = [];

    $space = Space::query()->select('spaces.id', 'spaces.space_rent', 'spaces.rent_per_hour', 'spaces.price_per_day', 'spaces.space_type', 'use_slot_rent')
      ->where([
        ['spaces.id', $spaceId],
        ['spaces.seller_id', $sellerId],
      ])->firstOrFail();

    $timeSlotInfo = TimeSlot::query()->select('time_slot_rent')->where('id', '=', $timeSlotId)->first();

    if ($space->space_type == 1 && !$timeSlotInfo) {
      Session::flash('warning', __('Time slot booking is disabled for this space'));
      return redirect()->route('space.index');
    }

    if (isset($spaceServicesWithSubserviceData) && is_array($spaceServicesWithSubserviceData) && count($spaceServicesWithSubserviceData) > 0) {

      foreach ($spaceServicesWithSubserviceData as $item) {
        $spaceServiceId = $item['spaceServiceId'];

        if (!isset($spaceServiceMap[$spaceServiceId])) {
          $spaceService = SpaceService::with([
            'serviceContents' => function ($query) use ($language) {
              $query->where('language_id', $language->id)
                ->select('space_service_id', 'title', 'slug', 'language_id');
            }
          ])->find($item['spaceServiceId']);

          // Add the service_title property to the SpaceService model
          $spaceService->service_title = $spaceService->serviceContents->first()->title;
          $spaceService->space_type = $space->space_type;

          $spaceServiceMap[$spaceServiceId] = [
            'spaceService' => $spaceService,
            'subServices' => [],
          ];
        }

        $subService = SubService::with([
          'subServiceContents' => function ($query) use ($language) {
            $query->where('language_id', $language->id)
              ->select('sub_service_id', 'title', 'slug', 'language_id');
          }
        ])->find($item['subServiceId']);

        $subService->sub_service_title = $subService->subServiceContents->first()->title;
        $subService->space_type = $space->space_type;

        if ($space->space_type == 3) {
          $subService->number_of_day = $item['numberOfCustomDay'];
        }

        if ($spaceService->price_type === 'per person') {
          if ($space->space_type == 3) {
            $subtotal += $subService->price * $numberOfGuest * $item['numberOfCustomDay'];
          } else {
            $subtotal += $subService->price * $numberOfGuest;
          }
        } else {
          if ($space->space_type == 3) {
            $day = $item['numberOfCustomDay'];
            $subtotal += $subService->price  * $day;
          } else {
            $subtotal += $subService->price;
          }
        }
        $spaceServiceMap[$spaceServiceId]['subServices'][] = $subService;
      }
    }

    $spaceServicesWithSubservice = array_values($spaceServiceMap);

    if (isset($spaceServicesWithoutSubserviceData) && is_array($spaceServicesWithoutSubserviceData) && count($spaceServicesWithoutSubserviceData) > 0) {

      $spaceServicesWithoutSubservice = SpaceService::with([
        'serviceContents' => function ($query) use ($language) {
          $query->where('language_id', $language->id)
            ->select('space_service_id', 'title', 'slug', 'language_id');
        }
      ])
        ->whereIn('id', array_column($spaceServicesWithoutSubserviceData, 'spaceServiceId'))
        ->get();

      $spaceServicesWithoutSubservice->each(function ($spaceService) use ($spaceServicesWithoutSubserviceData) {

        $matchingData = collect($spaceServicesWithoutSubserviceData)->firstWhere('spaceServiceId', $spaceService->id);

        // Add numberOfCustomDay as an attribute to the SpaceService model
        if ($matchingData && isset($matchingData['numberOfCustomDay'])) {
          $spaceService->numberOfCustomDay = $matchingData['numberOfCustomDay'];
        } else {
          $spaceService->numberOfCustomDay = 1;
        }
      });

      // Add the service_title property to the SpaceService model
      foreach ($spaceServicesWithoutSubservice as $spaceService) {
        $spaceService->service_title = $spaceService->serviceContents->first()->title;
        $spaceService->space_type = $space->space_type;
        if ($spaceService->price_type === 'per person') {
          if ($space->space_type == 3) {
            $subtotal += $spaceService->price * $numberOfGuest * $spaceService['numberOfCustomDay'];
          } else {
            $subtotal += $spaceService->price * $numberOfGuest;
          }
        } else {
          if ($space->space_type == 3) {
            $day = $spaceService['numberOfCustomDay'];
            $subtotal += $spaceService->price * $day;
          } else {
            $subtotal += $spaceService->price;
          }
        }
      }
    }

    if (isset($space)) {
      if (!empty($space->rent_per_hour) && ($space->space_type == 2)) {
        $subtotal = ($space->rent_per_hour +  $subtotal) * $bookingData['custom_hour'];
      }
      if (!empty($space->price_per_day) && ($space->space_type == 3)) {

        $subtotal += $space->price_per_day * $bookingData['number_of_day'];
      }
      if (($space->space_type == 1)) {
        if ($space->use_slot_rent == 1) {
          $slotRent = $timeSlotInfo->time_slot_rent ?? 0;
          $subtotal += $slotRent;
        } else {
          $subtotal += $space->space_rent;
        }
      }
    }

    $discount_amount = 0.00;
    // Check if a coupon code exists in the session
    if (session()->has('couponCode')) {
      $coupon_code = session('couponCode');

      // Retrieve the coupon using the coupon code
      $coupon = SpaceCoupon::where('code', $coupon_code)->first();
      

      if ($coupon) {
        // Retrieve the coupon usage record
        $couponUsage = CouponUsage::where('coupon_id', $coupon->id)
          ->where([
            ['coupon_code', $coupon->code],
            ['user_id', Auth::guard('web')->user()->id],
          ])
          ->first();
        // Set the discount amount if the coupon usage record exists
        if ($couponUsage) {
          $discount_amount = $couponUsage->discount_amount;
        }
      }
    }

    // Calculate  Grand total and tax amount
    $taxAmount = 0.00;
    $grandTotal = 0.00;
    $subtotal = $subtotal - $discount_amount;
    
    if (!empty($taxPercentage)) {
      
      $taxAmount = $subtotal * ($taxPercentage->tax) / 100;

    }

    $grandTotal = $subtotal + $taxAmount;

    // Fetch time slot and service content
    $timeSlot = isset($time_slot_id) ? TimeSlot::query()->select('time_slots.*')
      ->where([
        ['time_slots.id', $time_slot_id],
        ['time_slots.space_id', $spaceId],
      ])->first() : null;

    $serviceContent = isset($spaceId) ? Space::query()
      ->select('space_contents.slug as slug', 'space_contents.space_id', 'spaces.space_type')
      ->join('space_contents', 'space_contents.space_id', '=', 'spaces.id')
      ->where('space_contents.language_id', $language->id)
      ->where('spaces.id', $spaceId)->first() : null;

    // Prepare data for view
    $data['space'] = $space;
    $data['serviceStages'] = $spaceServicesWithSubservice;
    $data['otherServices'] = $spaceServicesWithoutSubservice;
    $data['numberOfGuest'] = $numberOfGuest;
    $data['spaceContent'] = $serviceContent;
    $data['taxPercentage'] = $taxPercentage->tax;
    $data['taxAmount'] = $taxAmount;
    $data['serviceTotal'] = $serviceTotal;

    $timeZone = now()->timezoneName;
    $timeFormat = Basic::query()->select('time_format')->first()->time_format;

    //this function check the availability of the hourly rental space for the given date and time
    if (!empty($space) && $space->space_type == 2) {

      // already startTime, endTime and bookingDate converted to admin timezone and 24h format in the handleHourlyBooking method
      $data['startTime'] = $startTime;
      $data['endTime'] = $endTime;
      $data['hour'] = $bookingData['custom_hour'] ?? 1;
      $data['endTimeWithoutInterval'] = $endTimeWithoutInterval;

      // Checks the availability of a space for hourly rental bookings
      $availability = $this->checkSpaceAvailabilityForHourlyRental($spaceId, $bookingDate, $startTime, $endTime, $timeFormat);

      // If space not available, flash error and redirect back
      if (!$availability['available']) {
        Session::flash('error', $availability['message']);
        return redirect()->back();
      }
    }

    //this function check the availability of the multi-day rental space for the given start and end date 
    if (!empty($space) && $space->space_type == 3) {

      $startDateCarbon = Carbon::parse($startDate)->setTimezone($timeZone)->format('Y-m-d');
      $endDateCarbon = Carbon::parse($endDate)->setTimezone($timeZone)->format('Y-m-d');
      $todayDate = now()->setTimezone($timeZone)->format('Y-m-d');

      $data['startDate'] = $startDateCarbon;
      $data['endDate'] = $endDateCarbon;
      $data['numberOfDay'] = $numberOfDay;

      // Checks the availability of a space for multi-day rental bookings
      $availability = $this->checkSpaceAvailabilityForMultiday($spaceId, $startDateCarbon, $endDateCarbon, $todayDate);

      // If space not available, flash error and redirect back
      if (!$availability['available']) {
        Session::flash('error', $availability['message']);
        return redirect()->back();
      }
    }


    //for fixed time slot
    if (!empty($space) && $space->space_type == 1) {
      $data['timeSlotId'] = isset($timeSlotId) ? $timeSlotId : null;
      $data['startTime'] = $startTime ? Carbon::createFromFormat('H:i:s', $startTime, 'UTC')
        ->setTimezone($timeZone)
        ->format($timeFormat === '12h' ? 'h:i A' : 'H:i') : null;
      $data['endTime'] = $endTime;
      $data['endTime'] = $endTime ? Carbon::createFromFormat('H:i:s', $endTime, 'UTC')
        ->setTimezone($timeZone)
        ->format($timeFormat === '12h' ? 'h:i A' : 'H:i') : null;
      $data['numberOfBooking'] = isset($timeSlot) ? optional($timeSlot)->number_of_booking : null;
    }

    if ($space->space_type == 3) {
      $data['rent'] = $space->price_per_day;
      $data['type'] = __('Day');
    } else if ($space->space_type == 2) {
      $data['rent'] = $space->rent_per_hour;
      $data['type'] = __('Hour');
    } else {
      if($space->use_slot_rent == 1){
        $data['rent'] = $timeSlotInfo->time_slot_rent ?? 0.00;
        $data['type'] = null;
      }
      else{
        $data['rent'] = $space->space_rent;
        $data['type'] = null;
      }

    }

    // update the session data with grandtotal , tax
    $formattedGrandTotal = number_format($grandTotal, 2, '.', '');
    $formattedSubtotal = number_format($subtotal, 2, '.', '');
    $formattedServicetotal = number_format($serviceTotal, 2, '.', '');

    if (session()->has('hourlyBookingData')) {
      $bookingData['grand_total'] = isset($grandTotal) ? $formattedGrandTotal : 0.00;
      $bookingData['tax_percentage'] = isset($taxPercentage) ? $taxPercentage->tax : 0;
      $bookingData['tax'] = isset($taxAmount) ? $taxAmount : 0.00;
      $bookingData['service_total'] = isset($serviceTotal) ? $formattedServicetotal : 0.00;
      $bookingData['subtotal'] = isset($subtotal) ? $formattedSubtotal : 0.00;
      $bookingData['rent'] = isset($data['rent']) ? $data['rent'] : 0.00;
      $request->session()->put('hourlyBookingData', $bookingData);
    }

    if (session()->has('multiDayBookingData')) {
      $bookingData['grand_total'] = isset($grandTotal) ? $formattedGrandTotal : 0.00;
      $bookingData['tax_percentage'] = isset($taxPercentage) ? $taxPercentage->tax : 0;
      $bookingData['tax'] = isset($taxAmount) ? $taxAmount : 0.00;
      $bookingData['service_total'] = isset($serviceTotal) ? $formattedServicetotal : 0.00;
      $bookingData['subtotal'] = isset($subtotal) ? $formattedSubtotal : 0.00;
      $bookingData['rent'] = isset($data['rent']) ? $data['rent'] : 0.00;
      $request->session()->put('multiDayBookingData', $bookingData);
    }

    if (session()->has('timeSlotBookingData')) {
      $bookingData['grand_total'] = isset($grandTotal) ? $formattedGrandTotal : 0.00;
      $bookingData['tax_percentage'] = isset($taxPercentage) ? $taxPercentage->tax : 0;
      $bookingData['tax'] = isset($taxAmount) ? $taxAmount : null;
      $bookingData['service_total'] = isset($serviceTotal) ? $formattedServicetotal : 0.00;
      $bookingData['subtotal'] = isset($subtotal) ? $formattedSubtotal : 0.00;
      $bookingData['rent'] = isset($data['rent']) ? $data['rent'] : 0.00;
      $request->session()->put('timeSlotBookingData', $bookingData);
    }

    $data['subtotal'] = $subtotal;
    $data['grandTotal'] = $grandTotal;
    $data['discount'] = $discount_amount;
    $data['bookingDate'] = $bookingDate;
    $data['sellerId'] = $sellerId;
    $data['spaceId'] = $spaceId;
    $data['spaceType'] = $space->space_type ?? null;

    $data['authUser'] = Auth::guard('web')->user();
    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_space_booking', 'meta_description_space_booking')->first();
    $data['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();

    $data['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();
    $stripe = OnlineGateway::query()->whereKeyword('stripe')->first();
    $stripeInformation = json_decode($stripe->information, true);
    $data['stripeKey'] = $stripeInformation['key'];

    $authorizenet = OnlineGateway::query()->whereKeyword('authorize.net')->first();
    $anetInfo = json_decode($authorizenet->information);

    if ($anetInfo->sandbox_status == 1) {
      $data['anetSource'] = 'https://jstest.authorize.net/v1/Accept.js';
    } else {
      $data['anetSource'] = 'https://js.authorize.net/v1/Accept.js';
    }
    $data['anetClientKey'] = $anetInfo->public_client_key;
    $data['anetLoginId']   = $anetInfo->api_login_id;

    // Define error messages and add them to the data array
    $data = array_merge($data, [
      'stripeError' => __('Your card number is incomplete'),
      'anetCardError' => __('Please provide valid credit card number'),
      'anetYearError' => __('Please provide valid expiration year'),
      'anetMonthError' => __('Please provide valid expiration month'),
      'anetExpirationDateError' => __('Expiration date must be in the future'),
      'anetCvvInvalidError' => __('Please provide valid CVV'),
      'paymentGatewayError' => __('Payment gateway is required'),
      'firstNameError' => __('First name is required'),
      'phoneNumberError' => __('Phone number is required'),
      'emailAddressError' => __('Email address is required'),
    ]);
    

    return view('frontend.booking.checkout', $data);
  }

  //get available time slots by date
  public function getTimeSlotsByDate(Request $request)
  {
    $bookingDate = $request->selectedDate;
    $space_id = $request->spaceId;
    $seller_id = $request->sellerId;

    // Get Admin Timezone & Time Format
    $timezone = now()->timezoneName ?? config('app.timezone');
    $settings = Basic::select('time_format')->first();
    $timeFormat = $settings && $settings->time_format === '12h' ? '12' : '24';

    // Parse Booking Date with Admin Timezone
    $selectedDate = Carbon::parse($bookingDate, $timezone);
    $parsedBookingDate = $selectedDate->format('Y-m-d');
    $dayOfWeek = $selectedDate->dayOfWeek;

    // Find the Matching Day (GlobalDay)
    $day = GlobalDay::select('id', 'name', 'start_of_week')
      ->where('start_of_week', $dayOfWeek)
      ->where('space_id', $space_id)
      ->first();

    if (!$day) {
      return response()->json(['error' => 'No global day found'], 404);
    }

    // Get Already Booked Time Slots
    $bookedTimeSlots = SpaceBooking::query()
      ->select('time_slot_id', DB::raw('COUNT(*) as booking_count'))
      ->where('space_id', $space_id)
      ->where('booking_date', $parsedBookingDate)
      ->where('booking_status', '!=', 'rejected')
      ->groupBy('time_slot_id')
      ->get();

    //  Get All Time Slots
    $allTimeSlots = TimeSlot::query()
      ->select('id as time_slot_id', 'start_time', 'end_time', 'number_of_booking', 'time_slot_rent', 'global_day_id')
      ->where('space_id', $space_id)
      ->where('global_day_id', $day->id)
      ->get();

    // Exclude Fully Booked Slots
    $arrayBookedId = [];
    foreach ($bookedTimeSlots as $bookedSlot) {
      $slot = $allTimeSlots->firstWhere('time_slot_id', $bookedSlot->time_slot_id);
      if ($slot && $bookedSlot->booking_count >= $slot->number_of_booking) {
        $arrayBookedId[] = $bookedSlot->time_slot_id;
      }
    }

    $now = now($timezone);
    $isToday = $parsedBookingDate === $now->format('Y-m-d');


    // Filter Available Slots (Exclude booked & past slots if today)
    $availableTimeSlots = $allTimeSlots->filter(function ($slot) use ($arrayBookedId, $isToday, $timezone, $now) {
      if (in_array($slot->time_slot_id, $arrayBookedId)) {
        return false;
      }

      // If booking date is today, exclude past time slots
      if ($isToday) {
        try {
          $slotStartTime = Carbon::createFromFormat('H:i:s', $slot->start_time, 'UTC')
            ->setTimezone($timezone);
          // Compare only time (ignore date part)
          if ($slotStartTime->lessThanOrEqualTo($now)) {
            return false;
          }
        } catch (\Exception $e) {
          return false;
        }
      }

      return true;
    })->values();

    // Format Start/End Time as per Admin Timezone & Admin Format
    $formattedSlots = $availableTimeSlots->map(function ($slot) use ($timezone, $timeFormat) {
      try {
        $start = Carbon::createFromFormat('H:i:s', $slot->start_time, 'UTC')->setTimezone($timezone);
        $end = Carbon::createFromFormat('H:i:s', $slot->end_time, 'UTC')->setTimezone($timezone);

        $slot->start_time = $start->format($timeFormat === '12' ? 'h:i A' : 'H:i');
        $slot->end_time = $end->format($timeFormat === '12' ? 'h:i A' : 'H:i');
      } catch (\Exception $e) {
        // Handle format error if any
        $slot->start_time = '--';
        $slot->end_time = '--';
      }

      return $slot;
    });

    return response()->json($formattedSlots);
  }

  public function applyCoupon(Request $request)
  {
    $taxAmount = 0.00;
    try {

      if ($request->coupon == null) {
        return response()->json(['error' => __('Please enter your coupon code') . '!']);
      }

      $coupon = SpaceCoupon::where('code', $request->coupon)->firstOrFail();
      
      // no need to convert timezone for start_date and end_date as these are stored in admin timezone
      $startDate = Carbon::parse($coupon->start_date);
      $endDate = Carbon::parse($coupon->end_date);

      $adminTimezone = now()->timezoneName;
      $todayDate = Carbon::now($adminTimezone);

      $userId = Auth::guard('web')->check() ? Auth::id() : null;
      $hostIp = $request->input('hostIp');

      // check coupon is valid or not
      if ($todayDate->between($startDate, $endDate) == false) {
        return response()->json(['error' => __('Sorry') . ', ' . __('coupon has been expired') . '!']);
      }

      // check coupon is valid or not for this room
      $spaceId = $request->spaceId;
      $spaceIds = empty($coupon->spaces) ? '' : json_decode($coupon->spaces);

      if (!empty($spaceIds) && !in_array($spaceId, $spaceIds)) {
        return response()->json(['error' => __('You can not apply this coupon for this space') . '!']);
      }

      $initTotalRent = str_replace(',', '', $request->initTotal);
      $subtotalValue =  floatval($request->subtotal);


      if ($initTotalRent == '0.00') {
        return response()->json(['error' => __('First') . ', ' . __('fillup the booking dates') . '.']);
      }

      $initTotalRent = floatval($initTotalRent);
      $couponValue = floatval($coupon->value);

      if ($coupon->coupon_type == 'fixed') {
        $discount = $couponValue ?? 0.00;
        // $total = $initTotalRent - $discount;
        $subtotal = $subtotalValue - $discount;
      } else {
        $discount = $initTotalRent * ($couponValue / 100);
        // $total = $initTotalRent - $discount;
        $subtotal = $subtotalValue - $discount;
      }
      
      $basic = Basic::select('tax')->first();
      $tax = floatval($basic->tax) ?? 0.00;
      $taxAmount = $subtotal * ($tax/100);

      $total = $subtotal + $taxAmount;

      // Check if the coupon has already been used by this IP address or user
      $couponUsage = CouponUsage::where([
        ['coupon_code', $coupon->code],
        ['host_ip', $hostIp],
        ['coupon_id', $coupon->id],
      ])
        ->where(function ($query) use ($hostIp, $userId) {
          $query->where('host_ip', $hostIp);
          if ($userId) {
            $query->orWhere('user_id', $userId);
          }
        })
        ->first();

      // Ensure grand total is not negative
      if ($total < 0) {
        $total = 0.00;
      }
      if ($subtotal < 0) {
        $subtotal = 0.00;
      }

      if ($couponUsage) {
        return response()->json([
          'error' => __('You have already used this coupon') . '.',
          'discount' => $discount,
          'grandTotal' => $total,
          'subtotal' => $subtotal,
          'taxAmount' => $taxAmount,
        ]);
      }

      session()->put('couponCode', $coupon->code);

      // Record the coupon usage
      CouponUsage::create([
        'coupon_id' => $coupon->id,
        'coupon_code' => $coupon->code,
        'host_ip' => $hostIp,
        'user_id' => $userId,
        'discount_amount' => $discount,
      ]);

      return response()->json([
        'success' => __('Coupon applied successfully') . '.',
        'discount' => $discount,
        'grandTotal' => $total,
        'subtotal' => $subtotal,
        'taxAmount' => $taxAmount,
      ]);
    } catch (ModelNotFoundException $e) {
      $basic = Basic::select('tax')->first();
      $tax = floatval($basic->tax) ?? 0.00;
      $taxAmount = floatval($request->subtotal) * ($tax / 100);

      return response()->json([
        'error' => __('Coupon is not valid') . '!',
        'discount' => '0.00',
        'grandTotal' => $request->initTotal,
        'subtotal' => $request->subtotal,
        'taxAmount' => $taxAmount,
      ]);
    }
  }

  private function convertTo24hInAdminTimezone($time, $adminTimezone)
  {
    if (empty($time)) return null;

    $timeLower = strtolower($time);
    $is12h = Str::contains($timeLower, ['am', 'pm']);

    try {
      if ($is12h) {
        // 12h format: parse with admin tz, output 24h
        return Carbon::createFromFormat('h:i A', $time, $adminTimezone)
          ->format('H:i');
      } else {
        // Already 24h: return as-is (no parse needed)
        return $time;
      }
    } catch (\Exception $e) {
      Log::error("Invalid time format: {$time}");
      return $time;
    }
  }
  private function parseBookingDate($dateStr, $adminTimezone)
  {
    if (empty($dateStr)) return null;

    try {
      $date = Carbon::createFromFormat('m/d/Y', $dateStr)
        ->startOfDay()
        ->setTimezone($adminTimezone);

      return $date->format('Y-m-d');
    } catch (\Exception $e) {
      return null;
    }
  }

  // Checks the availability of a space for hourly rental bookings
  private function checkSpaceAvailabilityForHourlyRental($spaceId, $bookingDate, $startTime, $endTime, $time_format)
  {

    $timeFormat = $time_format && $time_format == '12h' ? 'h:i A' : 'H:i';
    $adminTimezone = now()->timezoneName;

    $bookingStartDateTime = Carbon::parse($bookingDate . ' ' . $startTime, $adminTimezone);

    // Get current datetime in admin timezone
    $now = Carbon::now($adminTimezone);

    // Check if the booking start datetime is in the past
    if ($bookingStartDateTime->lt($now)) {
      return [
        'available' => false,
        'message' => __('Selected booking start time has already passed. Please choose a valid time') . '.'
      ];
    }
    // Get count of existing bookings for the space at the given time
    $bookedSpaces = SpaceBooking::query()
      ->select('space_id', DB::raw('COUNT(*) as booking_count'))
      ->where('space_id', $spaceId)
      ->where('booking_date', $bookingDate)
      ->where('booking_status', '!=', 'rejected')
      ->where(function ($query) use ($startTime, $endTime) {
        $query->where('start_time', '<', $endTime)
          ->where('end_time', '>', $startTime);
      })
      ->groupBy('space_id')
      ->first();

    // Get the space's quantity
    $space = Space::where('id', $spaceId)
      ->where('space_type', 2)
      ->select('similar_space_quantity')
      ->first();

    $bookingCount = $bookedSpaces ? $bookedSpaces->booking_count : 0;
    $similarSpaceQuantity = $space ? $space->similar_space_quantity : 0;

    if ($similarSpaceQuantity <= $bookingCount) {
      $formatedStartTime = Carbon::parse($startTime)->format($timeFormat);
      $formatedEndTime = Carbon::parse($endTime)->format($timeFormat);
      return [
        'available' => false,
        'message' => __("Space not available. Booked slots count") . __(': ') . $bookingCount . '. ' .
          __("Requested slot") . __(': ') . $formatedStartTime . ' ' . __('to') . ' ' . $formatedEndTime
      ];
    }

    return ['available' => true];
  }

  // Checks the availability of a space for multi-day rental bookings
  private function checkSpaceAvailabilityForMultiday($spaceId, $startDate, $endDate, $todayDate)
  {

    // Then format if needed
    $start = $startDate;
    $end = $endDate;

    // Count overlapping bookings for the same space
    $count = SpaceBooking::where([
      ['space_id', $spaceId],
      ['booking_status', '!=', 'rejected'],
      ['end_date', '>=', $todayDate]
    ])
      ->where(function ($query) use ($start, $end) {
        $query->whereBetween('start_date', [$start, $end])
          ->orWhereBetween('end_date', [$start, $end])
          ->orWhere(function ($q) use ($start, $end) {
            $q->where('start_date', '<=', $start)
              ->where('end_date', '>=', $end);
          });
      })
      ->count();

    // Get the space's quantity
    $space = Space::where('id', $spaceId)
      ->where('space_type', 3)
      ->select('similar_space_quantity')
      ->first();

    $bookingCount = $count ? $count : 0;
    $similarSpaceQuantity = $space ? $space->similar_space_quantity : 0;

    if ($similarSpaceQuantity <= $bookingCount) {
      return [
        'available' => false,
        'message' => __('Unfortunately, the dates') . ' ' . $start . ' ' . __('to') . $end . ' ' . __('overlap with an existing booking') . '.'
      ];
    }

    return ['available' => true];
  }
}
