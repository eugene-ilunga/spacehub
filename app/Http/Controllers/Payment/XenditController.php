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
use App\Models\Package;
use App\Models\Seller;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class XenditController extends Controller
{
  public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
  {

    $cancel_url = $_cancel_url;
    $notify_url = $_success_url;

    Session::put("request", $request->all());
    Session::put('cancel_url', $cancel_url);

    $external_id = Str::random(10);
    $secret_key = 'Basic ' . config('xendit.key_auth');
    $data_request = Http::withHeaders([
      'Authorization' => $secret_key
    ])->post('https://api.xendit.co/v2/invoices', [
      'external_id' => $external_id,
      'amount' => (int) round($_amount),
      'currency' => $bex->base_currency_text,
      'success_redirect_url' => $notify_url
    ]);

    $response = $data_request->object();
    $response = json_decode(json_encode($response), true);

    if (!empty($response['success_redirect_url'])) {
      Session::put('xendit_id', $response['id']);
      Session::put('secret_key', config('xendit.key_auth'));
      return redirect($response['invoice_url']);
    } else {
      return redirect($cancel_url)->with('error', __('Payment Canceled') . '.');
    }
  }

  public function successPayment(Request $request)
  {
    $defaultLang = getVendorLanguage();

    $requestData = Session::get('request');
    $bs = Basic::first();
    $cancel_url = Session::get('cancel_url');
    /** Get the payment ID before session clear **/

    $xendit_id = Session::get('xendit_id');
    $secret_key = Session::get('secret_key');
    if (!is_null($xendit_id) && $secret_key == config('xendit.key_auth')) {
      $paymentFor = Session::get('paymentFor');

      $transaction_id = SellerPermissionHelper::uniqidReal(8);
      $transaction_details = json_encode($request['payment_request_id']);

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
        return redirect()->route('success.page', ['language' => $defaultLang->code]);
      } else {
        return redirect($cancel_url);
      }
    }
    return redirect($cancel_url);
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
