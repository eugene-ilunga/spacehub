<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
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
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('razorpay')->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs)
    {
        $cancel_url = $_cancel_url;
        $notifyURL = $_success_url;

        $orderData = [
            'receipt' => $_title,
            'amount' => $_amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        Session::put('request', $request->all());
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key" => $this->keyId,
            "amount" => $_amount,
            "name" => $_title,
            "description" => $_description,
            "prefill" => [
                "name" => $request->name,
                "email" => $request->address,
                "contact" => $request->razorpay_phone,
            ],
            "notes" => [
                "address" => $request->razorpay_address,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bs->base_currency_text !== 'INR') {
            $data['display_currency'] = $bs->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $jsonData = json_encode($data);
        $displayCurrency = $bs->base_currency_text;

        return view('frontend.payment.razorpay', compact('data', 'displayCurrency', 'jsonData', 'notifyURL'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $defaultLang = getVendorLanguage();
        $bs = Basic::first();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('order_payment_id');
        $success = true;
        if (empty($request['razorpay_payment_id']) === false) {

            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $request['razorpay_payment_id'],
                    'razorpay_signature' => $request['razorpay_signature']
                );

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
            }
        }

        if ($success === true) {

            $paymentFor = Session::get('paymentFor');

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
        } else {
            return redirect()->route('membership.razorpay.cancel');
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
