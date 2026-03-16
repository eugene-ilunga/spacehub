<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;;
use App\Models\Space;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
  private $key, $secret, $api;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('razorpay')->first();
    $razorpayData = json_decode($data->information, true);

    $this->key = $razorpayData['key'];
    $this->secret = $razorpayData['secret'];

    $this->api = new Api($this->key, $this->secret);
  }

  public function index(Request $request, $data, $paymentFor)
  {
    
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', __('Invalid currency for razorpay payment') .'.')->withInput();
    }

      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Razorpay';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['bookingStatus'] = 'pending';


      $title = 'Space Booking';
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.razorpay.notify', ['slug' => $serviceSlug]);

      $customerName = $request['first_name'];
      $customerEmail = $request['email_address'];
      $customerPhone = $request['customer_phone'];


    // create order data
    $orderData = [
      'receipt'         => $title,
      'amount'          => intval($data['grandTotal'] * 100),
      'currency'        => 'INR',
      'payment_capture' => 1 // auto capture
    ];

    $razorpayOrder = $this->api->order->create($orderData);

    $websiteTitle = Basic::query()->pluck('website_title')->first();

    // create checkout data
    $checkoutData = [
      'key'               => $this->key,
      'amount'            => $orderData['amount'],
      'name'              => $websiteTitle,
      'description'       => $title . ' via Razorpay.',
      'prefill'           => [
        'name'              => $customerName,
        'email'             => $customerEmail,
        'contact'           => $customerPhone
      ],
      'order_id'          => $razorpayOrder->id
    ];

    $jsonData = json_encode($checkoutData);

    // put some data in session before redirect to razorpay url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('razorpayOrderId', $razorpayOrder->id);

    return view('frontend.payment.razorpay', compact('jsonData', 'notifyURL'));
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');
    $razorpayOrderId = $request->session()->get('razorpayOrderId');

    if ($paymentFor == 'space') {
      $serviceSlug = $arrData['slug'];
    }

    $urlInfo = $request->all();
 
    // assume that the transaction was successful
    $success = true;

    /**
     * either razorpay_order_id or razorpay_subscription_id must be present.
     * the keys of $attributes array must be follow razorpay convention.
     */
    try {
      $attributes = [
        'razorpay_order_id' => $razorpayOrderId,
        'razorpay_payment_id' => $urlInfo['razorpayPaymentId'],
        'razorpay_signature' => $urlInfo['razorpaySignature']
      ];
    
      $this->api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
      $success = false;
    }

    if ($success === true) {

        $orderProcess = new OrderProcessController();

        // store space booking information in database

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
        $request->session()->forget('razorpayOrderId');

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
    } else {

      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('razorpayOrderId');

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } 
    }
  }
}
