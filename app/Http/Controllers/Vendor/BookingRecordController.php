<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\ServiceOrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceService;
use App\Models\SubService;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BookingRecordController extends Controller
{
  public function index(Request $request)
  {

    $bookingNumber = $paymentStatus = $bookingStatus = $customerName = null;
    $language = getVendorLanguage();

    $authCheck = Auth::guard('seller')->check();
    if ($authCheck) {
      $seller_id = Auth::guard('seller')->user()->id;
      Seller::findOrFail($seller_id);
    } else {
      return redirect()->route('vendor.login', ['language' => $language->code]);
    }
    // Call the combined function to retrieve space IDs
    $spaceIds = Package::getSpaceIdsBySeller($seller_id);

    if ($request->filled('booking_number')) {
      $bookingNumber = $request['booking_number'];

    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request['payment_status'];
    }
    if ($request->filled('booking_status')) {
      $bookingStatus = $request['booking_status'];
    }
    if ($request->filled('customer_name')) {
      $customerName = $request['customer_name'];
    }
    $bookings = SpaceBooking::query()->where('seller_id', Auth::guard('seller')->user()->id)
      ->when($bookingNumber, function (Builder $query, $bookingNumber) {
        return $query->where('booking_number', 'like', '%' . $bookingNumber . '%');
      })
      ->when($customerName, function (Builder $query, $customerName) {
        return $query->where('customer_name', 'like', '%' . $customerName . '%');
      })
      ->when($paymentStatus, function (Builder $query, $paymentStatus) {
        return $query->where('payment_status', '=', $paymentStatus);
      })
      ->when($bookingStatus, function (Builder $query, $bookingStatus) {
        return $query->where('booking_status', '=', $bookingStatus);
      })
      ->whereIn('space_bookings.space_id', $spaceIds)
      ->orderByDesc('id')
      ->paginate(10);

    $language = Language::query()->where('is_default', '=', 1)->first();
    $bookings->map(function ($order) use ($language) {
      $space = $order->space()->with('spaceContents')->first();
      if ($space) {
        $titleAndSlug = $space->spaceContents->where('language_id', $language->id)->first();
        if(!(empty($titleAndSlug)))
        {
          $order['space_title'] = $titleAndSlug->title;
          $order['space_slug'] = $titleAndSlug->slug;
        }
        
      }
    });
    return view('vendors.booking-management.index', compact('bookings'));
  }

  public function show($id)
  {
    $order = SpaceBooking::query()->where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
    $data['orderInfo'] = $order;
  
    $language = getVendorLanguage();
    $space = $order->space()->with('spaceContents')->first();


    $data['space_type'] = $space->space_type;
    if ($space->space_type == 3) {
      $data['rent'] = $space->price_per_day;
      $data['type'] = __('Day');
    } else if ($space->space_type == 2) {
      $data['rent'] = $space->rent_per_hour;
      $data['type'] = __('Hour');
    } else {

      $timeSlotId = $order->time_slot_id;
      $timeSlotInfo = TimeSlot::query()->select('time_slot_rent')->where('id', '=', $timeSlotId)->first();
      if ($space->use_slot_rent == 1) {
        $data['rent'] = $timeSlotInfo->time_slot_rent ?? 0.00;
        $data['type'] = null;
      } else {
        $data['rent'] = $space->space_rent ?? 0.00;
        $data['type'] = null;
      }
    }

    if ($space) {
      $titleAndSlug = $space->spaceContents->where('language_id', $language->id)->first();
      $order['space_title'] = $titleAndSlug->title;
      $order['space_slug'] = $titleAndSlug->slug;
    }
    else
    {
      $order['space_title'] = null;
      $order['space_slug'] = null;
    }

    $stageServices = json_decode($order->service_stage_info, true);
    $otherServices = json_decode($order->other_service_info, true);

    $services = [];
    if (is_array($stageServices)) {
      $services = array_merge($services, $stageServices);
    }
    if (is_array($otherServices)) {
      $services = array_merge($services, $otherServices);
    }


    $spaceServiceMap = [];
    foreach ($services as $item) {
      $spaceServiceId = $item['spaceServiceId'];

      if (!isset($spaceServiceMap[$spaceServiceId])) {
        $spaceService = SpaceService::with([
          'serviceContents' => function ($query) use ($language) {
            $query->where('language_id', $language->id)
              ->select('space_service_id', 'title', 'slug', 'language_id');
          }
        ])->find($item['spaceServiceId']);

        if(!is_null($spaceService)){
          $spaceService->number_of_custom_day = $item['numberOfCustomDay'] ?? 1;
          $spaceService->number_of_guest = $order['number_of_guest'] ?? 1;
          $spaceService->total_hour = $order['total_hour'] ?? 1;
          $spaceService->space_type = $space->space_type ?? null;

          $spaceService->service_title = $spaceService->serviceContents->first()->title;
        }

        $spaceServiceMap[$spaceServiceId] = [
          'spaceService' => $spaceService,
          'subServices' => [],
          'global_service_total_price' => 0.00,
        ];
      }
      if (array_key_exists('subServiceId', $item)) {
        $subService = SubService::with([
          'subServiceContents' => function ($query) use ($language) {
            $query->where('language_id', $language->id)
              ->select('sub_service_id', 'title', 'slug', 'language_id');
          }
        ])->find($item['subServiceId']);

        $subService->price_type = $spaceService->price_type ?? null;

        if ($space->space_type == 3) {
          $subService->number_of_custom_day = $item['numberOfCustomDay'] ?? 1;
        } elseif ($space->space_type == 2) {
          $subService->total_hour = $order['total_hour'] ?? 1;
        }
        $subService->number_of_guest = $order['number_of_guest'] ?? 1;

        if ($subService) {
          $subService->sub_service_title = $subService->subServiceContents->first()->title;
          if ($spaceService['has_sub_services'] == 1) {
            if ($spaceService['price_type'] == 'fixed') {
              if ($spaceService['space_type'] == 3) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_custom_day'];
              } elseif ($spaceService['space_type'] == 2) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['total_hour'];
              } else {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price;
              }
            } else {
              if ($spaceService['space_type'] == 3) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_custom_day'] * $subService['number_of_guest'];
              } elseif ($spaceService['space_type'] == 2) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['total_hour'] * $subService['number_of_guest'];
              } else {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_guest'];
              }
            }
          }
        }
        $spaceServiceMap[$spaceServiceId]['subServices'][] = $subService;
      }

      if ($spaceService['has_sub_services'] != 1) {
        if ($spaceService['price_type'] == 'fixed') {
          if ($spaceService['space_type'] == 3) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_custom_day'];
          } elseif ($spaceService['space_type'] == 2) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['total_hour'];
          } else {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'];
          }
        } else {
          if ($spaceService['space_type'] == 3) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_custom_day'] * $spaceService['number_of_guest'];
          } elseif ($spaceService['space_type'] == 2) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['total_hour'] * $spaceService['number_of_guest'];
          } else {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_guest'];
          }
        }
      }

    }

    $services = array_values($spaceServiceMap);

    $data['userInfo'] = User::query()->where('id', '=', $order->user_id)->first();
    $data['services'] = $services;
    $data['language'] = $language;
    $data['basic'] = Basic::select('base_currency_symbol', 'base_currency_symbol_position','base_currency_text','base_currency_text_position')->first();

    return view('vendors.booking-management.show', $data);
  }


  public function bookingReport(Request $request)
  {
    $data['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
    $data['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

    $seller = Auth::guard('seller')->user();
    if (!$seller) {
      return redirect()->route('vendor.login');
    }
    $vendorId = $seller->id;

    // Set default date range (e.g., current month) if none provided
    $from = $to = $paymentGateway = $paymentStatus = $bookingStatus = null;
    if ($request->filled('from') && $request->filled('to')) {
      $from = Carbon::parse($request->from)->format('Y-m-d');
      $to = Carbon::parse($request->to)->format('Y-m-d');
    } else {
      // Default to current month
      $from = Carbon::now()->startOfMonth()->format('Y-m-d');
      $to = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    if ($request->filled('payment_gateway')) {
      $paymentGateway = $request->payment_gateway;
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request->payment_status;
    }
    if ($request->filled('booking_status')) {
      $bookingStatus = $request->booking_status;
    }

    // Base query for SpaceBooking with filters
    $query = SpaceBooking::query()->select(
      'booking_number',
      'customer_name',
      'customer_email',
      'space_id',
      'service_stage_info',
      'other_service_info',
      'sub_service_info',
      'sub_total',
      'tax',
      'grand_total',
      'currency_symbol',
      'currency_symbol_position',
      'payment_method',
      'payment_status',
      'booking_status',
      'space_rent_price',
      'booking_date',
      'start_time',
      'end_time',
      'created_at'
    )
      ->where('seller_id', $vendorId)
      ->whereBetween('created_at', [$from, $to])
      ->when($paymentGateway, function (Builder $query, $paymentGateway) {
        return $query->where('payment_method', '=', $paymentGateway);
      })
      ->when($paymentStatus, function (Builder $query, $paymentStatus) {
        return $query->where('payment_status', '=', $paymentStatus);
      })
      ->when($bookingStatus, function (Builder $query, $bookingStatus) {
        return $query->where('booking_status', '=', $bookingStatus);
      });

    // Paginated records for display
    $records = $query->orderByDesc('id')->paginate(10);

    // Fetch language and map space titles/slugs
    $language = Language::query()->where('is_default', '=', 1)->first();
    $records->map(function ($booking) use ($language) {
      $space = $booking->space()->with('spaceContents')->first();
      if ($space) {
        $titleAndSlug = $space->spaceContents->where('language_id', $language->id)->first();
        $booking['space_title'] = $titleAndSlug ? $titleAndSlug->title : '';
        $booking['space_slug'] = $titleAndSlug ? $titleAndSlug->slug : '';
      }
    });

    Session::put('booking_records', $records);
    $data['orders'] = $records;

    // Calculate total bookings and earnings from filtered records
    $data['totalBookings'] = $records->count();
    $data['totalEarnings'] = $records->sum('grand_total');

    // Fetch all filtered records with space relation to calculate metrics by space_type
    $filteredBookings = $query->with('space')->get();

    // Calculate metrics by space_type
    $data['timeslotBookings'] = $filteredBookings->where('space.space_type', 1)->count();
    $data['timeslotEarnings'] = $filteredBookings->where('space.space_type', 1)->sum('grand_total');
    $data['hourlyBookings'] = $filteredBookings->where('space.space_type', 2)->count();
    $data['hourlyEarnings'] = $filteredBookings->where('space.space_type', 2)->sum('grand_total');
    $data['multidayBookings'] = $filteredBookings->where('space.space_type', 3)->count();
    $data['multidayEarnings'] = $filteredBookings->where('space.space_type', 3)->sum('grand_total');

    $data['basic'] = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position')->first();

    // Pass default date range to the view for form initialization
    $data['from'] = $from;
    $data['to'] = $to;

    return view('vendors.booking-management.report', $data);
  }

  public function sendMail(Request $request, $id)
  {
    // validation
    $rules = [
      'subject' => 'required|max:255'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput()
        ->with('openVendorEmailModal', $id);
    }
    $bookingRecord = SpaceBooking::query()->find($id);
    $mailData = [];
    $mailData['subject'] = $request->subject;

    if ($request->filled('message')) {
      $msg = $request->message;
    } else {
      $msg = '';
    }

    $mailData['body'] = $msg;

    $mailData['recipient'] = $bookingRecord->customer_email;

    $mailData['sessionMessage'] = __('Mail has been sent successfully') . '!';

    BasicMailer::sendMail($mailData);
    return redirect()->back();
  }


  public function exportReport()
  {
    if (Session::has('booking_records')) {
      $bookingRecords = Session::get('booking_records');

      if (count($bookingRecords) == 0) {
        Session::flash('warning', __('No booking records found to export') . '!');

        return redirect()->back();
      } else {
        return Excel::download(new ServiceOrdersExport($bookingRecords), 'service-orders.csv');
      }
    } else {
      Session::flash('error', __('There has no booking records to export') . '.');

      return redirect()->back();
    }
  }


  public function destroy(Request $request)
  {
    $this->deleteBooking($request->id);

    return redirect()->back()->with('success', __('Booking record deleted successfully') . '!');
  }
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteBooking($id);
    }

    $request->session()->flash('success', __('Booking records deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  // order deletion code
  public function deleteBooking($id)
  {
    $booking = SpaceBooking::query()->find($id);

    // delete the invoice
    @unlink(public_path('assets/file/invoices/space/' . $booking->invoice));

    // delete s-order
    $booking->delete();
  }


}
