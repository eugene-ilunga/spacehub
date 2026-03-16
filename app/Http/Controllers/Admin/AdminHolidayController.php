<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Seller;
use App\Models\SpaceHoliday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class AdminHolidayController extends Controller
{
    public function sellerSelect()
    {
        $information['sellers'] = Seller::join('memberships', 'sellers.id', '=', 'memberships.seller_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('sellers.id', 'sellers.username')
            ->get();
        return view('admin.holiday.select-vendor', $information);
    }
    public function index(Request $request)
    {

        // Validate and set seller_id
        $sellerId = $request->filled('seller_id') ? $request->seller_id : null;

        // Handle 'admin' case
        if ($sellerId === 'admin') {
            $sellerId = 0;
        }

        // Check membership if seller_id is not 0
        if ($sellerId != 0) {
            $hasMembership = SellerPermissionHelper::currentPackagePermission($sellerId);
            if (!$hasMembership) {
                return redirect()->back()->with('warning', __('This vendor is not available') . '!');
            }
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
        $information['globalHoliday'] = SpaceHoliday::when($sellerId !== null, function ($query) use ($sellerId) {
            return $query->where('seller_id', $sellerId);
        })
            ->get();

        return view('admin.holiday.index', $information);
    }

    public function store(Request $request)
    {
        if ($request->seller_id == 'admin') {
            $seller_id = 0;
        } else {
            $seller_id = $request->seller_id;
        }

        $current_package = SellerPermissionHelper::currentPackagePermission($seller_id);

        if ($seller_id != 0) {
            $current_package = SellerPermissionHelper::currentPackagePermission($seller_id);
            if ($current_package == null) {
                $request->session()->flash('warning', __('No packages available for this vendor') . '!');
                return Response::json(['status' => 'success'], 200);
            }
        }
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

        $holiday = SpaceHoliday::where('seller_id', $seller_id)->pluck('date')->toArray();

        $adminTimezone = config('app.timezone');
        $date = Carbon::parse($request->date, $adminTimezone)->format('Y-m-d');


        if (in_array($date, $holiday)) {
            $request->session()->flash('warning', __('The date exists in the holiday list') . '!');
            return Response::json(['status' => 'success'], 200);
        } else {
            SpaceHoliday::create([
                'date' => $date,
                'seller_id' => $seller_id,
            ]);
            $request->session()->flash('success', __('Holiday added successfully') . '!');

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
