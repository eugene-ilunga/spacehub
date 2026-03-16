<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FreshpayController extends Controller
{
  public function index(Request $request, $paymentFor)
  {
    $validator = Validator::make($request->all(), [
      'freshpay_customer_number' => 'required|string|max:50',
      'freshpay_method' => 'required|in:airtel,orange,mpesa'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    if (!$request->session()->has('productCart')) {
      Session::flash('error', __('Something went wrong') . '!');
      return redirect()->route('shop.products');
    }

    $freshpay = OnlineGateway::query()->whereKeyword('freshpay')->first();
    if (empty($freshpay) || $freshpay->status != 1) {
      return redirect()->back()->with('error', __('Freshpay is currently unavailable') . '.')->withInput();
    }

    $freshpayInfo = json_decode($freshpay->information, true);
    $configurationError = $this->validateConfiguration($freshpayInfo);
    if (!is_null($configurationError)) {
      return redirect()->back()->with('error', $configurationError)->withInput();
    }

    $productList = $request->session()->get('productCart');
    $purchaseProcess = new PurchaseProcessController();
    $calculatedData = $purchaseProcess->calculation($request, $productList);
    $currencyInfo = $this->getCurrencyInfo();
    $reference = 'fp_shop_' . uniqid();

    $arrData = [
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
      'paymentMethod' => 'Freshpay',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending',
      'conversation_id' => $reference
    ];

    $payload = [
      'merchant_id' => $freshpayInfo['merchant_id'],
      'merchant_secrete' => $freshpayInfo['merchant_secrete'],
      'amount' => (string) round($calculatedData['grandTotal'], 2),
      'currency' => $currencyInfo->base_currency_text,
      'action' => 'debit',
      'customer_number' => $request->freshpay_customer_number,
      'firstname' => $freshpayInfo['firstname'],
      'lastname' => $freshpayInfo['lastname'],
      'email' => $freshpayInfo['email'],
      'reference' => $reference,
      'method' => $request->freshpay_method,
      'callback_url' => route('shop.purchase_product.freshpay.notify')
    ];

    $paymentResponse = $this->makePaymentRequest($payload);

    if ($paymentResponse['accepted'] !== true) {
      return redirect()->back()->with('error', $paymentResponse['message'])->withInput();
    }

    Cache::put($this->checkoutCacheKey($reference), [
      'arr_data' => $arrData,
      'product_list' => $productList
    ], now()->addHours(6));

    if ($paymentResponse['state'] !== 'success') {
      return redirect()->back()
        ->with('warning', $paymentResponse['message'])
        ->withInput();
    }

    try {
      $this->finalizeShopOrder($arrData, $productList, $reference);

      $request->session()->forget('productCart');
      $request->session()->forget('discount');
      $this->clearFreshpayContext($reference);

      return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
    } catch (\Exception $exception) {
      Log::error('Freshpay shop payment processing failed', [
        'message' => $exception->getMessage(),
        'reference' => $reference
      ]);

      return redirect()->back()->with('error', __('Unable to complete the Freshpay transaction') . '.')->withInput();
    }
  }

  public function notify(Request $request)
  {
    $payload = $request->all();
    $reference = $this->extractReference($payload);

    Log::info('Freshpay shop callback received', [
      'reference' => $reference,
      'payload' => $payload
    ]);

    if (empty($reference)) {
      return response()->json([
        'status' => 'error',
        'message' => 'missing_reference'
      ], 422);
    }

    Cache::put($this->callbackCacheKey($reference), $payload, now()->addHours(6));

    if ($this->isFailedResponse($payload)) {
      $this->clearFreshpayContext($reference);
      return response()->json([
        'status' => 'failed'
      ]);
    }

    if (!$this->isSuccessfulResponse($payload)) {
      return response()->json([
        'status' => 'pending'
      ]);
    }

    $checkoutContext = Cache::get($this->checkoutCacheKey($reference));
    $arrData = data_get($checkoutContext, 'arr_data');
    $productList = data_get($checkoutContext, 'product_list');

    if (!is_array($arrData) || !is_array($productList)) {
      Log::warning('Freshpay shop callback context not found', ['reference' => $reference]);
      return response()->json([
        'status' => 'ok',
        'message' => 'context_not_found'
      ]);
    }

    try {
      $this->finalizeShopOrder($arrData, $productList, $reference);
      $this->clearFreshpayContext($reference);
    } catch (\Exception $exception) {
      Log::error('Freshpay shop callback processing failed', [
        'reference' => $reference,
        'message' => $exception->getMessage()
      ]);

      return response()->json([
        'status' => 'error',
        'message' => 'processing_failed'
      ], 500);
    }

    return response()->json([
      'status' => 'ok'
    ]);
  }

  private function validateConfiguration($freshpayInfo)
  {
    if (!is_array($freshpayInfo)) {
      return __('Freshpay configuration is invalid') . '.';
    }

    $requiredFields = ['merchant_id', 'merchant_secrete', 'firstname', 'lastname', 'email'];

    foreach ($requiredFields as $field) {
      if (empty($freshpayInfo[$field])) {
        return __('Freshpay configuration is incomplete') . '.';
      }
    }

    return null;
  }

  private function makePaymentRequest(array $payload)
  {
    try {
      $response = Http::acceptJson()->asJson()->timeout(30)->post('https://paydrc.gofreshbakery.net/api/v5/', $payload);
    } catch (\Exception $exception) {
      Log::error('Freshpay shop request failed', [
        'message' => $exception->getMessage(),
        'reference' => $payload['reference'] ?? null
      ]);

      return [
        'accepted' => false,
        'state' => 'failed',
        'message' => __('Failed to connect with Freshpay') . '.'
      ];
    }

    $responseBody = (string) $response->body();
    $responseData = $response->json();
    if (!is_array($responseData)) {
      $responseData = [];
    }

    if ($this->isFailedResponse($responseData)) {
      return [
        'accepted' => false,
        'state' => 'failed',
        'message' => $this->extractResponseMessage($responseData, __('Freshpay payment failed') . '.')
      ];
    }

    $isSuccess = $this->isSuccessfulResponse($responseData);
    $isPending = $this->isPendingResponse($responseData);
    $isMessageAccepted = $this->isInitiationMessageAccepted($responseData, $responseBody);

    if (!$response->successful() && !$isSuccess && !$isPending && !$isMessageAccepted) {
      return [
        'accepted' => false,
        'state' => 'failed',
        'message' => $this->extractResponseMessage($responseData, __('Failed to connect with Freshpay') . '.')
      ];
    }

    if ($isSuccess) {
      return [
        'accepted' => true,
        'state' => 'success',
        'message' => $this->extractResponseMessage($responseData, __('Freshpay payment was confirmed') . '.'),
        'data' => $responseData
      ];
    }

    if ($isPending || $isMessageAccepted || $response->successful()) {
      return [
        'accepted' => true,
        'state' => 'pending',
        'message' => __('Freshpay payment is pending confirmation on your phone') . '.',
        'data' => $responseData
      ];
    }

    return [
      'accepted' => false,
      'state' => 'failed',
      'message' => $this->extractResponseMessage($responseData, __('Freshpay payment failed') . '.'),
      'data' => $responseData
    ];
  }

  private function finalizeShopOrder(array $arrData, array $productList, $reference)
  {
    $existingOrder = ProductOrder::query()->where('conversation_id', $reference)->first();
    if (!is_null($existingOrder)) {
      return $existingOrder;
    }

    $purchaseProcess = new PurchaseProcessController();
    $orderInfo = $purchaseProcess->storeData($productList, $arrData);

    foreach ($productList as $key => $item) {
      $product = Product::query()->find($key);
      if (!is_null($product) && $product->product_type == 'physical') {
        $stock = $product->stock - intval($item['quantity']);
        $product->update(['stock' => $stock]);
      }
    }

    $invoice = $purchaseProcess->generateInvoice($orderInfo, $productList);
    $orderInfo->update(['invoice' => $invoice]);

    $adminData['life_time_earning'] = $arrData['grandTotal'];
    $adminData['total_profit'] = $arrData['grandTotal'];

    $orderInfo['transaction_type'] = 7;
    storeTransaction($orderInfo);
    storeEarnings($adminData);

    $purchaseProcess->prepareMail($orderInfo);

    return $orderInfo;
  }

  private function isPendingResponse(array $responseData)
  {
    $statusKeys = [
      'status',
      'state',
      'result',
      'payment_status',
      'transaction_status',
      'response_status',
      'data.status',
      'data.payment_status'
    ];

    foreach ($statusKeys as $statusKey) {
      $statusValue = data_get($responseData, $statusKey);

      if (is_numeric($statusValue) && in_array((string) $statusValue, ['102', '202'])) {
        return true;
      }

      if (is_string($statusValue)) {
        $normalized = strtolower(trim($statusValue));
        if (in_array($normalized, ['pending', 'processing', 'initiated', 'in_progress', 'awaiting_confirmation', 'otp_sent'])) {
          return true;
        }
      }
    }

    return false;
  }

  private function isFailedResponse(array $responseData)
  {
    $statusKeys = [
      'status',
      'state',
      'result',
      'payment_status',
      'transaction_status',
      'response_status',
      'data.status',
      'data.payment_status'
    ];

    foreach ($statusKeys as $statusKey) {
      $statusValue = data_get($responseData, $statusKey);

      if (is_bool($statusValue) && $statusValue === false) {
        return true;
      }

      if (is_string($statusValue)) {
        $normalized = strtolower(trim($statusValue));
        if (in_array($normalized, ['failed', 'failure', 'declined', 'cancelled', 'canceled', 'rejected', 'error'])) {
          return true;
        }
      }
    }

    $codeKeys = ['code', 'status_code', 'statusCode', 'response_code', 'responseCode', 'data.code'];
    foreach ($codeKeys as $codeKey) {
      $codeValue = data_get($responseData, $codeKey);
      if (!is_null($codeValue) && in_array((string) $codeValue, ['400', '401', '402', '403', '404', '409', '422', '429', '500', '501', '502', '503'])) {
        return true;
      }
    }

    return false;
  }

  private function isInitiationMessageAccepted(array $responseData, $responseBody)
  {
    $message = strtolower($this->extractResponseMessage($responseData, ''));
    $body = strtolower((string) $responseBody);
    $haystack = trim($message . ' ' . $body);

    if (empty($haystack)) {
      return false;
    }

    return Str::contains($haystack, [
      'pin',
      'otp',
      'confirm',
      'approve',
      'initiat',
      'pending',
      'processing',
      'in progress',
      'request sent',
      'awaiting'
    ]);
  }

  private function extractReference(array $payload)
  {
    $referenceKeys = [
      'reference',
      'transaction_reference',
      'transaction_ref',
      'transaction_id',
      'conversation_id',
      'data.reference',
      'data.transaction_reference',
      'data.transaction_ref',
      'data.transaction_id',
      'data.conversation_id'
    ];

    foreach ($referenceKeys as $referenceKey) {
      $reference = data_get($payload, $referenceKey);
      if (!empty($reference) && (is_string($reference) || is_numeric($reference))) {
        return trim((string) $reference);
      }
    }

    return null;
  }

  private function checkoutCacheKey($reference)
  {
    return 'freshpay_shop_checkout_' . $reference;
  }

  private function callbackCacheKey($reference)
  {
    return 'freshpay_shop_callback_' . $reference;
  }

  private function clearFreshpayContext($reference)
  {
    if (empty($reference)) {
      return;
    }

    Cache::forget($this->checkoutCacheKey($reference));
    Cache::forget($this->callbackCacheKey($reference));
  }

  private function isSuccessfulResponse(array $responseData)
  {
    $statusKeys = [
      'status',
      'state',
      'result',
      'payment_status',
      'transaction_status',
      'response_status',
      'data.status',
      'data.payment_status'
    ];

    foreach ($statusKeys as $statusKey) {
      $statusValue = data_get($responseData, $statusKey);

      if (is_bool($statusValue) && $statusValue === true) {
        return true;
      }

      if (is_numeric($statusValue) && in_array((string) $statusValue, ['1', '200'])) {
        return true;
      }

      if (is_string($statusValue)) {
        $normalized = strtolower(trim($statusValue));
        if (in_array($normalized, ['success', 'successful', 'completed', 'paid', 'approved', 'ok'])) {
          return true;
        }
      }
    }

    $codeKeys = ['code', 'status_code', 'statusCode', 'response_code', 'responseCode', 'data.code'];
    foreach ($codeKeys as $codeKey) {
      $codeValue = data_get($responseData, $codeKey);
      if (!is_null($codeValue) && in_array((string) $codeValue, ['0', '00', '1', '200', '201'])) {
        return true;
      }
    }

    return false;
  }

  private function extractResponseMessage(array $responseData, $fallbackMessage)
  {
    $messageKeys = [
      'message',
      'error',
      'description',
      'response_description',
      'response_message',
      'data.message',
      'data.description'
    ];

    foreach ($messageKeys as $messageKey) {
      $message = data_get($responseData, $messageKey);

      if (!empty($message) && is_string($message)) {
        return $message;
      }
    }

    return $fallbackMessage;
  }
}
