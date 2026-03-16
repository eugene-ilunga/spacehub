<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ToyyibpayController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
 

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'RM' or not
    if ($currencyInfo->base_currency_text !== 'RM') {
      return redirect()->back()->with('error', __('Invalid currency for toyyibpay payment') . '.')->withInput();
    }
    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Toyyibpay';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $serviceSlug = $data['slug'];
    $info = OnlineGateway::where('keyword', 'toyyibpay')->first();
    $information = json_decode($info->information, true);
   

    $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
    $notifyURL = route('service.place_order.toyyibpay.notify', ['slug' => $serviceSlug]);


    $info = OnlineGateway::where('keyword', 'toyyibpay')->first();
    $information = json_decode($info->information, true);
    $ref = uniqid();

    session()->put('toyyibpay_ref_id', $ref);

    $bill_title = __('Buy Plan');
    $bill_description = __('Buy Plan via Toyyibpay');

    $some_data = array(
      'userSecretKey' => $information['secret_key'],
      'categoryCode' => $information['category_code'],
      'billName' => $bill_title,
      'billDescription' => $bill_description,
      'billPriceSetting' => 1,
      'billPayorInfo' => 1,
      'billAmount' => $data['grandTotal'] * 100,
      'billReturnUrl' => $notifyURL,
      'billExternalReferenceNo' => $ref,
      'billTo' => $request->first_name,
      'billEmail' => $request->email_address,
      'billPhone' => $request->phone ? $request->phone : 99999999999,
    );
   


    if ($information['sandbox_status'] == 1) {
      $host = 'https://dev.toyyibpay.com/'; // for development environment
    } else {
      $host = 'https://toyyibpay.com/'; // for production environment
    }

    $curl = curl_init();


    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_URL, $host . 'index.php/api/createBill');  // sandbox will be dev.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

    $result = curl_exec($curl);

    $info = curl_getinfo($curl);
    curl_close($curl);
    $response = json_decode($result, true);
   

    if (!empty($response[0])) {
      // put some data in session before redirect to paytm url
      Session::put('arrData', $data);
      Session::put("cancel_url", $cancel_url);
      return redirect($host . $response[0]["BillCode"]);
    } else {
      return redirect($cancel_url);
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $arrData = Session::get('arrData');
    
    $serviceSlug = $arrData['slug'];

    $ref = session()->get('toyyibpay_ref_id');
   
    if ($request['status_id'] == 1 && $request['order_id'] == $ref) {
      // remove this session datas
      Session::forget('paymentFor');
      Session::forget('arrData');

      $orderProcess = new OrderProcessController();

      // store service order information in database
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
