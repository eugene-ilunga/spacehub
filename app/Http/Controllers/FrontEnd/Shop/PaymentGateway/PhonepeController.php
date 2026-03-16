<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class PhonepeController extends Controller
{
  private $sandboxCheck;
  public function index(Request $request, $paymentFor)
  {

    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~ Purchase Info ~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
    $currencyInfo = $this->getCurrencyInfo();
    if ($currencyInfo->base_currency_text != 'INR') {
      return back()->with(['alert-type' => 'error', 'message' => __('Invalid Currency') . '.']);
    }
    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    } else {
      Session::flash('error', __('Something went wrong') . '!');

      return redirect()->route('shop.products');
    }
    $purchaseProcess = new PurchaseProcessController();
    // do calculation
    $calculatedData = $purchaseProcess->calculation($request, $productList);

    $arrData = array(
      'billing_name' => $request['billing_name'],
      'billing_email' => $request['billing_email'],
      'billing_phone' => $request['billing_phone'],
      'billing_city' => $request['billing_city'],
      'billing_state' => $request['billing_state'],
      'billing_country' => $request['billing_country'],
      'billing_address' => $request['billing_address'],

      'shipping_name' => $request->checkbox == 1 ? $request['shipping_name'] : $request['billing_name'],

      'shipping_email' => $request->checkbox == 1 ? $request['shipping_email'] : $request['billing_email'],

      'shipping_phone' => $request->checkbox == 1 ? $request['shipping_phone'] : $request['billing_phone'],

      'shipping_city' => $request->checkbox == 1 ? $request['shipping_city'] : $request['billing_city'],

      'shipping_state' => $request->checkbox == 1 ? $request['shipping_state'] : $request['billing_state'],

      'shipping_country' => $request->checkbox == 1 ? $request['shipping_country'] : $request['billing_country'],

      'shipping_address' => $request->checkbox == 1 ? $request['shipping_address'] : $request['billing_address'],

      'total' => $calculatedData['total'],
      'discount' => $calculatedData['discount'],
      'productShippingChargeId' => $request->exists('shipping_method') ? $request['shipping_method'] : null,
      'shippingCharge' => $calculatedData['shippingCharge'],
      'tax' => $calculatedData['tax'],
      'grandTotal' => $calculatedData['grandTotal'],
      'currencyText' => $currencyInfo->base_currency_text,
      'currencyTextPosition' => $currencyInfo->base_currency_text_position,
      'currencySymbol' => $currencyInfo->base_currency_symbol,
      'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
      'paymentMethod' => 'Phone Pe',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );
    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~ Purchase End ~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~ Payment Gateway Info ~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    $info = OnlineGateway::where('keyword', 'phonepe')->first();
    $information = json_decode($info->information, true);

    $this->sandboxCheck = $information['sandbox_status'] ?? 0;
    $clientId = $information['merchant_id'];
    $clientSecret = $information['salt_key'];

    $cancel_url = route('shop.purchase_product.cancel');
    $notifyURL = route('shop.purchase_product.phonepe.notify');
    $amount = $calculatedData['grandTotal'] ?? 0.00;

    //* Here i completed 1 step which is generating access token in each request
    $accessToken = $this->getPhonePeAccessToken($clientId, $clientSecret);

    if (!$accessToken) {
      return back()->withError(__('Failed to get PhonePe access token') . '.');
    }

    Session::put('arrData', $arrData);

    return  $this->initiatePayment($accessToken, $notifyURL, $cancel_url, $amount);

    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~ Payment Gateway Info End ~~~~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
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
    $info = OnlineGateway::where('keyword', 'phonepe')->first();
    $information = json_decode($info->information, true);

    $merchantOrderId = $request->input('merchantOrderId') ??
      Session::get('merchantOrderId') ??
      uniqid();

    $verificationResponse = $this->verifyOrderStatus($merchantOrderId);

    if ($verificationResponse['success']) {
      // get the information from session
      $productList = $request->session()->get('productCart');

      $arrData = $request->session()->get('arrData');
      $purchaseProcess = new PurchaseProcessController();

      // store product order information in database
      $orderInfo = $purchaseProcess->storeData($productList, $arrData);

      // then subtract each product quantity from respective product stock
      foreach ($productList as $key => $item) {
        $product = Product::query()->find($key);

        if ($product->product_type == 'physical') {
          $stock = $product->stock - intval($item['quantity']);

          $product->update(['stock' => $stock]);
        }
      }

      // generate an invoice in pdf format
      $invoice = $purchaseProcess->generateInvoice($orderInfo, $productList);

      // then, update the invoice field info in database
      $orderInfo->update(['invoice' => $invoice]);

      //add balance to admin revenue
      $adminData['life_time_earning'] =  $arrData['grandTotal'];
      if ($orderInfo['seller_id'] != null) {
        $adminData['total_profit'] =  $arrData['grandTotal'];
      } else {
        $adminData['total_profit'] =  $arrData['grandTotal'];
      }
      

      //storeTransaction
      $orderInfo['transaction_type'] = 7;
      storeTransaction($orderInfo);
      storeEarnings($adminData);

      // send a mail to the customer with the invoice
      $purchaseProcess->prepareMail($orderInfo);

      // remove all session data
      $request->session()->forget('productCart');
      $request->session()->forget('discount');

      return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
    } else {
      return redirect()->route('shop.checkout')->with(['alert-type' => 'error', 'message' => __('Payment Canceled') . '.']);
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
