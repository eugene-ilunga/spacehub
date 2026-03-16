<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;

class FlutterwaveController extends Controller
{
  private $public_key, $secret_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('flutterwave')->first();
    $flutterwaveData = json_decode($data->information, true);

    $this->public_key = $flutterwaveData['public_key'];
    $this->secret_key = $flutterwaveData['secret_key'];
  }

  public function index(Request $request, $data, $paymentFor)
  {
    
    $allowedCurrencies = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', __('Invalid currency for flutterwave payment') . '.')->withInput();
    }

      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Flutterwave';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['bookingStatus'] = 'pending';


      $title = __('Booking a Space');
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.flutterwave.notify', ['slug' => $serviceSlug]);

      $customerName = $request['first_name'];
      $customerEmail = $request['email_address'];
      $customerPhone = $request['customer_phone'];

      if($data['grandTotal'] < 10) {
        return redirect()->back()->with('error', __('The minimum amount for payment is 10') . '.')->withInput();
      }


    // send payment to flutterwave for processing
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode([
        'tx_ref' => 'FLW | ' . time(),
        'amount' => $data['grandTotal'],
        'currency' => $currencyInfo->base_currency_text,
        'redirect_url' => $notifyURL,
        'payment_options' => 'card,banktransfer',
        'customer' => [
          'email' => $customerEmail,
          'phone_number' => $customerPhone,
          'name' => $customerName
        ],
        'customizations' => [
          'title' => $title,
          'description' => $title . ' via Flutterwave.'
        ]
      ]),
      CURLOPT_HTTPHEADER => array(
        'authorization: Bearer ' . $this->secret_key,
        'content-type: application/json'
      )
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $responseData = json_decode($response, true);

    //curl end
    // put some data in session before redirect to flutterwave url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);

    if ($responseData['status'] === 'success') {
      return redirect($responseData['data']['link']);
    } else {
      return redirect()->back()->with('error', 'Error: ' . $responseData['message'])->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');

    if ($paymentFor == 'space') {
      $serviceSlug = $arrData['slug'];
    }

    $urlInfo = $request->all();

    if ($urlInfo['status'] == 'successful') {
      $txId = $urlInfo['transaction_id'];

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txId}/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'authorization: Bearer ' . $this->secret_key,
          'content-type: application/json'
        )
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $responseData = json_decode($response, true);


      if ($responseData['status'] === 'success') {

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

          return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
        } 
      } else {
        $request->session()->forget('paymentFor');
        $request->session()->forget('arrData');

        if ($paymentFor == 'space') {
          return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
        }
      }
    } else {
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      }
    }
  }
}
