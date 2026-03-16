<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PhonePeController extends Controller
{
  private $sandboxCheck;
  public function index(Request $request, $data, $paymentFor)
  {
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', __('Invalid currency for phonepe payment') . '.')->withInput();
    }
    $cancel_url = route('service.place_order.cancel', ['slug' => $data['slug']]);
    $notifyURL = route('service.place_order.phonepe.notify', ['slug' => $data['slug']]);
    $amount = $data['grandTotal'] ?? 0;

    $paymentMethod = OnlineGateway::where('keyword', 'phonepe')->first();
    $paymentInfo = json_decode($paymentMethod->information, true);

    $this->sandboxCheck = $paymentInfo['sandbox_status'] ?? 0;
    $clientId = $paymentInfo['merchant_id'];
    $clientSecret = $paymentInfo['salt_key'];

    //* Here i completed 1 step which is generating access token in each request
    $accessToken = $this->getPhonePeAccessToken($clientId, $clientSecret);

    if (!$accessToken) {
      return back()->withError(__('Failed to get PhonePe access token') . '.');
    }

    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Phonepe';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    Session::put('arrData', $data);

    return  $this->initiatePayment($accessToken, $notifyURL, $cancel_url, $amount);
  }

  private function getPhonePeAccessToken($clientId, $clientSecret)
  {
    return Cache::remember('phonepe_access_token', 3500, function () use ($clientId, $clientSecret) {

      $tokenUrl = $this->sandboxCheck
        ? 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token'
        : 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';


      $response = Http::asForm()->post($tokenUrl, [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'client_version' => 1,
        'grant_type' => 'client_credentials'
      ]);

      if ($response->successful()) {
        return $response->json()['access_token'];
      }
      return null;
    });
  }

  public function initiatePayment($accessToken, $successUrl, $cancelUrl, $_amount)
  {
    $baseUrl = $this->sandboxCheck
      ? 'https://api-preprod.phonepe.com/apis/pg-sandbox'
      : 'https://api.phonepe.com/apis/pg';

    $endpoint = '/checkout/v2/pay';

    // Generate a unique merchantOrderId and store it in the session
    $merchantOrderId = uniqid();
    Session::put('merchantOrderId', $merchantOrderId);
    Session::put('cancel_url', $cancelUrl);

    //here we preapare the parameter of the request 
    $payload = [
      'merchantOrderId' => $merchantOrderId,
      'amount' => intval($_amount * 100), //you have to multiply the amount by 100 to convert it to paise
      'paymentFlow' => [
        'type' => 'PG_CHECKOUT',
        'merchantUrls' => [
          'redirectUrl' => $successUrl,
          'cancelUrl' => $cancelUrl
        ]
      ]
    ];

    try {
      //after preparing the parameter we send a request to create a payment link
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'O-Bearer ' . $accessToken,
      ])->post($baseUrl . $endpoint, $payload);

      $responseData = $response->json();

      //after successfully created the payment link of we redirect the user to api responsed redirectUrl
      if ($response->successful() && isset($responseData['redirectUrl'])) {
        return redirect()->away($responseData['redirectUrl']);
      } else {
        // Handle API errors
        Session::forget(['merchantOrderId', 'cancel_url']);
        return back()->with('error', 'Failed to initiate payment: ' . ($responseData['message'] ?? 'Unknown error'));
      }
    } catch (\Exception $e) {

      Session::forget(['merchantOrderId', 'cancel_url']);
      return response()->json([
        'success' => false,
        'code' => 'NETWORK_ERROR',
        'message' => $e->getMessage()
      ], 500);
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $arrData = Session::get('arrData');
   
    $info = OnlineGateway::where('keyword', 'phonepe')->first();

    $serviceSlug = $arrData['slug'];

    $merchantOrderId = $request->input('merchantOrderId') ??
      Session::get('merchantOrderId') ??
      uniqid();

    $verificationResponse = $this->verifyOrderStatus($merchantOrderId);

    if ($verificationResponse['success']) {

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

      Session::forget('paymentFor');
      Session::forget('arrData');
      return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
    } else {
      Session::forget('paymentFor');
      Session::forget('arrData');
      Session::flash('error', __('Your payment has been canceled') . '.');
      return redirect()->route('space.index');
    }
  }
  private function verifyOrderStatus($merchantOrderId)
  {
    $paymentMethod = OnlineGateway::where('keyword', 'phonepe')->first();
    $paymentInfo = json_decode($paymentMethod->information, true);
    $this->sandboxCheck = $paymentInfo['sandbox_status'] ?? 0;

    try {

      $accessToken = $this->getPhonePeAccessToken(
        $paymentInfo['merchant_id'],
        $paymentInfo['salt_key']
      );

      if (!$accessToken) {
        throw new \Exception('Failed to get access token');
      }

      $baseUrl = $this->sandboxCheck
        ? 'https://api-preprod.phonepe.com/apis/pg-sandbox'
        : 'https://api.phonepe.com/apis/pg';

      $endpoint = "/checkout/v2/order/{$merchantOrderId}/status";

      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'O-Bearer ' . $accessToken,
      ])->get($baseUrl . $endpoint);

      if ($response->successful()) {
        $responseData = $response->json();

        return [
          'success' => true,
          'state' => $responseData['state'] ?? null,
          'amount' => $responseData['amount'] ?? null,
          'data' => $responseData
        ];
      } else {
        return [
          'success' => false,
          'error' => $response->json() ?? 'Unknown error'
        ];
      }
    } catch (\Exception $e) {
      return [
        'success' => false,
        'error' => $e->getMessage()
      ];
    }
  }
}
