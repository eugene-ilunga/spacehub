<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BookForTour;
use Illuminate\Support\Facades\Response;
use App\Models\Seller;
use Illuminate\Http\Request;

class TourRequestController extends Controller
{
    public function index(Request $request)
    {
        $requestId = $status  = $seller = null;
        if ($request->filled('request_id')) {
            $requestId = $request['request_id'];
        }

        if ($request->filled('tour_status')) {
            $status = $request['tour_status'];
        }
        if ($request->filled('seller')) {
            $seller = $request['seller'];
        }

        // get language form session
        $language = getAdminLanguage();

        $langId = $language->id;

        $data['tourRequests'] = BookForTour::when($requestId, function (Builder $query, $requestId) {
            return $query->where('booking_number', 'like', '%' . $requestId . '%');
        })
            ->when($seller, function (Builder $query, $seller) {
                if ($seller == 'admin') {
                    $seller_id = 0;
                } else {
                    $seller_id = $seller;
                }
                return $query->where('seller_id', '=', $seller_id);
            })
            ->when($status, function (Builder $query, $status) {
                return $query->where('book_for_tours.status', '=', $status);
            })

            ->join('space_contents', function ($join) use ($langId) {
                $join->on('book_for_tours.space_id', '=', 'space_contents.space_id')
                    ->where('space_contents.language_id', $langId)
                    ->whereNotNull('space_contents.title');
            })
            ->leftJoin('sellers', 'book_for_tours.seller_id', '=', 'sellers.id')
            ->select('book_for_tours.*', 'space_contents.title', 'space_contents.slug','sellers.username as seller_name')
            ->paginate(10);
        $data['sellers'] = Seller::select('id', 'username')->where('id', '!=', 0)->get();

        return view('admin.tour-request.index', $data);
    }
    
    public function update(Request $request, $id)
    {
        
        $tourRequest = BookForTour::query()->findOrFail($id);
       
        $tourStatusMgs = __('Tour request status updated successfully') . '!';

        if ($request['tour_status'] == 'confirmed') {
            $tourRequest->status = 'confirmed';
            $tourRequest->save();
            
            BookForTour::prepareMailForTourRequest($tourRequest, 'tour_request_confirm_status');

            $request->session()->flash('success', $tourStatusMgs);
        } else if ($request['tour_status'] == 'pending') {
            $tourRequest->status = 'pending';
            $tourRequest->save();

            $request->session()->flash('success', $tourStatusMgs);
        } else if ($request['tour_status'] == 'completed') {
            $tourRequest->status = 'completed';
            $tourRequest->save();

            $request->session()->flash('success', $tourStatusMgs);
        } else {
            $tourRequest->status = 'cancelled';
            $tourRequest->save();
            BookForTour::prepareMailForTourRequest($tourRequest, 'tour_request_cancel_status');
        }
        $request->session()->flash('success', $tourStatusMgs);
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
