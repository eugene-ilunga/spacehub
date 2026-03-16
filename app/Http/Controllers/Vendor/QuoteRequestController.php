<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\GetQuote;
use App\Models\Package;
use App\Models\Seller;
use App\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class QuoteRequestController extends Controller
{
    public function index(Request $request)
    {
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

        if ($request->filled('quote_status')) {
            $status = $request['quote_status'];
        }

        $data['quoteRequests'] = GetQuote::when($requestId, function (Builder $query, $requestId) {
            return $query->where('booking_number', 'like', '%' . $requestId . '%');
        })
            ->when($status, function (Builder $query, $status) {
                return $query->where('get_quotes.status', '=', $status);
            })
            ->leftJoin('space_contents', function ($join) use ($langId) {
                $join->on('get_quotes.space_id', '=', 'space_contents.space_id')
                    ->where('space_contents.language_id', $langId);
            })
            ->leftJoin('spaces', 'spaces.id', '=', 'get_quotes.space_id')
            ->where('sellers.id', $sellerId)
            ->whereIn('get_quotes.space_id',$spaceIds)
            ->leftJoin('sellers', 'get_quotes.seller_id', '=', 'sellers.id')
            ->select('get_quotes.*', 'space_contents.title', 'space_contents.slug', 'sellers.username as seller_name', 'spaces.space_type')
            ->paginate(10);

        return view('vendors.quote-request.index', $data);
    }

    public function update(Request $request, $id)
    {
        $quoteRequest = GetQuote::query()->findOrFail($id);

        if ($request['quote_status'] == 'closed') {
            $quoteRequest->status = 'closed';
            $quoteRequest->save();

            $request->session()->flash('success', __('Quote request status updated successfully') . '!');
        } else if ($request['quote_status'] == 'pending') {
            $quoteRequest->status = 'pending';
            $quoteRequest->save();

            $request->session()->flash('success', __('Quote request status updated successfully') . '!');
        } else if ($request['quote_status'] == 'responded') {
            $quoteRequest->status = 'responded';
            $quoteRequest->save();

            $request->session()->flash('success', __('Quote request status updated successfully') . '!');
        } else if ($request['quote_status'] == 'in_progress') {
            $quoteRequest->status = 'in_progress';
            $quoteRequest->save();

            $request->session()->flash('success', __('Quote request status updated successfully') . '!');
        } else {
            $quoteRequest->status = 'cancelled';
            $quoteRequest->save();
            GetQuote::prepareMailForQuoteRequest($quoteRequest, 'quote_request_status');
            $request->session()->flash('success', __('Quote request status updated successfully') . '!');
        }
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $quoteRequest = GetQuote::findOrFail($id);
        $quoteRequest->delete();
        session()->flash('success', __('Quote request information delete successfully') . '!');
        return redirect()->back();
    }
    public function bulkDestroy(Request $request)
    {

        $ids = $request->ids;
        foreach ($ids as $id) {
            $quoteRequest = GetQuote::findOrFail($id);
            $quoteRequest->delete();
        }
        $request->session()->flash('success',  __('Quote request information delete successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }
}
