<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
  private $token, $sandbox_status;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('mercadopago')->first();
    $mercadopagoData = json_decode($data->information, true);

    $this->token = $mercadopagoData['token'];
    $this->sandbox_status = $mercadopagoData['sandbox_status'];
  }

  public function index(Request $request, $data, $paymentFor)
  {
 
    $allowedCurrencies = array('ARS', 'BOB', 'BRL', 'CLF', 'CLP', 'COP', 'CRC', 'CUC', 'CUP', 'DOP', 'EUR', 'GTQ', 'HNL', 'MXN', 'NIO', 'PAB', 'PEN', 'PYG', 'USD', 'UYU', 'VEF', 'VES');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', 'Invalid currency for mercadopago payment.');
    }

      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'MercadoPago';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';


      $title = 'Space Booking';
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.mercadopago.notify', ['slug' => $serviceSlug]);
      $cancelURL = route('service.place_order.cancel', ['slug' => $serviceSlug]);

      $customerEmail = $request['email_address'];

    $curl = curl_init();

    $preferenceData = [
      'items' => [
        [
          'id' => uniqid(),
          'title' => $title,
          'description' => $title . ' via MercadoPago',
          'quantity' => 1,
          'currency' => $currencyInfo->base_currency_text,
          'unit_price' => $data['grandTotal']
        ]
      ],
      'payer' => [
        'email' => $customerEmail
      ],
      'back_urls' => [
        'success' => $notifyURL,
        'pending' => '',
        'failure' => $cancelURL
      ],
      'notification_url' => $notifyURL,
      'auto_return' => 'approved'
    ];

    $httpHeader = ['Content-Type: application/json'];

    $url = 'https://api.mercadopago.com/checkout/preferences?access_token=' . $this->token;

    $curlOPT = [
      CURLOPT_URL             => $url,
      CURLOPT_CUSTOMREQUEST   => 'POST',
      CURLOPT_POSTFIELDS      => json_encode($preferenceData, true),
      CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_TIMEOUT         => 30,
      CURLOPT_HTTPHEADER      => $httpHeader
    ];

    curl_setopt_array($curl, $curlOPT);

    $response = curl_exec($curl);
    $responseInfo = json_decode($response, true);
    curl_close($curl);

    if (isset($responseInfo['init_point']) && $this->sandbox_status == 0) {
      // put some data in session before redirect to mercadopago url
      $request->session()->put('arrData', $data);
      $request->session()->put('paymentFor', $paymentFor);

      return redirect($responseInfo['init_point']);
    } elseif (isset($responseInfo['sandbox_init_point']) && $this->sandbox_status == 1) {
      // put some data in session before redirect to mercadopago url
      $request->session()->put('arrData', $data);
      $request->session()->put('paymentFor', $paymentFor);

      return redirect($responseInfo['sandbox_init_point']);
    } else {
      // Handle the case where the response does not contain the expected keys
      return redirect()->back()->with('error', __('Sorry') . ', ' . __('there was an issue with the payment gateway'). '. ' . __('Please try again later or contact support') . '.');
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

    $paymentURL = 'https://api.mercadopago.com/v1/payments/' . $request['data']['id'] . '?access_token=' . $this->token;

    $paymentData = $this->curlCalls($paymentURL);
    $paymentInfo = json_decode($paymentData, true);

    if ($paymentInfo['status'] == 'approved') {


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
  }

  public function curlCalls($url)
  {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $curlData = curl_exec($curl);

    curl_close($curl);

    return $curlData;
  }
}
