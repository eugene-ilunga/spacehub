<?php

namespace App\Http\Controllers\Admin\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpaceCoupon\StoreCouponRequest;
use App\Http\Requests\SpaceCoupon\UpdateCouponRequest;
use App\Models\BasicSettings\Basic;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AdminCouponController extends Controller
{
    protected $websiteSettings;
    protected $currentLang;

    public function __construct()
    {
        // Access the website settings singleton
        $this->websiteSettings = App::make('websiteSettings');
        $this->currentLang = App::make('currentLanguage');
    }
    public function index(Request $request)
    {
        $name = $code = null;
        if ($request->filled('name')) {
            $name = $request['name'];
        }

        if ($request->filled('code')) {
            $code = $request['code'];
        }

        $data['coupons'] = SpaceCoupon::orderByDesc('id')
            ->where('name', 'like', '%' . $name . '%')
            ->where('code', 'like', '%' . $code . '%')
            ->paginate(10);

        $data['currencyInfo'] = $this->websiteSettings;
        
        $data['sellers'] = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('sellers.id', 'sellers.username')
            ->get();

        $data['spaceType'] = Basic::select('fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental')->first();

        $data['spaces'] = Space::query()
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->where([
                ['spaces.seller_id', 0],
                ['spaces.space_status', 1],
                ['spaces.space_type', '<>', null],
            ])
            ->where('space_contents.language_id', $this->currentLang->id)
            ->select(
                'spaces.id',
                'spaces.seller_id',
                'spaces.space_type',
                'spaces.space_status as status',
                'space_contents.title as space_title',
                'space_contents.slug'
            )
            ->get();
        $data['allSpaces'] = Space::query()
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->where([

                ['spaces.space_status', 1],
                ['spaces.space_type', '<>', null],
            ])
            ->where('space_contents.language_id', $this->currentLang->id)
            ->select(
                'spaces.id',
                'spaces.seller_id',
                'spaces.space_type',
                'spaces.space_status as status',
                'space_contents.title as space_title',
                'space_contents.slug'
            )
            ->get();
        return view('admin.space-management.coupon.index', $data);
    }
    public function store(StoreCouponRequest $request)
    {
        
        // Validate the request
        $validatedData = $request->validated();

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
    public function update(UpdateCouponRequest $request)
    {

        $id = $request->input('id'); // Get the coupon ID from the request data
        // Validate the request
        $validatedData = $request->validated();

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

    public function destroy($id)
    {
        SpaceCoupon::destroy($id);
        return redirect()->back()->with('success', __('Coupon deleted successfully') . '!');
    }
}
