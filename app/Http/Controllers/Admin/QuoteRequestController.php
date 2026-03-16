<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GetQuote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use App\Models\Seller;
use Illuminate\Http\Request;

class QuoteRequestController extends Controller
{
    public function index(Request $request)
    {

        $status  = $seller = $requestId = null;

        if ($request->filled('quote_status')) {
            $status = $request['quote_status'];
        }
        if ($request->filled('seller')) {
            $seller = $request['seller'];
        }
        if ($request->filled('request_id')) {
            $requestId = $request['request_id'];
        }
        // get language form session
        $language = getAdminLanguage();

        $langId = $language->id;

        $data['quoteRequests'] = GetQuote::when($seller, function (Builder $query, $seller) {
                if ($seller == 'admin') {
                    $seller_id = 0;
                } else {
                    $seller_id = $seller;
                }
                return $query->where('seller_id', '=', $seller_id);
            })
            ->when($status, function (Builder $query, $status) {
                return $query->where('get_quotes.status', '=', $status);
            })
            ->when($requestId, function (Builder $query, $requestId) {
                return $query->where('booking_number', 'like', '%' . $requestId . '%');
            })
            ->join('space_contents', function ($join) use ($langId) {
                $join->on('get_quotes.space_id', '=', 'space_contents.space_id')
                    ->where('space_contents.language_id', $langId)
                    ->whereNotNull('space_contents.title');
            })
            ->leftJoin('sellers', 'get_quotes.seller_id', '=', 'sellers.id')
            ->select('get_quotes.*', 'space_contents.title', 'space_contents.slug', 'sellers.username as seller_name')
            ->paginate(10);

        $data['sellers'] = Seller::select('id', 'username')->where('id', '!=', 0)->get();

        return view('admin.quote-request.index', $data);
    }

    public function update(Request $request, $id)
    {
        $quoteRequest = GetQuote::query()->findOrFail($id);
        $statusMsg = __('Quote request status updated successfully') . '!';

        if ($request['quote_status'] == 'closed') {
            $quoteRequest->status = 'closed';
            $quoteRequest->save();

            $request->session()->flash('success', $statusMsg);
        } else if ($request['quote_status'] == 'pending') {
            $quoteRequest->status = 'pending';
            $quoteRequest->save();

            $request->session()->flash('success', $statusMsg );
        } else if ($request['quote_status'] == 'responded') {
            $quoteRequest->status = 'responded';
            $quoteRequest->save();

            $request->session()->flash('success', $statusMsg);
        } else if ($request['quote_status'] == 'in_progress') {
            $quoteRequest->status = 'in_progress';
            $quoteRequest->save();

            $request->session()->flash('success', $statusMsg);
        } else {
            $quoteRequest->status = 'cancelled';
            $quoteRequest->save();
            GetQuote::prepareMailForQuoteRequest($quoteRequest, 'quote_request_status');
            $request->session()->flash('success', $statusMsg);
        }
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $quoteRequest = GetQuote::findOrFail($id);
        $quoteRequest->delete();
        session()->flash('success', __('Quote request information delete successfully') .'!');
        return redirect()->back();
    }
    public function bulkDestroy(Request $request)
    {

        $ids = $request->ids;
        foreach ($ids as $id) {
            $quoteRequest = GetQuote::findOrFail($id);
            $quoteRequest->delete();
        }
        $request->session()->flash('success', __('Quote request information delete successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }
}
