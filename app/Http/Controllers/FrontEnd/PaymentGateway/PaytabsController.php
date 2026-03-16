<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PaytabsController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
    

    $currencyInfo = $this->getCurrencyInfo();
    $paytabInfo = paytabInfo();
    if ($currencyInfo->base_currency_text != $paytabInfo['currency']) {
      return redirect()->back()->with('error', __('Invalid currency for paytabs payment') . '.')->withInput();
    }
    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Paytabs';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $spaceSlug = $data['slug'];

    $cancel_url = route('service.place_order.cancel', ['slug' => $spaceSlug]);
    $notifyURL = route('service.place_order.paytabs.notify', ['slug' => $spaceSlug]);
    // put some data in session before redirect
    Session::put('arrData', $data);
    Session::put('cancel_url', $cancel_url);

    $paytabInfo = paytabInfo();
    $description = 'Space booking via paytabs';

    try {
      $response = Http::withHeaders([
        'Authorization' => $paytabInfo['server_key'], // Server Key
        'Content-Type' => 'application/json',
      ])->post($paytabInfo['url'], [
        'profile_id' => $paytabInfo['profile_id'], // Profile ID
        'tran_type' => 'sale',
        'tran_class' => 'ecom',
        'cart_id' => uniqid(),
        'cart_description' => $description,
        'cart_currency' => $paytabInfo['currency'], // set currency by region
        'cart_amount' => $data['grandTotal'],
        'return' => $notifyURL,
      ]);


      $responseData = $response->json();

      return redirect()->to($responseData['redirect_url']);
    } catch (\Exception $e) {
      return redirect($cancel_url);
    }
  }

  public function notify(Request $request)
  {

    // get the information from session
    $arrData = Session::get('arrData');
    $spaceSlug = $arrData['slug'];

    $resp = $request->all();
    if ($resp['respStatus'] == "A" && $resp['respMessage'] == 'Authorised') {

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

      // remove this session datas
      Session::forget('paymentFor');
      Session::forget('arrData');

      return redirect()->route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'online']);
    } else {
      Session::forget('paymentFor');
      Session::forget('arrData');
      Session::flash('error', __('Your payment has been canceled') . '.');
      return redirect()->route('space.index');
    }
  }
}
