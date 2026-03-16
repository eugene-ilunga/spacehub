<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class AuthorizeNetController extends Controller
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

  public function index(Request $request, $data, $paymentFor)
  {

    $allowedCurrencies = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', __('Invalid currency for authorize.net payment') . '.')->withInput();
    }

    $arrData = $data;
    $arrData['currencyText'] = $currencyInfo->base_currency_text;
    $arrData['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $arrData['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $arrData['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $arrData['paymentMethod'] = 'Authorize.Net';
    $arrData['gatewayType'] = 'online';
    $arrData['paymentStatus'] = 'completed';
    $arrData['bookingStatus'] = 'pending';
    $serviceSlug = $arrData['slug'];


    if ($request->filled('opaqueDataValue') && $request->filled('opaqueDataDescriptor')) {

      // generate a unique merchant site transaction ID
      $transactionId = rand(100000000, 999999999);


      $response = $this->gateway->authorize([
        'amount' => sprintf('%0.2f', $arrData['grandTotal']),
        'currency' => $currencyInfo->base_currency_text,
        'transactionId' => $transactionId,
        'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
        'opaqueDataValue' => $request->opaqueDataValue
      ])->send();

      if ($response->isSuccessful()) {

        if ($paymentFor == 'space') {
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

          return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
        }
      } else {

        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      }
    } else {

      return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
    }
  }
}
