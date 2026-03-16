<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use App\Models\SpaceBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FreshpayController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
    $validator = Validator::make($request->all(), [
      'freshpay_customer_number' => 'required|string|max:50',
      'freshpay_method' => 'required|in:airtel,orange,mpesa'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
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

    $currencyInfo = $this->getCurrencyInfo();
    $reference = 'fp_space_' . uniqid();

    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Freshpay';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';
    $data['conversation_id'] = $reference;

    $payload = [
      'merchant_id' => $freshpayInfo['merchant_id'],
      'merchant_secrete' => $freshpayInfo['merchant_secrete'],
      'amount' => (string) round($data['grandTotal'], 2),
      'currency' => $currencyInfo->base_currency_text,
      'action' => 'debit',
      'customer_number' => $request->freshpay_customer_number,
      'firstname' => $freshpayInfo['firstname'],
      'lastname' => $freshpayInfo['lastname'],
      'email' => $freshpayInfo['email'],
      'reference' => $reference,
      'method' => $request->freshpay_method,
      'callback_url' => route('service.place_order.freshpay.notify', ['slug' => $data['slug']])
    ];

    $paymentResponse = $this->makePaymentRequest($payload);

    if ($paymentResponse['accepted'] !== true) {
      return redirect()->back()->with('error', $paymentResponse['message'])->withInput();
    }

    Cache::put($this->checkoutCacheKey($reference), [
      'data' => $data
    ], now()->addHours(6));

    if ($paymentResponse['state'] !== 'success') {
      return redirect()->back()
        ->with('warning', $paymentResponse['message'])
        ->withInput();
    }

    try {
      $this->finalizeBookingOrder($data, $reference);

      Session::forget('paymentFor');
      Session::forget('arrData');
      $this->clearFreshpayContext($reference);

      return redirect()->route('service.place_order.complete', ['slug' => $data['slug'], 'via' => 'online']);
    } catch (\Exception $exception) {
      Log::error('Freshpay booking payment processing failed', [
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

    Log::info('Freshpay booking callback received', [
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
    $data = data_get($checkoutContext, 'data');

    if (!is_array($data)) {
      Log::warning('Freshpay booking callback context not found', ['reference' => $reference]);
      return response()->json([
        'status' => 'ok',
        'message' => 'context_not_found'
      ]);
    }

    try {
      $this->finalizeBookingOrder($data, $reference);
      $this->clearFreshpayContext($reference);
    } catch (\Exception $exception) {
      Log::error('Freshpay booking callback processing failed', [
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
      Log::error('Freshpay booking request failed', [
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

  private function finalizeBookingOrder(array $data, $reference)
  {
    $existingBooking = SpaceBooking::query()->where('conversation_id', $reference)->first();
    if (!is_null($existingBooking)) {
      return $existingBooking;
    }

    $orderProcess = new OrderProcessController();

    $selectedService = Space::where('id', $data['spaceId'])->select('seller_id')->first();
    if (!is_null($selectedService) && $selectedService->seller_id != 0) {
      $data['seller_id'] = $selectedService->seller_id;
    } else {
      $data['seller_id'] = null;
    }

    $bookingInfo = $orderProcess->storeData($data);
    $invoice = $orderProcess->generateInvoice($bookingInfo);
    $bookingInfo->update(['invoice' => $invoice]);

    $vendorData['sub_total'] = $bookingInfo->sub_total ?? 0;
    $vendorData['seller_id'] = $bookingInfo->seller_id ?? null;
    storeAmountToSeller($vendorData);

    $adminData['life_time_earning'] = $bookingInfo->grand_total ?? 0;
    if ($bookingInfo['seller_id'] != null) {
      $adminData['total_profit'] = $bookingInfo->tax ?? 0;
    } else {
      $adminData['total_profit'] = $bookingInfo->grand_total ?? 0;
    }

    $bookingInfo['transaction_type'] = 1;
    storeTransaction($bookingInfo);
    storeEarnings($adminData);

    $orderProcess->prepareMail($bookingInfo);
    $orderProcess->prepareMailForVendor($bookingInfo);

    return $bookingInfo;
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
    return 'freshpay_space_checkout_' . $reference;
  }

  private function callbackCacheKey($reference)
  {
    return 'freshpay_space_callback_' . $reference;
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
