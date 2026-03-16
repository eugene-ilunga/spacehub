<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class AuthorizeController extends Controller
{
    private $gateway;
    public function __construct()
    {
        $data = OnlineGateway::query()->whereKeyword('authorize.net')->first();
        $authorizeNetData = json_decode($data->information, true);
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName($authorizeNetData['api_login_id']);
        $this->gateway->setTransactionKey($authorizeNetData['transaction_key']);
        if ($authorizeNetData['sandbox_status'] == 1) {
            $this->gateway->setTestMode(true);
        }
    }
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bs)
    {
        $defaultLang = getVendorLanguage();
        try {
            $allowedCurrencies = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            $currencyInfo = $this->getCurrencyInfo();
            // checking whether the base currency is allowed or not
            if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
                return redirect()->back()->with('error', __('Invalid currency for authorize.net payment') . '.')->withInput();
            }
            Session::put('request', $request->all());
            $requestData = $request->all();
            $bs = Basic::first();

            if ($request->filled('opaqueDataValue') && $request->filled('opaqueDataDescriptor')) {
                // generate a unique merchant site transaction ID
                $transactionId = rand(100000000, 999999999);
                $response = $this->gateway->authorize([
                    'amount' => sprintf('%0.2f', $_amount),
                    'currency' => $currencyInfo->base_currency_text,
                    'transactionId' => $transactionId,
                    'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
                    'opaqueDataValue' => $request->opaqueDataValue
                ])->send();

                if ($response->isSuccessful()) {
                    //success process will be go here
                    $paymentFor = Session::get('paymentFor');
                    $response = json_encode($response, true);

                    $transaction_id = SellerPermissionHelper::uniqidReal(8);
                    $transaction_details = $response;
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
                } else {

                    return redirect($_cancel_url);
                }
            } else {
                //return cancel url
                return redirect($_cancel_url);
            }
        } catch (\Exception $th) {
            return redirect($_cancel_url);
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
