<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Seller;
use App\Models\SpaceHoliday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class VendorHolidayController extends Controller
{

    public function index( )
    {
        // Validate and set seller_id
        $sellerId = Auth::guard('seller')->check();
       
        if($sellerId){
            $sellerId = Auth::guard('seller')->user()->id;
        }


        // Fetch active sellers with memberships
        $information['sellers'] = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('sellers.id', 'sellers.username')
            ->get();

        // Fetch global holidays for the seller
        $information['globalHoliday'] = SpaceHoliday::when($sellerId, function ($query) use ($sellerId) {
            return $query->where('seller_id', $sellerId);
        })
            ->get();

        return view('vendors.holiday.index', $information);
    }

    public function store(Request $request)
    {

        $sellerId = Auth::guard('seller')->check();

        if ($sellerId) {
            $sellerId = Auth::guard('seller')->user()->id;
        }

        $current_package = SellerPermissionHelper::currentPackagePermission($sellerId);

        $rules = ['date' => 'required',];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(
                [
                    'errors' => $validator->getMessageBag()->toArray()
                ],
                400
            );
        }

        $holiday = SpaceHoliday::where('seller_id', $sellerId)->pluck('date')->toArray();
        
        $adminTimezone = config('app.timezone');
        $date = Carbon::parse($request->date, $adminTimezone)->format('Y-m-d');

        if (in_array($date, $holiday)) {
            $request->session()->flash('warning', __('The date exists in the holiday list') . '!');
            return Response::json(['status' => 'success'], 200);
        } else {
            SpaceHoliday::create([
                'date' => $date,
                'seller_id' => $sellerId,
            ]);
            $request->session()->flash('success', __('Holiday updated successfully') . '!');

            return Response::json(['status' => 'success'], 200);
        }
    }
    public function destroy($id)
    {
        $spaceHoliday = SpaceHoliday::find($id);
        $spaceHoliday->delete();
        return redirect()->back()->with('success', __('Holiday delete successfully') . '!');
    }

    public function blukDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $spaceHoliday = SpaceHoliday::find($id);
            $spaceHoliday->delete();
        }

        $request->session()->flash('success', __('Holiday delete successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }
}
