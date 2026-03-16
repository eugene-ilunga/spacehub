<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class YocoController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

   
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'ZAR') {
      return redirect()->back()->with('error', __('Invalid currency for yoco payment') . '.')->withInput();
    }
    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Yoco';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['bookingStatus'] = 'pending';
    }

      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.yoco.notify', ['slug' => $serviceSlug]);
      $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);


    $info = OnlineGateway::where('keyword', 'yoco')->first();
    $information = json_decode($info->information, true);

    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $information['secret_key'],
    ])->post('https://payments.yoco.com/api/checkouts', [
      'amount' => $data['grandTotal'] * 100,
      'currency' => 'ZAR',
      'successUrl' => $notifyURL
    ]);


    $responseData = $response->json();

    if (array_key_exists('redirectUrl', $responseData)) {
      // put some data in session before redirect
      Session::put('arrData', $data);
      Session::put('cancel_url', $cancel_url);
      Session::put('yoco_id', $responseData['id']);
      Session::put('s_key', $information['secret_key']);
      return redirect($responseData["redirectUrl"]);
    } else {
      return redirect($cancel_url);
    }
  }

  public function notify(Request $request)
  {

    // get the information from session
    $arrData = Session::get('arrData');
    if ($arrData !== null && isset($arrData['slug'])) {
      $serviceSlug = $arrData['slug'];
    } else {

      $serviceSlug = null;
    }


    $id = Session::get('yoco_id');
    $s_key = Session::get('s_key');

    $info = OnlineGateway::where('keyword', 'yoco')->first();

    $information = json_decode($info->information, true);

    if ( !is_null($id) && $information['secret_key'] == $s_key) {

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
