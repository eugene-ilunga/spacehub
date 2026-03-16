<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PaytabsController extends Controller
{
  public function index(Request $request, $paymentFor)
  {

    $currencyInfo = $this->getCurrencyInfo();
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

    //get data using helper functin in Helper.php
    $paytabInfo = paytabInfo();
    if ($currencyInfo->base_currency_text != $paytabInfo['currency']) {
      return redirect()->back()->with('currency_error', __('Invalid currency for paytabs payment') . '.')->withInput();
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
      'paymentMethod' => 'Paytabs',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );


    $description = 'Product Purchase via paytabs';
    try {
      $response = Http::withHeaders([
        'Authorization' => $paytabInfo['server_key'], // Server Key
        'Content-Type' => 'application/json',
      ])->post($paytabInfo['url'], [
        'profile_id' => $paytabInfo['profile_id'], // Profile ID
        'tran_type' => 'sale',
        'tran_class' => 'ecom',
        'cart_id' => uniqid(),
        'cart_description' => $description,
        'cart_currency' => $paytabInfo['currency'], // set currency by region
        'cart_amount' => round($calculatedData['grandTotal'], 2),
        'return' => route('shop.purchase_product.paytabs.notify'),
      ]);

      $responseData = $response->json();

      $request->session()->put('arrData', $arrData);
      return redirect()->to($responseData['redirect_url']);
    } catch (\Exception $e) {
      return redirect()->route('shop.checkout')->with(['alert-type' => 'error', 'message' => __('Payment Canceled') . '.']);
    }
  }

  public function notify(Request $request)
  {
    $resp = $request->all();
    if ($resp['respStatus'] == "A" && $resp['respMessage'] == 'Authorised') {

      // get the information from session
      $productList = $request->session()->get('productCart');

      $arrData = $request->session()->get('arrData');

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
      $adminData['life_time_earning'] =  $arrData['grandTotal'];
      if ($orderInfo['seller_id'] != null) {
        $adminData['total_profit'] =  $arrData['grandTotal'];
      } else {
        $adminData['total_profit'] =  $arrData['grandTotal'];
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
      Session::forget('arrData');

      return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
    } else {
      return redirect()->route('shop.checkout')->with(['alert-type' => 'error', 'message' => __('Payment Canceled') . '.']);
    }
  }
}
