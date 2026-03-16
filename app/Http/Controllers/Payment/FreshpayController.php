<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FreshpayController extends Controller
{
  public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
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
      session()->flash('warning', __('Freshpay is currently unavailable') . '.');
      return redirect($_cancel_url);
    }

    $freshpayInfo = json_decode($freshpay->information, true);
    $configurationError = $this->validateConfiguration($freshpayInfo);
    if (!is_null($configurationError)) {
      session()->flash('warning', $configurationError);
      return redirect($_cancel_url);
    }

    $currencyInfo = $this->getCurrencyInfo();
    $reference = 'fp_vendor_' . uniqid();

    $requestData = $request->all();
    $requestData['conversation_id'] = $reference;
    $paymentFor = Session::get('paymentFor');

    $payload = [
      'merchant_id' => $freshpayInfo['merchant_id'],
      'merchant_secrete' => $freshpayInfo['merchant_secrete'],
      'amount' => (string) round($_amount, 2),
      'currency' => $currencyInfo->base_currency_text,
      'action' => 'debit',
      'customer_number' => $request->freshpay_customer_number,
      'firstname' => $freshpayInfo['firstname'],
      'lastname' => $freshpayInfo['lastname'],
      'email' => $freshpayInfo['email'],
      'reference' => $reference,
      'method' => $request->freshpay_method,
      'callback_url' => route('membership.freshpay.notify')
    ];

    try {
      $response = Http::acceptJson()->asJson()->timeout(30)->post('https://paydrc.gofreshbakery.net/api/v5/', $payload);
    } catch (\Exception $exception) {
      Log::error('Freshpay vendor request failed', [
        'message' => $exception->getMessage(),
        'reference' => $reference
      ]);

      session()->flash('warning', __('Failed to connect with Freshpay') . '.');
      return redirect($_cancel_url);
    }

    $responseBody = (string) $response->body();
    $responseData = $response->json();
    if (!is_array($responseData)) {
      $responseData = [];
    }

    $isMessageAccepted = $this->isInitiationMessageAccepted($responseData, $responseBody);
    $isAcceptedInitiation = $this->isAcceptedInitiationResponse($responseData) || $isMessageAccepted;
    $initialState = $this->determineInitialState($responseData, $isMessageAccepted);

    if ($this->isFailedResponse($responseData)) {
      Log::warning('Freshpay vendor payment initiation was rejected', [
        'reference' => $reference,
        'status_code' => $response->status(),
        'response' => $responseData
      ]);

      session()->flash('warning', $this->extractResponseMessage($responseData, __('Freshpay payment failed') . '.'));
      return redirect($_cancel_url);
    }

    if (!$response->successful() && !$isAcceptedInitiation) {
      Log::warning('Freshpay vendor payment initiation failed with non-2xx response', [
        'reference' => $reference,
        'status_code' => $response->status(),
        'response' => $responseData,
        'response_body' => Str::limit($responseBody, 800)
      ]);

      session()->flash('warning', $this->extractResponseMessage($responseData, __('Failed to connect with Freshpay') . '.'));
      return redirect($_cancel_url);
    }

    if (!$isAcceptedInitiation) {
      Log::info('Freshpay vendor payment initiation accepted with fallback state', [
        'reference' => $reference,
        'status_code' => $response->status(),
        'initial_state' => $initialState,
        'response' => $responseData
      ]);
    }

    Session::put('request', $requestData);
    Session::put('cancel_url', $_cancel_url);
    Session::put('freshpay_ref_id', $reference);
    Session::put('freshpay_response', $responseData);
    Session::put('freshpay_response_body', Str::limit($responseBody, 3000));
    Session::put('freshpay_initial_state', $initialState);
    Session::put('freshpay_payment_for', $paymentFor);

    Cache::put($this->checkoutCacheKey($reference), [
      'request_data' => $requestData,
      'cancel_url' => $_cancel_url,
      'payment_for' => $paymentFor,
      'initial_state' => $initialState
    ], now()->addHours(6));

    return redirect($_success_url . '?reference=' . $reference);
  }

  public function notify(Request $request)
  {
    $payload = $request->all();
    $reference = $this->extractReference($payload);

    Log::info('Freshpay vendor callback received', [
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

    return response()->json([
      'status' => 'ok'
    ]);
  }

  public function successPayment(Request $request)
  {
    $defaultLang = getVendorLanguage();
    $requestedReference = $request->input('reference');
    $sessionReference = Session::get('freshpay_ref_id');
    $reference = $sessionReference ?: $requestedReference;

    $checkoutContext = !empty($reference) ? Cache::get($this->checkoutCacheKey($reference)) : null;
    $requestData = Session::get('request', data_get($checkoutContext, 'request_data'));
    $paymentFor = Session::get('paymentFor', data_get($checkoutContext, 'payment_for', Session::get('freshpay_payment_for')));

    if (!empty($reference) && empty($sessionReference)) {
      Session::put('freshpay_ref_id', $reference);
      $sessionReference = $reference;
    }

    if (!empty($requestData) && !Session::has('request')) {
      Session::put('request', $requestData);
    }

    if (!empty($paymentFor) && !Session::has('paymentFor')) {
      Session::put('paymentFor', $paymentFor);
    }

    $bs = Basic::first();
    $cancelUrl = Session::get('cancel_url', data_get($checkoutContext, 'cancel_url', route('vendor.plan.extend.index', ['language' => $defaultLang->code])));
    $initialState = Session::get('freshpay_initial_state', data_get($checkoutContext, 'initial_state', 'unknown'));

    if (!empty($sessionReference) && $request->filled('reference') && $request->reference !== $sessionReference) {
      return redirect($cancelUrl);
    }

    if (empty($requestData) || empty($paymentFor)) {
      session()->flash('warning', __('Freshpay payment context was not found, please try again') . '.');
      return $this->redirectToCheckoutForm($paymentFor, $requestData, $defaultLang);
    }

    $callbackPayload = !empty($sessionReference) ? Cache::get($this->callbackCacheKey($sessionReference)) : null;
    if (is_array($callbackPayload) && $this->isFailedResponse($callbackPayload)) {
      session()->flash('warning', $this->extractResponseMessage($callbackPayload, __('Freshpay payment failed') . '.'));
      return $this->redirectToCheckoutForm($paymentFor, $requestData, $defaultLang);
    }

    $isCallbackSuccess = is_array($callbackPayload) && $this->isSuccessfulResponse($callbackPayload);
    if (!$isCallbackSuccess && $initialState !== 'success') {
      session()->flash('warning', __('Freshpay payment is pending confirmation on your phone') . '.');
      return $this->redirectToCheckoutForm($paymentFor, $requestData, $defaultLang);
    }

    if (in_array($paymentFor, ['membership', 'extend']) && !empty($sessionReference)) {
      $existingMembership = Membership::query()->where('conversation_id', $sessionReference)->first();
      if (!is_null($existingMembership)) {
        session()->flash('success', __('Your payment has been completed') . '.');
        $this->clearFreshpayState($sessionReference);
        return redirect()->route('success.page', ['language' => $defaultLang->code]);
      }
    } elseif ($paymentFor == 'feature' && !empty($sessionReference)) {
      $existingFeature = SpaceFeature::query()->where('conversation_id', $sessionReference)->first();
      if (!is_null($existingFeature)) {
        session()->flash('success', __('Your payment is successful, feature request is sent') . '!');
        $this->clearFreshpayState($sessionReference);
        return redirect()->route('success.page', ['language' => $defaultLang->code]);
      }
    }

    $transactionId = SellerPermissionHelper::uniqidReal(8);
    $transactionPayload = Session::get('freshpay_response', ['reference' => $sessionReference]);
    $responseBody = Session::get('freshpay_response_body');

    if (!empty($responseBody)) {
      $transactionPayload['response_body'] = $responseBody;
    }

    if (is_array($callbackPayload) && !empty($callbackPayload)) {
      $transactionPayload['callback'] = $callbackPayload;
    }

    $transactionDetails = json_encode($transactionPayload);

    if (in_array($paymentFor, ['membership', 'extend'])) {
      $package = Package::find($requestData['package_id']);
      $amount = $requestData['price'];
      $password = $paymentFor == 'membership'
        ? ($requestData['password'] ?? null)
        : uniqid('qrcode');

      $checkout = new VendorCheckoutController();
      $lastMembership = $checkout->store($requestData, $transactionId, $transactionDetails, $amount, $bs, $password);

      $vendor = $this->getVendorDetails($lastMembership->seller_id);

      $activation = Carbon::parse($lastMembership->start_date);
      $expire = Carbon::parse($lastMembership->expire_date);
      $expireDateFormatted = Carbon::parse($expire)->format('Y') == '9999'
        ? 'Lifetime'
        : $expire->toFormattedDateString();

      $currencyFormat = function ($amount) use ($bs) {
        return ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '')
          . $amount
          . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : '');
      };

      $membershipInvoiceData = [
        'name'      => $vendor->seller_name,
        'username'  => $vendor->username,
        'email'     => $vendor->email,
        'phone'     => $vendor->phone,
        'order_id'  => $transactionId,
        'base_currency_text_position'  => $bs->base_currency_text_position,
        'base_currency_text'  => $bs->base_currency_text,
        'base_currency_symbol'  => $bs->base_currency_symbol,
        'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
        'amount'  => $amount,
        'payment_method'  => $requestData['payment_method'],
        'package_title'  => $package->title,
        'start_date'  => $requestData['start_date'],
        'expire_date'  => $requestData['expire_date'],
        'website_title'  => $bs->website_title,
        'logo'  => $bs->logo,
      ];

      $fileName = $this->makeInvoice($membershipInvoiceData);
      $lastMembership->update(['invoice' => $fileName]);

      $mailData = [
        'toMail' => $vendor->email,
        'toName' => $vendor->fname,
        'username' => $vendor->username,
        'package_title' => $package->title,
        'package_price' => $currencyFormat($package->price),
        'total' => $currencyFormat($lastMembership->price),
        'activation_date' => $activation->toFormattedDateString(),
        'expire_date' => $expireDateFormatted,
        'membership_invoice' => $fileName,
        'website_title' => $bs->website_title,
        'templateType' => $paymentFor == 'membership'
          ? 'registration_with_premium_package'
          : 'membership_extend',
        'type' => $paymentFor == 'membership'
          ? 'registrationWithPremiumPackage'
          : 'membershipExtend'
      ];

      (new MegaMailer())->mailFromAdmin($mailData);

      $transaction = [
        'order_number' => $lastMembership->transaction_id,
        'transaction_type' => 5,
        'user_id' => null,
        'seller_id' => $lastMembership->seller_id,
        'payment_status' => 'completed',
        'payment_method' => $lastMembership->payment_method,
        'sub_total' => $lastMembership->price,
        'grand_total' => $lastMembership->price,
        'tax' => null,
        'gateway_type' => 'online',
        'currency_symbol' => $lastMembership->currency_symbol,
        'currency_symbol_position' => $bs->base_currency_symbol_position,
      ];
      storeTransaction($transaction);

      $earnings = [
        'life_time_earning' => $lastMembership->price,
        'total_profit' => $lastMembership->price,
      ];
      storeEarnings($earnings);

      session()->flash('success', __('Your payment has been completed') . '.');
      $this->clearFreshpayState($sessionReference);

      return redirect()->route('success.page', ['language' => $defaultLang->code]);
    } elseif ($paymentFor == 'feature') {
      $amount = $requestData['price'];
      $requestData['payment_status'] = 'completed';
      $requestData['gateway_type'] = 'online';
      $password = uniqid('qrcode');
      $checkout = new VendorCheckoutController();

      $featureInfo = $checkout->store($requestData, $transactionId, $transactionDetails, $amount, $bs, $password);

      $transactionData = [
        'order_number' => $featureInfo->booking_number,
        'transaction_type' => 6,
        'user_id' => null,
        'seller_id' => $featureInfo->seller_id,
        'payment_status' => 'completed',
        'payment_method' => $featureInfo->payment_method,
        'grand_total' => $featureInfo->total,
        'sub_total' => $featureInfo->total,
        'tax' => null,
        'gateway_type' => 'online',
        'currency_symbol' => $featureInfo->currency_symbol,
        'currency_symbol_position' => $featureInfo->currency_symbol_position
      ];
      storeTransaction($transactionData);

      storeEarnings([
        'life_time_earning' => $featureInfo->total,
        'total_profit' => $featureInfo->total
      ]);

      $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
        ['space_id', $featureInfo->space_id],
        ['language_id', $defaultLang->id]
      ])->first();

      $url = $spaceContent ? route('space.details', [
        'slug' => $spaceContent->slug,
        'id' => $featureInfo->space_id
      ]) : null;

      $spaceName = $spaceContent ? $spaceContent->title : null;

      $vendorInfo = $this->getVendorDetails($featureInfo->seller_id);
      $vendorName = $vendorInfo->seller_name;

      $featureInvoiceData = [
        'name'      => $vendorInfo->seller_name,
        'username'  => $vendorInfo->username,
        'email'     => $vendorInfo->email,
        'phone'     => $vendorInfo->phone,
        'order_id'  => $transactionId,
        'base_currency_text_position'  => $bs->base_currency_text_position,
        'base_currency_text'  => $bs->base_currency_text,
        'base_currency_symbol'  => $bs->base_currency_symbol,
        'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
        'amount'  => $featureInfo->total,
        'payment_method'  => $requestData['payment_method'],
        'space_title'  => $spaceName,
        'start_date'  => $featureInfo->start_date,
        'expire_date'  => $featureInfo->end_date,
        'website_title'  => $bs->website_title,
        'logo'  => $bs->logo,
        'day'  => $featureInfo->days,
        'purpose'  => 'feature',
      ];

      $invoice = $this->makeInvoice($featureInvoiceData);
      $featureInfo->update(['invoice' => $invoice]);

      SpaceFeature::sendPaymentStatusEmail(
        $featureInfo,
        $url,
        $spaceName,
        $vendorName,
        $bs->website_title,
        'featured_request_payment_approved',
        $featureInfo->invoice
      );

      session()->flash('success', __('Your payment is successful, feature request is sent') . '!');
      $this->clearFreshpayState($sessionReference);

      return redirect()->route('success.page', ['language' => $defaultLang->code]);
    }

    return redirect($cancelUrl);
  }

  public function cancelPayment()
  {
    $defaultLang = getVendorLanguage();
    $requestData = Session::get('request');
    $paymentFor = Session::get('paymentFor');
    $responseData = Session::get('freshpay_response');

    if (!is_array($responseData)) {
      $responseData = [];
    }

    if (!Session::has('warning') && !Session::has('error')) {
      session()->flash('warning', $this->extractResponseMessage($responseData, __('Something went wrong') . '.'));
    }

    $this->clearFreshpayState(Session::get('freshpay_ref_id'));

    return $this->redirectToCheckoutForm($paymentFor, $requestData, $defaultLang);
  }

  private function getVendorDetails($sellerId)
  {
    return Seller::select('sellers.*', 'seller_infos.name as seller_name')
      ->leftJoin('seller_infos', function ($join) use ($sellerId) {
        $join->on('sellers.id', '=', 'seller_infos.seller_id')
          ->where('sellers.id', $sellerId);
      })
      ->where('sellers.id', '=', $sellerId)
      ->first();
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

  private function determineInitialState(array $responseData, $isMessageAccepted = false)
  {
    if ($this->isSuccessfulResponse($responseData)) {
      return 'success';
    }

    if ($this->isPendingResponse($responseData) || $isMessageAccepted) {
      return 'pending';
    }

    return 'unknown';
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

  private function redirectToCheckoutForm($paymentFor, $requestData, $defaultLang)
  {
    if (!is_array($requestData) || empty($requestData)) {
      return redirect()->route('vendor.plan.extend.index', ['language' => $defaultLang->code]);
    }

    if (($paymentFor == 'membership' || $paymentFor == 'extend') && !empty($requestData['package_id'])) {
      return redirect()->route('vendor.plan.extend.checkout', [
        'package_id' => $requestData['package_id'],
        'language' => $defaultLang->code
      ])->withInput($requestData);
    }

    if ($paymentFor == 'feature') {
      return redirect()->route('vendor.space_management.space.index', ['language' => $defaultLang->code])->withInput($requestData);
    }

    return redirect()->route('vendor.plan.extend.index', ['language' => $defaultLang->code]);
  }

  private function isAcceptedInitiationResponse(array $responseData)
  {
    return $this->isSuccessfulResponse($responseData) || $this->isPendingResponse($responseData);
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
    return 'freshpay_vendor_checkout_' . $reference;
  }

  private function callbackCacheKey($reference)
  {
    return 'freshpay_vendor_callback_' . $reference;
  }

  private function clearFreshpayState($reference = null)
  {
    Session::forget([
      'request',
      'paymentFor',
      'freshpay_ref_id',
      'freshpay_response',
      'freshpay_response_body',
      'freshpay_initial_state',
      'freshpay_payment_for',
      'cancel_url'
    ]);

    if (!empty($reference)) {
      Cache::forget($this->checkoutCacheKey($reference));
      Cache::forget($this->callbackCacheKey($reference));
    }
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
