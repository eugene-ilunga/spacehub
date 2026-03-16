<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Config\Iyzipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IyzicoController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
  

    $rules = [
      'first_name_for_iyzico' => 'required',
      'last_name_for_iyzico' => 'required',
      'identity_number_for_iyzico' => 'required',
      'email_address_for_iyzico' => 'required|email',
      'phone_number_for_iyzico' => 'required|regex:/^\d{10,12}$/',
      'zip_code_for_iyzico' => 'required',
      'address_for_iyzico' => 'required',
      'country_for_iyzico' => 'required',
      'city_for_iyzico' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      // Set a custom session variable
      session()->flash('iyzico_validation_errors', true);
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $fname = $request->first_name_for_iyzico;
    $lname = $request->last_name_for_iyzico;
    $email = $request->email_address_for_iyzico;
    $city = $request->city_for_iyzico;
    $country = $request->country_for_iyzico;
    $address = $request->address_for_iyzico;
    $zip_code = $request->zip_code_for_iyzico;

    $spaceSlug = $data['slug'];

    $cancel_url = route('service.place_order.cancel', ['slug' => $spaceSlug]);
    $notifyURL = route('service.place_order.iyzico.notify', ['slug' => $spaceSlug]);

    $currencyInfo = $this->getCurrencyInfo();

    if ($currencyInfo->base_currency_text != 'TRY') {
      return redirect()->back()->with('error', __('Invalid currency for iyzico payment') . '.')->withInput();
    }

    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Iyzico';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'pending';
    $data['bookingStatus'] = 'pending';


    $conversion_id = uniqid(9999, 999999);
    $data['conversation_id'] = $conversion_id;
    $basket_id = 'B' . uniqid(999, 99999);
    $phone_number = $request->phone_number_for_iyzico;
    $identity_number = $request->email_address_for_iyzico;

    Session::put('arrData', $data);

    $paymentMethod = OnlineGateway::where('keyword', 'iyzico')->first();
    $paydata = json_decode($paymentMethod->information, true);

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
    $request->setPrice($data['grandTotal']);
    $request->setPaidPrice($data['grandTotal']);
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setBasketId($basket_id);
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl($notifyURL);
    $request->setEnabledInstallments(array(2, 3, 6, 9));

    $buyer = new \Iyzipay\Model\Buyer();
    $buyer->setId(uniqid());
    $buyer->setName($fname);
    $buyer->setSurname($lname);
    $buyer->setGsmNumber($phone_number);
    $buyer->setEmail($email);
    $buyer->setIdentityNumber($identity_number);
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
    $firstBasketItem->setPrice($data['grandTotal']);
    $basketItems[0] = $firstBasketItem;
    $request->setBasketItems($basketItems);

    # make request
    $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);

    $paymentResponse = (array)$payWithIyzicoInitialize;
    foreach ($paymentResponse as $key => $data) {
      $paymentInfo = json_decode($data, true);
      if ($paymentInfo['status'] == 'success') {
        if (!empty($paymentInfo['payWithIyzicoPageUrl'])) {
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

  public function notify(Request $request)
  {

    // get the information from session
    $arrData = Session::get('arrData');

    $spaceSlug = $arrData['slug'];

    // remove this session datas
    Session::forget('paymentFor');
    Session::forget('arrData');

    $orderProcess = new OrderProcessController();

    $selected_service = Space::where('id', $arrData['spaceId'])->select('seller_id')->first();
    if ($selected_service->seller_id != 0) {
      $arrData['seller_id'] = $selected_service->seller_id;
    } else {
      $arrData['seller_id'] = null;
    }
    $bookingInfo = $orderProcess->storeData($arrData);

    return redirect()->route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'online']);
  }
}
