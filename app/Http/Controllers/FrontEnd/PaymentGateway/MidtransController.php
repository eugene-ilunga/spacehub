<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;

class MidtransController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
   
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text != 'IDR') {
      return redirect()->back()->with('error', __('Invalid currency for midtrans payment') . '.')->withInput();
    }

    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Midtrans';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $spaceSlug = $data['slug'];


    $info = OnlineGateway::where('keyword', 'midtrans')->first();
    $information = json_decode($info->information, true);

    $cancel_url = route('service.place_order.cancel', ['slug' => $spaceSlug]);

    $client_key = $information['server_key'];
    MidtransConfig::$serverKey = $information['server_key'];
    if ($information['midtrans_mode'] == 1) {
      MidtransConfig::$isProduction = false;
    } elseif ($information['midtrans_mode'] == 0) {
      MidtransConfig::$isProduction = true;
    }
    MidtransConfig::$isSanitized = true;
    MidtransConfig::$is3ds = true;
    $token = uniqid();

    // this session $token also is used in the MidtransBankNotifyController
    Session::put('token', $token);

    $params = [
      'transaction_details' => [
        'order_id' => $token,
        'gross_amount' => (int) round($data['grandTotal']), 
      ],
      'customer_details' => [
        'first_name' => $request->first_name,
        'email' => $request->email_address,
        'phone' => $request->customer_phone ? $request->customer_phone : 999999999,
      ],
    ];

    $snapToken = Snap::getSnapToken($params);

    //if generate payment url then put some data into session
    Session::put('arrData', $data);
    Session::put('cancel_url', $cancel_url);
    Session::put('midtrans_payment_type', 'space_booking');

    $is_production = $information['midtrans_mode'] == 1 ? $information['midtrans_mode'] : 0;

    return view('frontend.payment.space-midtrans', compact('snapToken', 'is_production', 'client_key', 'cancel_url'));
  }

  public function cardNotify($order_id)
  {

    // get the information from session
    $arrData = Session::get('arrData');
    $spaceSlug = $arrData['slug'];

    if ($order_id) {

      $orderProcess = new OrderProcessController();

      $selected_service = Space::where('id', $arrData['spaceId'])->select('seller_id')->first();
      if (
        $selected_service->seller_id != 0
      ) {
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
      Session::forget('token');

      return redirect()->route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'online']);
    } else {

      Session::flash('error', __('Your payment has been canceled') . '.');

      Session::forget('paymentFor');
      Session::forget('arrData');
      Session::forget('token');

      return redirect()->route('space.index');
    }
  }

  public function OnlineBackNotify($order_id)
  {
    
    // get the information from session
    $arrData = Session::get('arrData');
    $spaceSlug = $arrData['slug'];

    if ($order_id) {

      $orderProcess = new OrderProcessController();

      // store service order information in database
      $selected_service = Space::where('id', $arrData['spaceId'])->select('seller_id')->first();
      if ($selected_service->seller_id != 0) {
        $arrData['seller_id'] = $selected_service->seller_id;
      } else {
        $arrData['seller_id'] = null;
      }
      $orderInfo = $orderProcess->storeData($arrData);

      // generate an invoice in pdf format
      $invoice = $orderProcess->generateInvoice($orderInfo);

      // then, update the invoice field info in database
      $orderInfo->update(['invoice' => $invoice]);

      // send a mail to the customer with the invoice
      $orderProcess->prepareMail($orderInfo);

      // remove this session datas
      Session::forget(['paymentFor', 'arrData', 'midtrans_payment_type', 'token']);
      return [
        'url' => route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'online'])
      ];
    } else {

      Session::forget(['paymentFor', 'arrData', 'token']);
      Session::flash('error', __('Your payment has been canceled') . '.');
      return [
        'url' => route('space.index')
      ];
    }
  }
}
