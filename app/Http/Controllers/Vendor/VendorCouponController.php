<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Requests\SpaceCoupon\StoreCouponRequest;
use App\Http\Requests\SpaceCoupon\UpdateCouponRequest;
use App\Models\BasicSettings\Basic;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorCouponController extends Controller
{
  protected $websiteSettings;
  protected $currentLang;

  public function __construct()
  {
    // Access the website settings singleton
    $this->websiteSettings = App::make('websiteSettings');
    // $this->currentLang = App::make('vendorCurrentLanguage');
  }
  public function index(Request $request)
  {
    $language = getVendorLanguage();

    $existFeatures = [];
    $outputFeatureArray = [];
    $spaceType = [];

    $vendor = Auth::guard('seller')->user();

    $name = $code = null;
    if ($request->filled('name')) {
      $name = $request['name'];
    }

    if ($request->filled('code')) {
      $code = $request['code'];
    }

    $data['coupons'] = SpaceCoupon::where('seller_id', $vendor->id)
      ->when($name, function ($query) use ($name) {
        return $query->where('name', 'like', '%' . $name . '%');
      })
      ->when($code, function ($query) use ($code) {
        return $query->where('code', 'like', '%' . $code . '%');
      })
      ->orderByDesc('id')->paginate(10);
    $data['currencyInfo'] = $this->websiteSettings;
    $membership = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
      ->where([
        ['sellers.id', '=', $vendor->id],
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->select('sellers.id', 'sellers.username')
      ->first();

    if ($membership) {
      $hasMembership = SellerPermissionHelper::currentPackagePermission($membership->id);
      $existFeatures = json_decode(optional($hasMembership)->package_feature, true) ?? [];
    }
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
    $data['seller_id'] = $vendor->id;

    $data['spaceType'] = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();

    $data['spaces'] = Space::query()
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['spaces.seller_id', $vendor->id],
        ['spaces.space_status', 1],
        ['spaces.space_type', '<>', null],
      ])
      ->whereIn('spaces.space_type', $spaceType)
      ->where('space_contents.language_id', $language->id)
      ->select(
        'spaces.id',
        'spaces.seller_id',
        'spaces.space_type',
        'spaces.space_status as status',
        'space_contents.title as space_title',
        'space_contents.slug'
      )
      ->get();

    return view('vendors.space-management.coupon.index', compact('data'));
  }

  public function store(StoreCouponRequest $request)
  {
    $vendor_id = Auth::guard('seller')->user()->id;
    $language = getVendorLanguage();

    if ($vendor_id != 0) {

      $hasMembership = SellerPermissionHelper::currentPackagePermission($vendor_id);
      if ($hasMembership != null) {
        $existFeatures = json_decode($hasMembership->package_feature, true);
      } else {
        session()->flash('warning', __('It appears that you currently do not have a membership') . '. ' . __('Please consider purchasing a plan to enjoy our services') . '.');
        return redirect()->route('vendor.plan.extend.index', ['language' => $language->code]);
      }
    }
    // Validate the request
    $validatedData = $request->validated();
    $validatedData['seller_id'] = $vendor_id;

    if (isset($validatedData['spaces']) && is_array($validatedData['spaces'])) {
      $validatedData['spaces'] = json_encode($validatedData['spaces']);
    }

    // Format the start_date and end_date
    $adminTimezone = now()->timezoneName;
    $validatedData['start_date'] = Carbon::parse($request->start_date, $adminTimezone)->format('Y-m-d');
    $validatedData['end_date'] = Carbon::parse($request->end_date, $adminTimezone)->format('Y-m-d');

    SpaceCoupon::create($validatedData);
    $request->session()->flash('success', __('New coupon added successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    SpaceCoupon::destroy($id);
    return redirect()->back()->with('success', __('Coupon deleted successfully') . '!');
  }

  public function getCouponData()
  {
    $vendor = Auth::guard('seller')->user();
    $data['currencyInfo'] = $this->websiteSettings;
    $membership = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
      ->where([
        ['sellers.id', '=', $vendor->id],
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->select('sellers.id', 'sellers.username')
      ->first();

    if (!$membership) {
      return response()->json(['warning' => __('Your membership is not active') . '.'], 403);
    }

    $data['seller_id'] = $membership->id;
    $data['vendor_dashboard'] = 'vendor_dashboard';


    return response()->json($data);
  }

  public function update(UpdateCouponRequest $request)
  {

    $vendor_id = Auth::guard('seller')->user()->id;
    $id = $request->input('id'); // Get the coupon ID from the request data
    // Validate the request
    $validatedData = $request->validated();
    $validatedData['seller_id'] = $vendor_id;

    if (isset($validatedData['spaces']) && is_array($validatedData['spaces'])) {
      $validatedData['spaces'] = json_encode($validatedData['spaces']);
    }

    // Format the start_date and end_date
    $adminTimezone = now()->timezoneName;
    $validatedData['start_date'] = Carbon::parse($request->start_date, $adminTimezone)->format('Y-m-d');
    $validatedData['end_date'] = Carbon::parse($request->end_date, $adminTimezone)->format('Y-m-d');

    SpaceCoupon::find($id)->update($validatedData);
    $request->session()->flash('success', __('Coupon updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function getSpaceForCoupon(Request $request)
  {
    $language = getVendorLanguage();
    $spaces = Space::query()
      ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->where([
        ['spaces.space_status', 1],
        ['spaces.space_type', '<>', null],
        ['spaces.seller_id', $request->seller_id],
        ['spaces.space_type', $request->space_type],
        ['space_contents.language_id', $language->id],
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
}
