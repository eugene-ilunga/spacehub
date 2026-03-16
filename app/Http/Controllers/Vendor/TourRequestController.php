<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BookForTour;
use App\Models\Package;
use App\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class TourRequestController extends Controller
{
    public function index(Request $request)
    {
        // get language form session
        $language = getVendorLanguage();
        $langId = $language->id;
        if (Auth::guard('seller')->check()) {
            $sellerId = Auth::guard('seller')->user()->id;
        } else {
            return redirect()->route('vendor.login', ['language' => $language->code]);
        }

        // Call the combined function to retrieve space IDs
        $spaceIds = Package::getSpaceIdsBySeller($sellerId);

        $requestId = $status = null;
        if ($request->filled('request_id')) {
            $requestId = $request['request_id'];
        }
        if ($request->filled('tour_status')) {
            $status = $request['tour_status'];
        }

        $data['tourRequests'] = BookForTour::when($requestId, function (Builder $query, $requestId) {
            return $query->where('booking_number', 'like', '%' . $requestId . '%');
        })

            ->when($status, function (Builder $query, $status) {
                return $query->where('book_for_tours.status', '=', $status);
            })
            ->leftJoin('space_contents', function ($join) use ($langId) {
                $join->on('book_for_tours.space_id', '=', 'space_contents.space_id')
                    ->where('space_contents.language_id', $langId);
            })
            ->leftJoin('spaces', 'spaces.id', '=', 'book_for_tours.space_id')
            ->where('sellers.id', $sellerId)
            ->whereIn('book_for_tours.space_id', $spaceIds)
            ->leftJoin('sellers', 'book_for_tours.seller_id', '=', 'sellers.id')
            ->select('book_for_tours.*', 'space_contents.title', 'space_contents.slug', 'sellers.username as seller_name', 'spaces.space_type')
            ->paginate(10);

        return view('vendors.tour-request.index', $data);
    }
    public function update(Request $request, $id)
    {
        $tourRequest = BookForTour::query()->findOrFail($id);

        if ($request['tour_status'] == 'confirmed') {
            $tourRequest->status = 'confirmed';
            $tourRequest->save();
            BookForTour::prepareMailForTourRequest($tourRequest, 'tour_request_confirm_status');
            $request->session()->flash('success', __('Tour request status updated successfully') . '!');
        } else if ($request['tour_status'] == 'pending') {
            $tourRequest->status = 'pending';
            $tourRequest->save();

            $request->session()->flash('success', __('Tour request status updated successfully') . '!');
        } else if ($request['tour_status'] == 'closed') {
            $tourRequest->status = 'closed';
            $tourRequest->save();

            $request->session()->flash('success', __('Tour request status updated successfully') . '!');
        } else {
            $tourRequest->status = 'cancelled';
            $tourRequest->save();
            BookForTour::prepareMailForTourRequest($tourRequest, 'tour_request_cancel_status');
            $request->session()->flash('success', __('Tour request status updated successfully') . '!');
        }
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $tourRequest = BookForTour::findOrFail($id);
        $tourRequest->delete();
        session()->flash('success', __('Tour request information delete successfully') . '!');
        return redirect()->back();
    }
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $tourRequest = BookForTour::findOrFail($id);
            $tourRequest->delete();
        }
        $request->session()->flash('success', __('Tour request information delete successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }
}
