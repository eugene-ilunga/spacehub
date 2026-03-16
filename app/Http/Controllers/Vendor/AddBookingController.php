<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Jobs\ProcessBooking;
use App\Models\BasicSettings\Basic;
use App\Models\GlobalDay;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceHoliday;
use App\Models\SpaceService;
use App\Models\SubService;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AddBookingController extends Controller
{
    protected $websiteSettings;
    public function __construct()
    {
        // Access the website settings singleton
        $this->websiteSettings = App::make('websiteSettings');
    }

    public function spaceSelect()
    {
        $vendor = Auth::guard('seller')->user();
        $currentLang = getVendorLanguage();
        $membership = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
            ->where([
                ['sellers.id', '=', $vendor->id],
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('sellers.id', 'sellers.username')
            ->first();
        $hasMembership = SellerPermissionHelper::currentPackagePermission($membership->id);
        $existFeatures = json_decode($hasMembership->package_feature, true);
        $outputFeatureArray = [];
        $spaceType = [];

        foreach ($existFeatures as $value) {
            if ($value == "Fixed Timeslot Rental" || $value == "Hourly Rental" || $value == "Multi Day Rental") {
                $key = strtolower(str_replace(' ', '_', $value));
                $key = str_replace('timeslot', 'time_slot', $key);
                $outputFeatureArray[$key] = $value;
            }
            if ($value == "Fixed Timeslot Rental") {
                $spaceType[] = 1;
            } elseif ($value == "Hourly Rental") {
                $spaceType[] = 2;
            } elseif ($value == "Multi Day Rental") {
                $spaceType[] = 3;
            }
        }
        $data['features'] = $outputFeatureArray;
        $data['seller_id'] = $membership->id;

        if (!$membership) {
            return redirect()->back()->with('error', __('Your membership is not active.'));
        }
        $data['spaceType'] = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();

        $data['spaces'] = Space::query()
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->where([
                ['spaces.seller_id', $vendor->id],
                ['spaces.space_status', 1],
                ['spaces.space_type', '<>', null],
            ])
            ->whereIn('spaces.space_type', $spaceType)
            ->where('space_contents.language_id', $currentLang->id)
            ->select(
                'spaces.id',
                'spaces.seller_id',
                'spaces.space_type',
                'spaces.space_status as status',
                'space_contents.title as space_title',
                'space_contents.slug'
            )
            ->get();
        return view('vendors.booking-management.add-booking.space_selection', $data);
    }

    public function getSpaceForAddBooking(Request $request)
    {
        
        $defaultLnag = getVendorLanguage();
        $vendor = Auth::guard('seller')->user();
        $spaces = Space::query()
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->where([
                ['spaces.space_status', 1],
                ['spaces.space_type', '<>', null],
                ['spaces.seller_id', $vendor->id],
                ['spaces.space_type', $request->space_type],
                ['space_contents.language_id', $defaultLnag->id],
            ])
            ->select(
                'spaces.id',
                'spaces.seller_id',
                'spaces.space_type',
                'spaces.space_status as status',
                'space_contents.title as space_title',
                'space_contents.slug'
            )
            ->get();

        return response()->json($spaces);
    }

    public function index(Request $request)
    {
       
        $validatedData = $request->validate([
            'type' => 'required|string',
            'space' => 'required|integer',
        ]);

        $language = getVendorLanguage();

        if(Auth::guard('seller')->check()){
            $data['sellerId'] = Auth::guard('seller')->user()->id;
        } else {
            abort(403, 'Unauthorized access.');
        }

        if ($request->has('seller_id') && $request->seller_id != $data['sellerId']) {
            abort(404, 'Not Found');
        }

        $data['holiday_date'] = SpaceHoliday::where('seller_id', $data['sellerId'])->get();

        $data['spaceType'] = $request->type ?? null;
        $spaceId = $request->space ?? null;

        $space = Space::where('id', $spaceId)
            ->where('seller_id', $data['sellerId']) 
            ->firstOrFail();

        $spaceContent = Space::query()->select(
            'spaces.id as space_id',
            'spaces.seller_id',
            'spaces.space_size',
            'spaces.use_slot_rent',
            'spaces.space_rent',
            'spaces.slider_images',
            'spaces.similar_space_quantity',
            'spaces.address',
            'spaces.space_type',
            'spaces.price_per_day',
            'spaces.rent_per_hour',
            'spaces.opening_time',
            'spaces.closing_time',
            'spaces.prepare_time',
            'spaces.thumbnail_image as image',
            'spaces.space_status as status',
            'spaces.max_guest',
            'spaces.min_guest',
            'space_contents.id as space_content_id',
            'space_contents.title',
            'space_contents.slug',
            'space_contents.address',
            'space_contents.description',
            'space_contents.amenities',
            'space_contents.get_quote_form_id',
            'space_contents.tour_request_form_id',
            'countries.id as country_id',
            'countries.name as country_name',
            'cities.id as city_id',
            'cities.name as city_name',
            'states.id as state_id',
            'states.name as state_name'
        )
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
            ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
            ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
            ->when('spaces.seller_id' != "0", function ($query) {
                return $query->leftJoin('memberships', 'spaces.seller_id', '=', 'memberships.seller_id')
                    ->where(function ($query) {
                        $query->where([
                            ['memberships.status', '=', 1],
                            ['memberships.start_date', '<=', now()->format('Y-m-d')],
                            ['memberships.expire_date', '>=', now()->format('Y-m-d')],
                        ])->orWhere('spaces.seller_id', '=', 0);
                    });
            })
            ->where([
                ['spaces.space_status', '=', 1],
                ['space_contents.language_id', '=', $language->id],
                ['space_contents.space_id', '=', $spaceId],
            ])
            ->firstOrFail();

        if (!empty($spaceContent) && $spaceContent->space_type == 3) {
            $quantity = $space->similar_space_quantity ?? 0;
            $spaceBookings = $this->getBookedDateForMultiDay($space->id, $quantity, $data['sellerId']);
        }
        if (!empty($spaceContent) && $spaceContent->space_type == 2) {
            $spaceBookings = SpaceBooking::where('space_id', $spaceId)->select('start_time', 'end_time',  'booking_type', 'booking_status', 'booking_date')->get();
        }
        if (!empty($spaceContent) && $spaceContent->space_type == 1) {
            $spaceBookings = SpaceBooking::where('space_id', $spaceId)->select('start_time', 'end_time', 'booking_type', 'booking_status', 'booking_date')->get();
        }

        if (!empty($spaceContent)) {
            $data['serviceContentsWithSubservice'] = SpaceService::query()
                ->select(
                    'space_services.*',
                    'space_service_contents.title as service_title',
                    'space_service_contents.slug'
                )
                ->join('space_service_contents', 'space_services.id', '=', 'space_service_contents.space_service_id')
                ->with(['subServices' => function ($query) use ($language) {
                    $query->select('sub_services.*', 'sub_service_contents.title as sub_service_title')
                        ->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
                        ->where('sub_service_contents.language_id', $language->id);
                }])
                ->where([
                    ['space_services.status', '=', 1],
                    ['space_services.space_id', '=', $spaceId],
                    ['space_services.has_sub_services', '=', 1],
                    ['space_service_contents.language_id', '=', $language->id],
                ])
                ->orderBy('serial_number', 'desc')
                ->get();

            $data['serviceContentsWithoutSubservice'] = SpaceService::query()->join('space_service_contents', 'space_services.id', '=', 'space_service_contents.space_service_id')
                ->where([
                    ['space_services.status', '=', 1],
                    ['space_services.seller_id', '=', $spaceContent->seller_id],
                    ['space_services.space_id', '=', $spaceId],
                    ['space_services.has_sub_services', '=', 0],
                    ['space_service_contents.language_id', '=', $language->id],
                ])->select(
                    'space_services.*',
                    'space_service_contents.title as title_without_subservice',
                    'space_service_contents.slug'
                )->orderBy('serial_number', 'desc')->get();

            // Fetch subservice data separately
            if (!empty($data['serviceContentsWithSubservice'])) {
                $servicesIds = $data['serviceContentsWithSubservice']->pluck('id');
                $data['subservices'] = SubService::query()
                    ->select(
                        'sub_services.*',
                        'sub_service_contents.title as subservice_title',
                        'sub_service_contents.slug',
                        'sub_service_contents.language_id'
                    )
                    ->join('sub_service_contents', 'sub_services.id', '=', 'sub_service_contents.sub_service_id')
                    ->whereIn('sub_services.service_id', $servicesIds)
                    ->where('sub_service_contents.language_id', '=', $language->id)
                    ->orderBy('sub_services.id', 'desc')
                    ->get();
                $reviews = $space->review()->orderByDesc('id')->get();
                $data['reviewCount'] = $reviews->count();
                $data['averageRating'] = $reviews->avg('rating');
                $reviews->map(function ($review) {
                    $review['user'] = $review->user()->first();
                });
                $weekendDays = GlobalDay::query()
                    ->select('order', 'is_weekend', 'name')
                    ->where([
                        ['space_id', '=', $spaceContent->space_id],
                        ['is_weekend', '=', 1],
                    ])
                    ->get();
                $data['spaceContent'] = $spaceContent;
                $data['reviews'] = $reviews;
                $data['space'] = $space;
                $data['weekendDays'] = $weekendDays;
                $spaceUnit = $this->websiteSettings;
                $data['spaceUnit'] = $spaceUnit;
                $data['spaceBooking'] = $spaceBookings ?? null;
                $data['quantity']  = $spaceContent->similar_space_quantity;
                $data['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
                $data['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();
                if ($spaceContent->space_type == 1) {
                    $vatAmount = $spaceContent->space_rent * ($spaceUnit->tax / 100);
                    $totalAmount = $spaceContent->space_rent + $vatAmount;
                    $subtotal = $spaceContent->space_rent;
                } elseif ($spaceContent->space_type == 2) {
                    $vatAmount = $spaceContent->rent_per_hour * ($spaceUnit->tax / 100);
                    $totalAmount = $spaceContent->rent_per_hour + $vatAmount;
                    $subtotal = $spaceContent->rent_per_hour;
                } elseif ($spaceContent->space_type == 3) {
                    $vatAmount = $spaceContent->price_per_day * ($spaceUnit->tax / 100);
                    $totalAmount = $spaceContent->price_per_day + $vatAmount;
                    $subtotal = $spaceContent->price_per_day;
                } else {
                    $vatAmount = 0.0;
                    $totalAmount = 0.0;
                    $subtotal = 0.0;
                }

                $data['vatAmount'] = $vatAmount;
                $data['totalAmount'] = $totalAmount;
                $data['subtotal'] = $subtotal;

                return view('vendors.booking-management.add-booking.add_booking', $data);
            }
        }
    }

    public function storeBookingData(Request $request)
    {
        $language = getVendorLanguage();
        $space_id = $request->spaceId;

        if (!Auth::guard('seller')->check()) {
            return redirect()->route('vendor.login');
        }

        $seller_id = Auth::guard('seller')->user()->id;

        $space = Space::select('space_type', 'id', 'space_rent', 'rent_per_hour', 'price_per_day', 'min_guest', 'max_guest', 'use_slot_rent')->where([
            ['id', $space_id],
            ['seller_id', $seller_id],
        ])->firstOrFail();

        $space_type = $space->space_type;
        $minGuest = $space->min_guest;
        $maxGuest = $space->max_guest;

        // Define the common validation rules
        $rules = [
            'spaceId' => 'required|integer',
            'paymentStatus' => 'required|string',
            'paymentGateway' => 'nullable|string',
            'taxAmount' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'totalAmount' => 'required|numeric',
            'bookingDate' => 'required',
            'numberOfGuest' => [
                'required',
                'integer',
                'min:1',
                'between:' . $minGuest . ',' . $maxGuest,
            ],
            'fullName' => 'required|string',
            'customerPhoneNumber' => 'required',
            'customerEmailAddress' => 'required|email',
            'numberOfDay' => 'nullable|integer',
        ];

        // Apply conditional validation rules based on space_type
        if ($space_type == 1) {
            // If space_type is 1, make timeSlotId required
            $rules['timeSlotId'] = 'required|integer';
        } elseif ($space_type == 2) {
            // If space_type is 2, make startTime and totalHour required
            $rules['startTime'] = 'required';
            $rules['hours'] = 'required|integer|min:1';
        }
        // Apply conditional validation for paymentGateway based on paymentStatus
        $rules['paymentGateway'] = function ($attribute, $value, $fail) use ($request) {
            if ($request->paymentStatus === 'completed' && empty($value)) {
                $fail(__('The payment gateway field is required') . '.');
            }
        };
        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]); 
        }

        $basic = $this->websiteSettings;
        $keywords = OnlineGateway::where('status', 1)->pluck('keyword')->toArray();
        $gatewayType = in_array($request->paymentGateway, $keywords) ? 'online' : 'offline';
        $timeSlotId = $request->timeSlotId ?? null;

        $timeSlotInfo = TimeSlot::query()->select('time_slot_rent', 'start_time', 'end_time')->where('id', '=', $timeSlotId)->first();

        if ($space_type == 1 && !$timeSlotInfo) {
            Session::flash('warning', __('Time slot booking is disabled for this space') . '.');
            return redirect()->back();
        }

        $timeZone = now()->timezoneName;
        $timeFormat = Basic::query()->select('time_format')->first()->time_format;

        if (!empty($space) && $space_type == 1) {
            $startTime = $timeSlotInfo->start_time;
            $endTime = $timeSlotInfo->end_time;
            $inputStartTime = $startTime ? Carbon::createFromFormat('H:i:s', $startTime, 'UTC')
                ->setTimezone($timeZone)
                ->format($timeFormat === '12h' ? 'h:i A' : 'H:i') : null;

            $inputEndTime = $endTime ? Carbon::createFromFormat('H:i:s', $endTime, 'UTC')
                ->setTimezone($timeZone)
                ->format($timeFormat === '12h' ? 'h:i A' : 'H:i') : null;
            $endTimeWithoutInterval = null;
        } elseif ($space->space_type == 2) {

            $inputStartTime = $this->convertTo24hInAdminTimezone($request->startTime, $timeZone);
            $inputEndTime = $this->convertTo24hInAdminTimezone($request->endTime, $timeZone);
            $endTimeWithoutInterval = $this->convertTo24hInAdminTimezone($request->endTimeWithoutInterval, $timeZone);
            $bookingDate = Carbon::parse($request->bookingDate)->setTimezone($timeZone)->format('Y-m-d');
            $spaceId = $space->id;
            // Checks the availability of a space for hourly rental bookings
            $availability = $this->checkSpaceAvailabilityForHourlyRental($spaceId, $bookingDate, $inputStartTime, $inputEndTime, $timeFormat);


            // If space not available, flash error and redirect back
            if (!$availability['available']) {

                return response()->json([
                    'status' => 'error',
                    'type' => 'hourly',
                    'message' =>  $availability['message'],
                ]);
            }
        } else {
            $inputStartTime = null;
            $inputEndTime = null;
            $endTimeWithoutInterval = null;
        }

        $spaceRent = null;
        if ($space_type == 2) {
            $spaceRent = $space->rent_per_hour;
        } elseif ($space_type == 3) {
            $spaceRent = $space->price_per_day;
        }
        elseif($space_type == 1){
            if ($space->use_slot_rent == 1) {
                $spaceRent = $timeSlotInfo->time_slot_rent;
            } else {
                $spaceRent = $space->space_rent;
            }

        } else {
            $spaceRent = 0.00;
        }

        $taxPercentage = Basic::query()->select('basic_settings.tax')->first();
        $spaceServicesWithSubserviceData = $request['spaceServicesWithSubservice'];
        $spaceServicesWithoutSubserviceData = $request['spaceServicesWithoutSubservice'];
        $spaceServicesWithoutSubservice = [];
        $subtotal = 0.00;
        $spaceServiceMap = [];

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
                if ($spaceService->price_type === 'per person') {
                    $subtotal += $subService->price * $request['numberOfGuest'];
                } else {
                    $subtotal += $subService->price;
                }
                $spaceServiceMap[$spaceServiceId]['subServices'][] = $subService;
            }
        }

        if (isset($spaceServicesWithoutSubserviceData) && is_array($spaceServicesWithoutSubserviceData) && count($spaceServicesWithoutSubserviceData) > 0) {
            $spaceServicesWithoutSubservice = SpaceService::with([
                'serviceContents' => function ($query) use ($language) {
                    $query->where('language_id', $language->id)
                        ->select('space_service_id', 'title', 'slug', 'language_id');
                }
            ])
                ->whereIn(
                    'id',
                    array_column($spaceServicesWithoutSubserviceData, 'spaceServiceId')
                )
                ->get();

            // Add the service_title property to the SpaceService model
            foreach ($spaceServicesWithoutSubservice as $spaceService) {
                $spaceService->service_title = $spaceService->serviceContents->first()->title;
                if ($spaceService->price_type === 'per person') {
                    $subtotal += $spaceService->price * $request['numberOfGuest'];
                } else {
                    $subtotal += $spaceService->price;
                }
            }
        }

        if (isset($space)) {
            if (!empty($space->rent_per_hour) && ($space->space_type == 2)) {
                $subtotal += $space->rent_per_hour * $request['hours'];

            }
            if (!empty($space->price_per_day) && ($space->space_type == 3)) {

                $subtotal += $space->price_per_day * $request['numberOfDay'];

            }
            if ($space->space_type == 1) {
                if ($space->use_slot_rent == 1) {
                    $slotRent = $timeSlotInfo->time_slot_rent ?? 0.00;
                    $subtotal += $slotRent;
                } else {
                    $subtotal += $space->space_rent;
                }
            }
        }

        // Calculate  Grand total and tax amount
        $taxAmount = 0.00;

        if (!empty($request['discountAmount'])) {
            $discountAmount = $request['discountAmount'] ?? 0.00;
            $subtotal -= $discountAmount;
        }

        if (!empty($taxPercentage)) {
            $taxAmount = $subtotal * ($taxPercentage->tax / 100);
        }

        $grandTotal = $subtotal + $taxAmount;


        $data = [
            'userId' => null,
            'seller_id' => $seller_id,
            'orderNumber' => uniqid(),
            'numberOfGuest' => $request->numberOfGuest,
            'customerName' => $request->fullName,
            'customerPhone' => $request->customerPhoneNumber,
            'emailAddress' => $request->customerEmailAddress,
            'spaceId' => $space_id,
            'serviceStageInfo' => json_encode($request->spaceServicesWithSubservice ?? []),
            'subServiceIds' => json_encode($request->subserviceIds ?? []),
            'otherServiceInfo' => json_encode($request->spaceServicesWithoutSubservice ?? []),
            'spaceRent' => $spaceRent,
            'subTotal' => round($subtotal),
            'taxPercentage' => $basic->tax,
            'tax' => round($taxAmount),
            'grandTotal' => round($grandTotal),
            'currencyText' => $basic->base_currency_text,
            'currencyTextPosition' => $basic->base_currency_text_position,
            'currencySymbol' => $basic->base_currency_symbol,
            'currencySymbolPosition' => $basic->base_currency_symbol_position,
            'bookedBy' => 'admin',
            'paymentMethod' => $request->paymentStatus == 'completed' ? $request->paymentGateway : 'unpaid',
            'gatewayType' => $gatewayType, // need to creatr online payment gateway array and apply condition to check online or offline
            'paymentStatus' => $gatewayType == 'offline' ? 'pending' : $request->paymentStatus,
            'bookingStatus' => 'pending',
            'bookingDate' => $space_type == 3
                ? Carbon::parse($request->startDate)->setTimezone($timeZone)->format('Y-m-d')
                :  Carbon::parse($request->bookingDate)->setTimezone($timeZone)->format('Y-m-d'),
            'space_type' => $space_type,
            'discount' => $request->discountAmount,
            //space type 1
            'timeSlotId' => $request->timeSlotId ?? null,
            //space type 2
            'startTime' => $inputStartTime,
            'endTime' => $inputEndTime,
            'endTimeWithoutInterval' => $endTimeWithoutInterval,
            'customHour' => $request->hours ?? null,
            'totalHour' => $request->totalHour ?? null,
            //space type 3 
            'startDate' => Carbon::parse($request->startDate)->setTimezone($timeZone)->format('Y-m-d') ?? null,
            'endDate' => Carbon::parse($request->endDate)->setTimezone($timeZone)->format('Y-m-d') ?? null,
            'numberOfDay' => $request->numberOfDay ?? null,
            'receiptName' =>  null,
        ];

        $bookingInfo = $this->storeData($data);
        // generate an invoice in pdf format
        ProcessBooking::dispatch($bookingInfo->id)->delay(now()->addSeconds(5));

        return response()->json([
            'status' => 'success',
            'message' => __('Booking successfully created') . '!',
        ]);
    }
    
    public function storeData($data)
    {
        if (isset($data['bookedBy']) && $data['bookedBy'] == 'vendor') {
            $customerName = $data['customerName'];
        } else {
            $customerName = ($data['firstName'] ?? '') . ' ' . ($data['lastName'] ?? '');
        }

        $orderInfo = SpaceBooking::query()->create([
            'user_id'                  => $data['userId'],
            'seller_id'                => $data['seller_id'] ?? null,
            'booking_number'           => $data['orderNumber'] ?? null,
            'number_of_guest'          => $data['numberOfGuest'] ?? null,
            'customer_name'            => $customerName,
            'customer_phone'           => $data['customerPhone'] ?? null,
            'customer_email'           => $data['emailAddress'] ?? null,
            'space_id'                 => $data['spaceId'] ?? null,
            'service_stage_info'       => $data['serviceStageInfo'] ?? null,
            'sub_service_info'         => $data['subServiceIds'] ?? null,
            'other_service_info'       => $data['otherServiceInfo'] ?? null,
            'space_rent_price'         => $data['spaceRent'] ?? null,
            'sub_total'                => $data['subTotal'] ?? null,
            'tax_percentage'           => $data['taxPercentage'] ?? null,
            'tax'                      => $data['tax'] ?? null,
            'grand_total'              => $data['grandTotal'] ?? null,
            'currency_text'            => $data['currencyText'] ?? null,
            'currency_text_position'   => $data['currencyTextPosition'] ?? null,
            'currency_symbol'          => $data['currencySymbol'] ?? null,
            'currency_symbol_position' => $data['currencySymbolPosition'] ?? null,
            'payment_method'           => $data['paymentMethod'] ?? null,
            'gateway_type'             => $data['gatewayType'] ?? null,
            'payment_status'           => $data['paymentStatus'] ?? null,
            'booking_status'           => $data['bookingStatus'] ?? null,
            'booking_date'             => $data['bookingDate'] ?? null,
            'start_time'               => $data['startTime'] ?? null,
            'end_time'                 => $data['endTime'] ?? null,
            'end_time_without_interval'  => $data['endTimeWithoutInterval'] ?? null,
            'custom_hour'              => $data['customHour'] ?? null,
            'total_hour'              => $data['totalHour'] ?? null,
            'start_date'               => $data['startDate'] ?? null,
            'end_date'                 => $data['endDate'] ?? null,
            'number_of_day'            => $data['numberOfDay'] ?? null,
            'booking_type'             => $space->space_type ?? null,
            'time_slot_id'             => $data['timeSlotId'] ?? null,
            'receipt'                  => $data['receiptName'] ?? null,
            'booked_by'                => $data['bookedBy'] ?? null,
            'discount'                 => $data['discount'] ?? 0,
        ]);

        return $orderInfo;
    }

    public function getBookedDateForMultiDay($spaceId, $quantity = 0, $seller_id)
    {
        $adminTimezone = now()->timezoneName ?? config('app.timezone');
        $today = now()->setTimezone($adminTimezone);

        $bookings = SpaceBooking::where('space_id', $spaceId)
            ->where('end_date', '>=', $today)
            ->where('seller_id', '=', $seller_id)
            ->where('booking_status', '!=', 'rejected')
            ->select('start_date', 'end_date')
            ->get();

        $bookingCounts = [];

        foreach ($bookings as $booking) {
            $start = $booking->start_date;
            $end = $booking->end_date;

            // Count overlapping bookings for the same space
            $count = SpaceBooking::where([
                ['space_id', $spaceId],
                ['booking_status', '!=', 'rejected'],
                ['seller_id', '=', $seller_id],
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

            if ($count >= $quantity) {
                $bookingCounts[] = [
                    'bookingStartDate' => $start,
                    'bookingEndDate'   => $end,
                ];
            }
        }

        return $bookingCounts;
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
            \Log::error("Invalid time format: {$time}");
            return $time;
        }
    }

    // get available timeslot by date
    public function getTimeSlotsByDate(Request $request)
    {
        
        $bookingDate = $request->selectedDate;
        $space_id = $request->spaceId;
        $seller_id = $request->sellerId;
        if (Auth::guard('seller')->check()) {
            $seller_id = Auth::guard('seller')->user()->id;
        }

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
            ->where('seller_id', $seller_id)
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
}
