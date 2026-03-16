<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;

class PaytmController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', __('Invalid currency for paytm payment') . '.')->withInput();
    }


    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Paytm';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';
    $item_number = uniqid('paytm-') . time();


    $serviceSlug = $data['slug'];
    $notifyURL = route('service.place_order.paytm.notify', ['slug' => $serviceSlug]);

    $paymentInfo = OnlineGateway::whereKeyword('paytm')->first();
    $paydata = $paymentInfo->convertAutoData();
    $data_for_request = $this->handlePaytmRequest($item_number, $data['grandTotal'], $notifyURL);
    if ($paydata['environment'] == 'local') {
      $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
    } else {
      $paytm_txn_url = 'https://securegw.paytm.in/theia/processTransaction';
    }
    $paramList = $data_for_request['paramList'];
    $checkSum = $data_for_request['checkSum'];

    // put some data in session before redirect to paytm url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);
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

    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');


    if ($request['STATUS'] === 'TXN_SUCCESS') {

      if ($paymentFor == 'space') {
        $serviceSlug = $arrData['slug'];
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

        $request->session()->forget('paymentFor');
        $request->session()->forget('arrData');

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
      }
    } else {

      $errorMessage = __('Your payment has been canceled') . '.';

      if ($request['RESPMSG']) {
        $errorMessage = $request['RESPMSG'];
      }

      $request->session()->flash('error', $errorMessage);
      
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      return redirect()->route('space.index');
    }
  }
}
