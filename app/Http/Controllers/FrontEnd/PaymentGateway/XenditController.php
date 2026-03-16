<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class XenditController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    $available_currency = array('IDR', 'PHP', 'USD', 'SGD', 'MYR');
    if (!in_array($currencyInfo->base_currency_text, $available_currency)) {
      return redirect()->back()->with('error', __('Invalid currency for xendit payment') . '.')->withInput();
    }

    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Xendit';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $serviceSlug = $data['slug'];

    $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
    $notifyURL = route('service.place_order.xendit.notify', ['slug' => $serviceSlug]);

    $external_id = Str::random(10);

    $secret_key = 'Basic ' . config('xendit.key_auth');


    $data_request = Http::withHeaders([
      'Authorization' => $secret_key
    ])->post('https://api.xendit.co/v2/invoices', [
      'external_id' => $external_id,
      'amount' => (int) round($data['grandTotal']),
      'currency' => $currencyInfo->base_currency_text,
      'success_redirect_url' => $notifyURL
    ]);

    $response = $data_request->object();

    $response = json_decode(json_encode($response), true);

    if (!empty($response['success_redirect_url'])) {
      Session::put('arrData', $data);
      Session::put('cancel_url', $cancel_url);
      Session::put('xendit_id', $response['id']);
      Session::put('secret_key', config('xendit.key_auth'));
      return redirect($response['invoice_url']);
    } else {
      return redirect($cancel_url)->with('error', __('Payment Canceled') . '.');
    }

  }


  public function notify(Request $request)
  {
    // get the information from session
    $arrData = Session::get('arrData');
    $serviceSlug = $arrData['slug'];

    $xendit_id = Session::get('xendit_id');
    $secret_key = Session::get('secret_key');
    if (!is_null($xendit_id) && $secret_key == config('xendit.key_auth')) {

      $orderProcess = new OrderProcessController();

      $selected_service = Space::where('id', $arrData['spaceId'])->select('seller_id')->first();
      if ($selected_service->seller_id != 0) {
        $arrData['seller_id'] = $selected_service->seller_id;
      } else {
        $arrData['seller_id'] = null;
      }
      $bookingInfo = $orderProcess->storeData($arrData);

      // generate an invoice in pdf format
      $invoice = $orderProcess->generateInvoice($bookingInfo);

      // then, update the invoice field info in database
      $bookingInfo->update(['invoice' => $invoice]);


      $vendorData['sub_total'] = $bookingInfo->sub_total ?? 0;
      $vendorData['seller_id'] = $bookingInfo->seller_id ?? null;
      //add balance to vendor
      storeAmountToSeller($vendorData);

      //add balance to admin revenue
      $adminData['life_time_earning'] =  $bookingInfo->grand_total ?? 0;
      if ($bookingInfo['seller_id'] != null) {
        $adminData['total_profit'] =  $bookingInfo->tax ?? 0;
      } else {
        $adminData['total_profit'] = $bookingInfo->grand_total ?? 0;
      }
   

      //store Transaction data
      $bookingInfo['transaction_type'] = 1;
      storeTransaction($bookingInfo);
      
      storeEarnings($adminData);

      // send a mail to the customer with the invoice
      $orderProcess->prepareMail($bookingInfo);

      // send a mail to the vendor

        $orderProcess->prepareMailForVendor($bookingInfo);

      
      return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
    } else {
      Session::forget('paymentFor');
      Session::forget('arrData');
      Session::flash('error', __('Your payment has been canceled') . '.');
      return redirect()->route('space.index');
    }
  }
}
