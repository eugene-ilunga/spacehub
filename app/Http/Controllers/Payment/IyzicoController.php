<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\SellerInfo;
use Illuminate\Support\Facades\Auth;
use Config\Iyzipay;

class IyzicoController extends Controller
{
  public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
  {

    $lang = getVendorLanguage();

    $paymentMethod = OnlineGateway::where('keyword', 'iyzico')->first();
    $paydata = json_decode($paymentMethod->information, true);

    $seller_info = SellerInfo::where([['seller_id', Auth::guard('seller')->user()->id], ['language_id', $lang->id]])->first();

    $fname = Auth::guard('seller')->user()->username;
    $lname = $seller_info->name;
    $email = Auth::guard('seller')->user()->email;
    $phone = Auth::guard('seller')->user()->phone;
    $city = $seller_info->city;
    $country = $seller_info->country;
    $address = $seller_info->address;
    $zip_code = $seller_info->zip_code;
    $id_number = $phone;
    $basket_id = 'B' . uniqid(999, 99999);

    $cancel_url = $_cancel_url;
    $notify_url = $_success_url;

    Session::put("request", $request->all());
    $conversion_id = uniqid(9999, 999999);
    Session::put('conversation_id', $conversion_id);

    $options = Iyzipay::options();
    $options->setApiKey($paydata['api_key']);
    $options->setSecretKey($paydata['secret_key']);
    if ($paydata['iyzico_mode'] == 1) {
      $options->setBaseUrl("https://sandbox-api.iyzipay.com");
    } else {
      $options->setBaseUrl("https://api.iyzipay.com"); // production mode
    }

    # create request class
    $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
    $request->setLocale(\Iyzipay\Model\Locale::EN);
    $request->setConversationId($conversion_id);
    $request->setPrice($_amount);
    $request->setPaidPrice($_amount);
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setBasketId($basket_id);
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl($notify_url);
    $request->setEnabledInstallments(array(2, 3, 6, 9));

    $buyer = new \Iyzipay\Model\Buyer();
    $buyer->setId(uniqid());
    $buyer->setName($fname);
    $buyer->setSurname($lname);
    $buyer->setGsmNumber($phone);
    $buyer->setEmail($email);
    $buyer->setIdentityNumber($id_number);
    $buyer->setLastLoginDate("");
    $buyer->setRegistrationDate("");
    $buyer->setRegistrationAddress($address);
    $buyer->setIp("");
    $buyer->setCity($city);
    $buyer->setCountry($country);
    $buyer->setZipCode($zip_code);
    $request->setBuyer($buyer);

    $shippingAddress = new \Iyzipay\Model\Address();
    $shippingAddress->setContactName($fname);
    $shippingAddress->setCity($city);
    $shippingAddress->setCountry($country);
    $shippingAddress->setAddress($address);
    $shippingAddress->setZipCode($zip_code);
    $request->setShippingAddress($shippingAddress);

    $billingAddress = new \Iyzipay\Model\Address();
    $billingAddress->setContactName($fname);
    $billingAddress->setCity($city);
    $billingAddress->setCountry($country);
    $billingAddress->setAddress($address);
    $billingAddress->setZipCode($zip_code);
    $request->setBillingAddress($billingAddress);

    $q_id = uniqid(999, 99999);
    $basketItems = array();
    $firstBasketItem = new \Iyzipay\Model\BasketItem();
    $firstBasketItem->setId($q_id);
    $firstBasketItem->setName("Purchase Id " . $q_id);
    $firstBasketItem->setCategory1("Purchase or Extend");
    $firstBasketItem->setCategory2("");
    $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
    $firstBasketItem->setPrice($_amount);
    $basketItems[0] = $firstBasketItem;
    $request->setBasketItems($basketItems);

    # make request
    $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);

    $paymentResponse = (array)$payWithIyzicoInitialize;

    foreach ($paymentResponse as $key => $data) {
      $paymentInfo = json_decode($data, true);
      if ($paymentInfo['status'] == 'success') {
        if (!empty($paymentInfo['payWithIyzicoPageUrl'])) {
          Session::put('conversation_id', $conversion_id);
          Session::put('cancel_url', $cancel_url);
          return redirect($paymentInfo['payWithIyzicoPageUrl']);
        } else {
          return redirect($cancel_url)->with('error', __('Payment Canceled') . '.');
        }
      } else {
        return redirect($cancel_url)->with('error', __('Payment Canceled') . '.');
      }
    }
  }

  public function successPayment(Request $request)
  {
    
    $defaultLang = getVendorLanguage();
    $requestData = Session::get('request');
    $bs = Basic::first();
    $cancel_url = Session::get('cancel_url');
    /** Get the payment ID before session clear **/
    $requestData['conversation_id'] = Session::get('conversation_id');
    $requestData['status'] = 0;

    $paymentFor = Session::get('paymentFor');

    $transaction_id = SellerPermissionHelper::uniqidReal(8);
    $transaction_details = json_encode($request['payment_request_id']);

    if (in_array($paymentFor, ['membership', 'extend'])) {
      $package = Package::find($requestData['package_id']);
      $amount = $requestData['price'];
      $requestData['status'] = 0;
      $password = $paymentFor == 'membership'
        ? ($requestData['password'] ?? null)
        : uniqid('qrcode');

      $checkout = new VendorCheckoutController();
       $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

      session()->flash('success', __('Your payment has been completed') . '.');

      Session::forget(['request', 'paymentFor']);
      return redirect()->route('success.page', ['language' => $defaultLang->code]);
    } elseif ($paymentFor == "feature") {

      $amount = $request['price'];
      $requestData['payment_status'] = 'pending';
      $requestData['gateway_type'] = 'online';
      $requestData['status'] = 0;
      $password = uniqid('qrcode');
      $checkout = new VendorCheckoutController();

      // Process feature payment
       $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

      // Set success message and clear session
      session()->flash('success', __('Your payment is successful, feature request is sent') . '!');
      Session::forget('request');
      Session::forget('paymentFor');
      return redirect()->route('success.page', ['language' => $defaultLang->code]);
    }
  }

  public function cancelPayment()
  {
    $defaultLang = getVendorLanguage();
    $requestData = Session::get('request');
    $paymentFor = Session::get('paymentFor');
    $errorMessage = __('Something went wrong') . '.';
    session()->flash('warning', $errorMessage);
    if ($paymentFor == "membership" || $paymentFor == "extend") {
      return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id'], 'language' => $defaultLang->code])->withInput($requestData);
    } elseif ($paymentFor == "feature") {
      return redirect()->route('vendor.space_management.space.index', ['language' => $defaultLang->code])->withInput($requestData);
    } else {
      Session::forget('paymentFor');
      return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id'], 'language' => $defaultLang->code])->withInput($requestData);
    }
  }
}
