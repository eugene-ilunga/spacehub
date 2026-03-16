<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Models\Language;
use App\Models\PaymentGateway\OnlineGateway;
use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Http\Helpers\MegaMailer;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePeController extends Controller
{
    private $sandboxCheck;
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {


        $info = OnlineGateway::where('keyword', 'phonepe')->first();
        $paydata = json_decode($info->information, true);

        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;

        $this->sandboxCheck = $paydata['sandbox_status'];

        $clientId = $paydata['merchant_id'];
        $clientSecret = $paydata['salt_key'];

        //* Here i completed 1 step which is generating access token in each request

        $accessToken = $this->getPhonePeAccessToken($clientId, $clientSecret);

        if (!$accessToken) {
            return back()->withError(__('Failed to get PhonePe access token') . '.');
        }
        Session::put("request", $request->all());
        Session::put('cancel_url', $cancel_url);

        return  $this->initiatePayment($accessToken, $notify_url, $cancel_url, $_amount);
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
            'amount' => intval($_amount * 100),
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
                return back()->with('error', 'Failed to initiate payment' . '.');
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

    public function successPayment(Request $request)
    {
        $defaultLang = getVendorLanguage();
        $requestData = Session::get('request');
        $bs = Basic::first();
        $cancel_url = Session::get('cancel_url');

        /** Get the payment ID before session clear **/
        $info = OnlineGateway::where('keyword', 'phonepe')->first();

        $merchantOrderId = $request->input('merchantOrderId') ??
            Session::get('merchantOrderId') ??
            uniqid();

        $verificationResponse = $this->verifyOrderStatus($merchantOrderId);

        // Prepare transaction details with all relevant data
        $transactionDetails = [
            'payment_gateway' => 'PhonePe',
            'merchant_order_id' => $merchantOrderId,
            'gateway_response' => $verificationResponse,
            'request_data' => $requestData,
        ];

        if ($verificationResponse['success']) {

            $paymentFor = Session::get('paymentFor');
            $transaction_id = SellerPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($transactionDetails);

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

                Session::forget(['request', 'paymentFor', 'merchantOrderId']);
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
                $misc = new MiscellaneousController();
                $language = $misc->getLanguage();
                $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
                    ['space_id', $featureInfo->space_id],
                    ['language_id', $language->id]
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
                $invoice = SpaceFeature::generateInvoice($featureInfo);
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
                Session::forget('merchantOrderId');
                return redirect()->route('success.page', ['language' => $defaultLang->code]);
            }
        }
        return redirect($cancel_url);
    }

    private function verifyOrderStatus($merchantOrderId)
    {
        $info = OnlineGateway::where('keyword', 'phonepe')->first();
        $paymentInfo = json_decode($info->information, true);

        $this->sandboxCheck = $paymentInfo['sandbox_status'];

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



    public function cancelPayment()
    {
        $defaultLang = getVendorLanguage();
        $requestData = Session::get('request');
        $errorMessage = __('Something went wrong') . '.';

        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', $errorMessage);
        if ($paymentFor == "membership" || $paymentFor == "extend") {
            Session::forget('merchantOrderId');
            Session::forget('paymentFor');
            return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id'], 'language' => $defaultLang->code])->withInput($requestData);
        } elseif ($paymentFor == "feature") {
            Session::forget('merchantOrderId');
            Session::forget('paymentFor');
            return redirect()->route('vendor.space_management.space.index', ['language' => $defaultLang->code])->withInput($requestData);
        } else {
            Session::forget('merchantOrderId');
            Session::forget('paymentFor');
            return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id'], 'language' => $defaultLang->code])->withInput($requestData);
        }
    }
}
