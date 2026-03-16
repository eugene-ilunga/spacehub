<?php

namespace App\Http\Controllers\Payment;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PaytmController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_item_number, $_callback_url)
    {
        $data = OnlineGateway::whereKeyword('paytm')->first();
        $paydata = $data->convertAutoData();
        $data_for_request = $this->handlePaytmRequest($_item_number, $_amount, $_callback_url);
        if ($paydata['environment'] == 'local') {
            $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
        } else {
            $paytm_txn_url = 'https://securegw.paytm.in/theia/processTransaction';
        }
        $paramList = $data_for_request['paramList'];
        $checkSum = $data_for_request['checkSum'];
     
        Session::put("request", $request->all());
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

    public function paymentStatus(Request $request)
    {
        
        $defaultLang = getVendorLanguage();
        $requestData = Session::get('request');
        $bs = Basic::first();
        $paymentFor = Session::get('paymentFor');
        if ($request["STATUS"] === "TXN_FAILURE") {
            $paymentFor = Session::get('paymentFor');
            session()->flash('warning', $request['RESPMSG']);
            if ($paymentFor == 'extend' || $paymentFor == "membership") {
                return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id'], 'language' => $defaultLang->code])->withInput($requestData);
            }
            if ($paymentFor == 'feature') {
                Session::flash('warning', $request['RESPMSG']);
                return redirect()->route('vendor.space_management.space.index', ['language' => $defaultLang->code])->withInput($requestData);
            }
        } elseif ($request['STATUS'] === 'TXN_SUCCESS') {

            $transaction_id = SellerPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request);

            if (in_array($paymentFor, ['membership', 'extend'])) {
                $package = Package::find($requestData['package_id']);
                $amount = $requestData['price'];
                $password = $paymentFor == 'membership'
                    ? ($requestData['password'] ?? null)
                    : uniqid('qrcode');

                $checkout = new VendorCheckoutController();
                $lastMembership = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                // get vendor information
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

                // process invoice data
                $membershipInvoiceData = [
                    'name'      => $vendor->seller_name,
                    'username'  => $vendor->username,
                    'email'     => $vendor->email,
                    'phone'     => $vendor->phone,
                    'order_id'  => $transaction_id,
                    'base_currency_text_position'  => $bs->base_currency_text_position,
                    'base_currency_text'  => $bs->base_currency_text,
                    'base_currency_symbol'  => $bs->base_currency_symbol,
                    'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
                    'amount'  => $amount,
                    'payment_method'  => $requestData["payment_method"],
                    'package_title'  => $package->title,
                    'start_date'  => $requestData["start_date"],
                    'expire_date'  => $requestData["expire_date"],
                    'website_title'  => $bs->website_title,
                    'logo'  => $bs->logo,
                ];

                $file_name = $this->makeInvoice($membershipInvoiceData);
                $lastMembership->update(['invoice' => $file_name]);

                //process mail data
                $mailData = [
                    'toMail' => $vendor->email,
                    'toName' => $vendor->fname,
                    'username' => $vendor->username,
                    'package_title' => $package->title,
                    'package_price' => $currencyFormat($package->price),
                    'total' => $currencyFormat($lastMembership->price),
                    'activation_date' => $activation->toFormattedDateString(),
                    'expire_date' => $expireDateFormatted,
                    'membership_invoice' => $file_name,
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

                Session::forget(['request', 'paymentFor']);
                return redirect()->route('success.page', ['language' => $defaultLang->code]);
            } elseif ($paymentFor == "feature") {
                $amount = $request['price'];
                $requestData['payment_status'] = 'completed';
                $requestData['gateway_type'] = 'online';
                $password = uniqid('qrcode');
                $checkout = new VendorCheckoutController();

                // Process feature payment
                $featureInfo = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                // Store transaction
                $transaction_data = [
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
                storeTransaction($transaction_data);

                // Store earnings
                storeEarnings([
                    'life_time_earning' => $featureInfo->total,
                    'total_profit' => $featureInfo->total
                ]);

                // Get space content details

                $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
                    ['space_id', $featureInfo->space_id],
                    ['language_id', $defaultLang->id]
                ])->first();

                //set url and space title
                $url = $spaceContent ? route('space.details', [
                    'slug' => $spaceContent->slug,
                    'id' => $featureInfo->space_id
                ]) : null;

                $spaceName = $spaceContent ? $spaceContent->title : null;

                // Get vendor info
                $vendorInfo = $this->getVendorDetails($featureInfo->seller_id);

                $vendorName = $vendorInfo->seller_name;

                $featureInvoiceData = [
                    'name'      => $vendorInfo->seller_name,
                    'username'  => $vendorInfo->username,
                    'email'     => $vendorInfo->email,
                    'phone'     => $vendorInfo->phone,
                    'order_id'  => $transaction_id,
                    'base_currency_text_position'  => $bs->base_currency_text_position,
                    'base_currency_text'  => $bs->base_currency_text,
                    'base_currency_symbol'  => $bs->base_currency_symbol,
                    'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
                    'amount'  => $featureInfo->total,
                    'payment_method'  => $requestData["payment_method"],
                    'space_title'  => $spaceName,
                    'start_date'  => $featureInfo->start_date,
                    'expire_date'  => $featureInfo->end_date,
                    'website_title'  => $bs->website_title,
                    'logo'  => $bs->logo,
                    'day'  => $featureInfo->days,
                    'purpose'  => 'feature',
                ];

                // Generate and update invoice

                $invoice = $this->makeInvoice($featureInvoiceData);
                $featureInfo->update(['invoice' => $invoice]);

                // Send payment confirmation email
                SpaceFeature::sendPaymentStatusEmail(
                    $featureInfo,
                    $url,
                    $spaceName,
                    $vendorName,
                    $bs->website_title,
                    'featured_request_payment_approved',
                    $featureInfo->invoice
                );

                // Set success message and clear session
                session()->flash('success', __('Your payment is successful, feature request is sent') . '!');
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page', ['language' => $defaultLang->code]);
            }
        }
    }

    protected function getVendorDetails($sellerId)
    {
        return Seller::select('sellers.*', 'seller_infos.name as seller_name')
            ->leftJoin('seller_infos', function ($join) use ($sellerId) {
                $join->on('sellers.id', '=', 'seller_infos.seller_id')
                    ->where('sellers.id', $sellerId);
            })
            ->where('sellers.id', '=', $sellerId)
            ->first();
    }
}
