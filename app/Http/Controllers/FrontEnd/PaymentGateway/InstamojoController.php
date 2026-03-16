<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Http\Helpers\Instamojo;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Exception;
use Illuminate\Http\Request;

class InstamojoController extends Controller
{
  private $api;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('instamojo')->first();
    $instamojoData = json_decode($data->information, true);

    if ($instamojoData['sandbox_status'] == 1) {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token'], 'https://test.instamojo.com/api/1.1/');
    } else {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token']);
    }
  }

  public function index(Request $request, $data, $paymentFor)
  {
   
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', __('Invalid currency for instamojo payment') . '.')->withInput();
    }


      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Instamojo';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['bookingStatus'] = 'pending';

      $title = __('Booking Space');
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.instamojo.notify', ['slug' => $serviceSlug]);

      $customerName = $request['name'];
      $customerEmail = $request['email_address'];
      $customerPhone = $request['phone_number'];

    try {
      $response = $this->api->paymentRequestCreate(array(
        'purpose' => $title,
        'amount' => round($data['grandTotal'], 2),
        'buyer_name' => $customerName,
        'email' => $customerEmail,
        'send_email' => false,
        'phone' => $customerPhone,
        'send_sms' => false,
        'redirect_url' => $notifyURL
      ));

      // put some data in session before redirect to instamojo url
      $request->session()->put('arrData', $data);
      $request->session()->put('paymentFor', $paymentFor);
      $request->session()->put('paymentId', $response['id']);

      return redirect($response['longurl']);
    } catch (Exception $e) {
      return redirect()->back()->with('error', __('Sorry, transaction failed'). '!')->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');
    $paymentId = $request->session()->get('paymentId');

    if ($paymentFor == 'space') {
      $serviceSlug = $arrData['slug'];
    }

    $urlInfo = $request->all();

    if ($urlInfo['payment_request_id'] == $paymentId) {

      if ($paymentFor == 'space') {
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
        $request->session()->forget('paymentFor');
        $request->session()->forget('arrData');
        $request->session()->forget('paymentId');

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
      } 
    } else {
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } 
    }
  }
}
