<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaytmController extends Controller
{
  public function index(Request $request, $paymentFor)
  {
    

    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    } else {
      Session::flash('error', 'Something went wrong!');

      return redirect()->route('shop.products');
    }

    $purchaseProcess = new PurchaseProcessController();

    // do calculation
    $calculatedData = $purchaseProcess->calculation($request, $productList);

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', __('Invalid currency for paytm payment') . '.')->withInput();
    }

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
      'paymentMethod' => 'Paytm',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );


    $notifyURL = route('shop.purchase_product.paytm.notify');

    $customerEmail = $request['billing_email'];
    $customerPhone = $request['billing_phone'];

    $item_number = uniqid('paytm-') . time();

    $paymentInfo = OnlineGateway::whereKeyword('paytm')->first();
    $paydata = $paymentInfo->convertAutoData();
    $data_for_request = $this->handlePaytmRequest($item_number, $calculatedData['grandTotal'], $notifyURL);

    if ($paydata['environment'] == 'local') {
      $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
    } else {
      $paytm_txn_url = 'https://securegw.paytm.in/theia/processTransaction';
    }

    $paramList = $data_for_request['paramList'];
    $checkSum = $data_for_request['checkSum'];

    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('arrData', $arrData);

    return view('frontend.payment.paytm', compact('paytm_txn_url', 'paramList', 'checkSum'));

  }

  public function handlePaytmRequest($_item_number, $amount, $callback_url)
  {
    $data = OnlineGateway::whereKeyword('paytm')->first();
    $paydata = $data->convertAutoData();

    // Load all functions of encdec_paytm.php and config-paytm.php
    $this->getAllEncdecFunc();
    $checkSum = "";
    $paramList = array();
    // Create an array having all required parameters for creating checksum.
    $paramList["MID"] = $paydata['merchant_mid'];
    $paramList["ORDER_ID"] = $_item_number;
    $paramList["CUST_ID"] = $_item_number;
    $paramList["INDUSTRY_TYPE_ID"] = $paydata['industry_type'];
    $paramList["CHANNEL_ID"] = 'WEB';
    $paramList["TXN_AMOUNT"] = $amount;
    $paramList["WEBSITE"] = $paydata['merchant_website'];
    $paramList["CALLBACK_URL"] = $callback_url;

    $paytm_merchant_key = $paydata['merchant_key'];
    //Here checksum string will return by getChecksumFromArray() function.
    $checkSum = getChecksumFromArray($paramList, $paytm_merchant_key);
    return array(
      'checkSum' => $checkSum,
      'paramList' => $paramList
    );
  }

  function getAllEncdecFunc()
  {
    function encrypt_e($input, $ky)
    {
      $key = html_entity_decode($ky);
      $iv = "@@@@&&&&####$$$$";
      $data = openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
      return $data;
    }

    function decrypt_e($crypt, $ky)
    {
      $key = html_entity_decode($ky);
      $iv = "@@@@&&&&####$$$$";
      $data = openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
      return $data;
    }

    function pkcs5_pad_e($text, $blocksize)
    {
      $pad = $blocksize - (strlen($text) % $blocksize);
      return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad_e($text)
    {
      $pad = ord($text[strlen($text) - 1]);
      if ($pad > strlen($text))
        return false;
      return substr($text, 0, -1 * $pad);
    }

    function generateSalt_e($length)
    {
      $random = "";
      srand((float)microtime() * 1000000);
      $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
      $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
      $data .= "0FGH45OP89";
      for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
      }
      return $random;
    }

    function checkString_e($value)
    {
      if ($value == 'null')
        $value = '';
      return $value;
    }

    function getChecksumFromArray($arrayList, $key, $sort = 1)
    {
      if ($sort != 0) {
        ksort($arrayList);
      }
      $str = getArray2Str($arrayList);
      $salt = generateSalt_e(4);
      $finalString = $str . "|" . $salt;
      $hash = hash("sha256", $finalString);
      $hashString = $hash . $salt;
      $checksum = encrypt_e($hashString, $key);
      return $checksum;
    }

    function getChecksumFromString($str, $key)
    {
      $salt = generateSalt_e(4);
      $finalString = $str . "|" . $salt;
      $hash = hash("sha256", $finalString);
      $hashString = $hash . $salt;
      $checksum = encrypt_e($hashString, $key);
      return $checksum;
    }

    function verifychecksum_e($arrayList, $key, $checksumvalue)
    {
      $arrayList = removeCheckSumParam($arrayList);
      ksort($arrayList);
      $str = getArray2StrForVerify($arrayList);
      $paytm_hash = decrypt_e($checksumvalue, $key);
      $salt = substr($paytm_hash, -4);
      $finalString = $str . "|" . $salt;
      $website_hash = hash("sha256", $finalString);
      $website_hash .= $salt;
      $validFlag = "FALSE";
      if ($website_hash == $paytm_hash) {
        $validFlag = "TRUE";
      } else {
        $validFlag = "FALSE";
      }
      return $validFlag;
    }

    function verifychecksum_eFromStr($str, $key, $checksumvalue)
    {
      $paytm_hash = decrypt_e($checksumvalue, $key);
      $salt = substr($paytm_hash, -4);
      $finalString = $str . "|" . $salt;
      $website_hash = hash("sha256", $finalString);
      $website_hash .= $salt;
      $validFlag = "FALSE";
      if ($website_hash == $paytm_hash) {
        $validFlag = "TRUE";
      } else {
        $validFlag = "FALSE";
      }
      return $validFlag;
    }

    function getArray2Str($arrayList)
    {
      $findme = 'REFUND';
      $findmepipe = '|';
      $paramStr = "";
      $flag = 1;
      foreach ($arrayList as $key => $value) {
        $pos = strpos($value, $findme);
        $pospipe = strpos($value, $findmepipe);
        if ($pos !== false || $pospipe !== false) {
          continue;
        }
        if ($flag) {
          $paramStr .= checkString_e($value);
          $flag = 0;
        } else {
          $paramStr .= "|" . checkString_e($value);
        }
      }
      return $paramStr;
    }

    function getArray2StrForVerify($arrayList)
    {
      $paramStr = "";
      $flag = 1;
      foreach ($arrayList as $key => $value) {
        if ($flag) {
          $paramStr .= checkString_e($value);
          $flag = 0;
        } else {
          $paramStr .= "|" . checkString_e($value);
        }
      }
      return $paramStr;
    }

    function redirect2PG($paramList, $key)
    {
      $hashString = getchecksumFromArray($paramList, $key);
      $checksum = encrypt_e($hashString, $key);
    }

    function removeCheckSumParam($arrayList)
    {
      if (isset($arrayList["CHECKSUMHASH"])) {
        unset($arrayList["CHECKSUMHASH"]);
      }
      return $arrayList;
    }

    function getTxnStatus($requestParamList)
    {
      return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
    }

    function getTxnStatusNew($requestParamList)
    {
      return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
    }

    function initiateTxnRefund($requestParamList)
    {
      $CHECKSUM = getRefundChecksumFromArray($requestParamList, PAYTM_MERCHANT_KEY, 0);
      $requestParamList["CHECKSUM"] = $CHECKSUM;
      return callAPI(PAYTM_REFUND_URL, $requestParamList);
    }

    function callAPI($apiURL, $requestParamList)
    {
      $jsonResponse = "";
      $responseParamList = array();
      $JsonData = json_encode($requestParamList);
      $postData = 'JsonData=' . urlencode($JsonData);
      $ch = curl_init($apiURL);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($postData)
        )
      );
      $jsonResponse = curl_exec($ch);
      $responseParamList = json_decode($jsonResponse, true);
      return $responseParamList;
    }

    function callNewAPI($apiURL, $requestParamList)
    {
      $jsonResponse = "";
      $responseParamList = array();
      $JsonData = json_encode($requestParamList);
      $postData = 'JsonData=' . urlencode($JsonData);
      $ch = curl_init($apiURL);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($postData)
        )
      );
      $jsonResponse = curl_exec($ch);
      $responseParamList = json_decode($jsonResponse, true);
      return $responseParamList;
    }

    function getRefundChecksumFromArray($arrayList, $key, $sort = 1)
    {
      if ($sort != 0) {
        ksort($arrayList);
      }
      $str = getRefundArray2Str($arrayList);
      $salt = generateSalt_e(4);
      $finalString = $str . "|" . $salt;
      $hash = hash("sha256", $finalString);
      $hashString = $hash . $salt;
      $checksum = encrypt_e($hashString, $key);
      return $checksum;
    }

    function getRefundArray2Str($arrayList)
    {
      $findmepipe = '|';
      $paramStr = "";
      $flag = 1;
      foreach ($arrayList as $key => $value) {
        $pospipe = strpos($value, $findmepipe);
        if ($pospipe !== false) {
          continue;
        }
        if ($flag) {
          $paramStr .= checkString_e($value);
          $flag = 0;
        } else {
          $paramStr .= "|" . checkString_e($value);
        }
      }
      return $paramStr;
    }

    function callRefundAPI($refundApiURL, $requestParamList)
    {
      $jsonResponse = "";
      $responseParamList = array();
      $JsonData = json_encode($requestParamList);
      $postData = 'JsonData=' . urlencode($JsonData);
      $ch = curl_init($refundApiURL);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_URL, $refundApiURL);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $jsonResponse = curl_exec($ch);
      return json_decode($jsonResponse, true);
    }
  }

  public function notify(Request $request)
  {

    $productList = $request->session()->get('productCart');

    $arrData = $request->session()->get('arrData');

    $transaction = PaytmWallet::with('receive');


    // this response is needed to check the transaction status
    $response = $transaction->response();

    if($response['STATUS'] === "TXN_FAILURE")
    {

      session()->flash('error', $response['RESPMSG']);
      return redirect()->route('shop.purchase_product.cancel');

    }

    if ($transaction->isSuccessful()) {

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
      $adminData['life_time_earning'] =  $arrData['grandTotal'] ;
      if ($orderInfo['seller_id'] != null) {
        $adminData['total_profit'] =  $arrData['grandTotal'] ;
      } else {
        $adminData['total_profit'] =  $arrData['grandTotal'] ;
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
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      
      return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
    } else {
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('productCart');
      $request->session()->forget('discount');

      return redirect()->route('shop.purchase_product.cancel');
    }
  }
}
