<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Config\Iyzipay;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class IyzipayController extends Controller
{
  public function index(Request $request, $paymentFor)
  {


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
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text != 'TRY') {
      return redirect()->back()->with('error', __('Invalid currency for toyyibpay payment') . '.')->withInput();
    }


    $arrData = array(
      'billing_name' => $request['billing_name'],
      'billing_email' => $request['billing_email'],
      'billing_phone' => $request['billing_phone'],
      'billing_city' => $request['billing_city'],
      'billing_state' => $request['billing_state'],
      'billing_country' => $request['billing_country'],
      'billing_address' => $request['billing_address'],
      'billing_zip_code' => $request['billing_zip_code'],

      'shipping_name' => $request->checkbox == 1 ? $request['shipping_name'] : $request['billing_name'],

      'shipping_email' => $request->checkbox == 1 ? $request['shipping_email'] : $request['billing_email'],

      'shipping_phone' => $request->checkbox == 1 ? $request['shipping_phone'] : $request['billing_phone'],

      'shipping_city' => $request->checkbox == 1 ? $request['shipping_city'] : $request['billing_city'],

      'shipping_state' => $request->checkbox == 1 ? $request['shipping_state'] : $request['billing_state'],

      'shipping_zip_code' => $request->checkbox == 1 ? $request['shipping_zip_code'] : $request['billing_zip_code'],
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
      'paymentMethod' => 'Iyzico',
      'gatewayType' => 'online',
      'paymentStatus' => 'pending',
      'orderStatus' => 'pending'
    );

    $notifyURL = route('shop.purchase_product.iyzico.notify');


    /*````````````````````````````````````````````
        ````````````Payment gateway info start`````````
        ---------------------------------------------*/

    $paymentMethod = OnlineGateway::where('keyword', 'iyzico')->first();
    $paydata = json_decode($paymentMethod->information, true);
    $conversion_id = uniqid(9999, 999999);

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
    $request->setPrice($calculatedData['total']);
    $request->setPaidPrice($calculatedData['grandTotal']);
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setBasketId("B67832");
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl($notifyURL);
    $request->setEnabledInstallments(array(2, 3, 6, 9));

    $buyer = new \Iyzipay\Model\Buyer();
    $buyer->setId(uniqid());
    $buyer->setName($arrData['billing_name']);
    $buyer->setSurname($arrData['billing_name']);
    $buyer->setGsmNumber("+905350000000");
    $buyer->setEmail($arrData['billing_email']);
    $buyer->setIdentityNumber("74300864791");
    $buyer->setLastLoginDate("");
    $buyer->setRegistrationDate("");
    $buyer->setRegistrationAddress($arrData['billing_address']);
    $buyer->setIp("");
    $buyer->setCity($arrData['billing_city']);
    $buyer->setCountry($arrData['billing_country']);
    $buyer->setZipCode($arrData['billing_zip_code']);
    $request->setBuyer($buyer);

    $shippingAddress = new \Iyzipay\Model\Address();
    $shippingAddress->setContactName($arrData['shipping_name']);
    $shippingAddress->setCity($arrData['shipping_city']);
    $shippingAddress->setCountry($arrData['shipping_country']);
    $shippingAddress->setAddress($arrData['shipping_address']);
    $shippingAddress->setZipCode($arrData['shipping_zip_code']);
    $request->setShippingAddress($shippingAddress);

    $billingAddress = new \Iyzipay\Model\Address();
    $billingAddress->setContactName($arrData['billing_name']);
    $billingAddress->setCity($arrData['billing_city']);
    $billingAddress->setCountry($arrData['billing_country']);
    $billingAddress->setAddress($arrData['billing_address']);
    $billingAddress->setZipCode($arrData['billing_zip_code']);
    $request->setBillingAddress($billingAddress);

    $q_id = uniqid(999, 99999);
    $basketItems = array();
    $firstBasketItem = new \Iyzipay\Model\BasketItem();
    $firstBasketItem->setId($q_id);
    $firstBasketItem->setName("Order Id " . $q_id);
    $firstBasketItem->setCategory1("Product Purchase");
    $firstBasketItem->setCategory2("");
    $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
    $firstBasketItem->setPrice($calculatedData['total']);
    $basketItems[0] = $firstBasketItem;

    $request->setBasketItems($basketItems);

    # make request
    $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);

    $paymentResponse = (array)$payWithIyzicoInitialize;

    foreach ($paymentResponse as $key => $data) {
      $paymentInfo = json_decode($data, true);
      if ($paymentInfo['status'] == 'success') {
        if (!empty($paymentInfo['payWithIyzicoPageUrl'])) {
          Cache::forget('conversation_id');
          Session::put('iyzico_token', $paymentInfo['token']);
          Session::put('conversation_id', $conversion_id);
          Cache::put('conversation_id', $conversion_id, 60000);

          // put some data in session before redirect to paypal url
          Session::put('paymentFor', $paymentFor);
          Session::put('arrData', $arrData);

          return redirect($paymentInfo['payWithIyzicoPageUrl']);
        }
      }
      return redirect()->route('shop.purchase_product')->with(['alert-type' => 'error', 'message' => $paymentInfo['errorMessage']]);
    }
  }

  public function notify(Request $request)
  {

    $conversation_id = Cache::get('conversation_id');
    $productList = $request->session()->get('productCart');

    $arrData = $request->session()->get('arrData');
    $arrData['conversation_id'] = $conversation_id;
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

    // remove all session data
    $request->session()->forget('productCart');
    $request->session()->forget('discount');

    return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);

    // remove all session data
    Session::forget('arrData');
    return redirect()->route('product_order.complete');
  }
}
