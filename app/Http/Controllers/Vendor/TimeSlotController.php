<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Requests\Space\StoreTimeSlotRequest;
use App\Http\Requests\Space\UpdateTimeSlotRequest;
use App\Models\BasicSettings\Basic;
use App\Models\GlobalDay;
use App\Models\Language;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TimeSlotController extends Controller
{
  public function index(Request $request)
  {

    $language = getVendorLanguage();
    $data['space'] = Space::query()->select(
      'spaces.id as space_id',
      'spaces.seller_id',
      'space_contents.title'
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', '=', $language->id],
        ['spaces.space_status', '=', 1],
        ['spaces.id', '=', $request->space_id],
        ['spaces.seller_id', '=', Auth::guard('seller')->user()->id],
      ])->firstOrFail();

    $data['days'] = GlobalDay::where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['space_id', $request->space_id],
    ])->get();

    $data['space_id'] = $request->space_id;
    $sellerId = Auth::guard('seller')->user()->id;
    $data['seller_id'] = $sellerId;
    $data['currentPackage'] = SellerPermissionHelper::currentPackagePermission($sellerId);

    return view('vendors.time-slot.index', $data);
  }
  public function manageWeekend(Request $request)
  {

    $language = getVendorLanguage();

    $data['space'] = Space::query()->select(
      'spaces.id as space_id',
      'spaces.seller_id',
      'space_contents.title'
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', '=', $language->id],
        ['spaces.space_status', '=', 1],
        ['spaces.id', '=', $request->space_id],
      ])->firstOrFail();

    $data['days'] = GlobalDay::where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['space_id', $request->space_id],
    ])->get();
    
    $data['space_id'] = $request->space_id;
    $sellerId = Auth::guard('seller')->user()->id;
    $data['seller_id'] = $sellerId;
    $data['currentPackage'] = SellerPermissionHelper::currentPackagePermission($sellerId);

    return view('vendors.time-slot.weekend', $data);
  }

  public function updateWeekend(Request $request, $id)
  {

    $days = GlobalDay::query()->findOrFail($id);
    $days->update([
      'is_weekend' => $request->is_weekend,
    ]);
    return redirect()->back()->with('success', __('Weekend updated successfully') . '!');
  }

  public function manageSchedule(Request $request)
  {

    $languages = Language::all();
    $data['languages'] = $languages;
    $defaultLanguageId = getVendorLanguage()->id;

    $sellerId = Auth::guard('seller')->user()->id;
    $seller = Seller::where('id', $sellerId)->firstOrFail();
    $spaceId = $request->space_id;

    $data['day_id'] = $request->day_id;
    $data['space_id'] = $spaceId;
    $data['seller_id'] = $seller->id;

    $data['spaceContent'] = Space::query()->select(
      'spaces.id as space_id',
      'spaces.seller_id',
      'space_contents.title',
      'spaces.use_slot_rent',
    )
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['space_contents.language_id', '=', $defaultLanguageId],
        ['spaces.space_status', '=', 1],
        ['spaces.id', '=', $spaceId],
        ['spaces.seller_id', '=', $seller->id],
      ])->firstOrFail();

     

    $data['day'] = GlobalDay::where([
      ['space_id', $spaceId],
      ['id', $request->day_id],
    ])->firstOrFail();

    $data['timeSlots'] = TimeSlot::query()->select('time_slots.*', 'global_days.name')
      ->join('global_days', 'time_slots.global_day_id', '=', 'global_days.id')
      ->where([
        ['time_slots.global_day_id', $request->day_id],
        ['time_slots.space_id', $spaceId],
      ])
      ->paginate(10);


    $timeFormat = Basic::value('time_format');
    if ($timeFormat == '12h') {
      $data['time_format'] = 'h:i A';
    } else {
      $data['time_format'] = 'H:i';
    }
    $data['time_zone'] = now()->timezoneName;

    return view('vendors.time-slot.manage-time-slot', $data);
  }

  public function storeTimeSlot(StoreTimeSlotRequest $request)
  {


    $now = Carbon::now();
    $timezone = $now->timezoneName;
    $space = Space::where('id', $request->space_id)->firstOrFail();

    $inputStartTime = trim($request->input('start_time')); 
    $inputEndTime = trim($request->input('end_time'));

    //  Detect format dynamically
    $startFormat = str_contains(strtolower($inputStartTime), 'am') || str_contains(strtolower($inputStartTime), 'pm') ? 'g:i A' : 'H:i';
    $endFormat   = str_contains(strtolower($inputEndTime), 'am') || str_contains(strtolower($inputEndTime), 'pm') ? 'g:i A' : 'H:i';

    // Convert from admin timezone to UTC
    try {
      $startTimeUtc = Carbon::createFromFormat($startFormat, $inputStartTime, $timezone)
        ->setTimezone('UTC')
        ->format('H:i:s');

      $endTimeUtc = Carbon::createFromFormat($endFormat, $inputEndTime, $timezone)
        ->setTimezone('UTC')
        ->format('H:i:s');
    } catch (\Exception $e) {
      return back()->withErrors(['msg' => 'Invalid time format. Please check your input.']);
    }

    $in = $request->all();

    $in['start_time'] = $startTimeUtc;
    $in['end_time'] = $endTimeUtc;
    $in['space_id']   = $space->id;

    TimeSlot::create($in);

    $request->session()->flash('success', __('New time slot added successfully') . '!');
    return response()->json(['status' => 'success'], 200);
  }
  public function update(UpdateTimeSlotRequest $request)
  {

    $timeSlot = TimeSlot::query()->findOrFail($request->id);
    $inputStartTime = trim($request->input('start_time'));
    $inputEndTime = trim($request->input('end_time'));

    $timezone = now()->timezoneName;
    // Dynamically detect time format
    $format = (preg_match('/AM|PM/i', $inputStartTime) || preg_match('/AM|PM/i', $inputEndTime)) ? 'h:i A' : 'H:i';

    try {
      // Convert to UTC before storing
      $start = Carbon::createFromFormat($format, $inputStartTime,  $timezone)
        ->setTimezone('UTC')
        ->format('H:i:s');

      $end = Carbon::createFromFormat($format, $inputEndTime, $timezone)
        ->setTimezone('UTC')
        ->format('H:i:s');


      $timeSlot->start_time = $start;
      $timeSlot->end_time = $end;
      $timeSlot->number_of_booking = $request->number_of_booking ?? null;
      $timeSlot->time_slot_rent = $request->time_slot_rent ?? null;
      $timeSlot->save();

      $request->session()->flash('success', __('Time slot updated successfully') . '!');
      return response()->json(['status' => 'success'], 200);
    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => 'Invalid time format.'], 422);
    }
  }
  public function destroy(Request  $request)
  {
    $timeSchedule = TimeSlot::where('id', $request->time_slot_id)->firstOrFail();
    if ($timeSchedule) {
      $timeSchedule->delete();
      return redirect()->back()->with('success', __('Time schedule deleted successfully') . '!');
    }
  }

  public function bulkDestroy(Request  $request)
  {
    $ids = $request->ids;
    if ($ids) {
      foreach ($ids as $id) {
        $timeSchedule = TimeSlot::where('id', $id)->firstOrFail();
        if ($timeSchedule) {
          $timeSchedule->delete();
        }
      }
      $request->session()->flash('success', __('Time schedule deleted successfully') . '!');
      return Response::json(['status' => 'success'], 200);
    }
  }
}
