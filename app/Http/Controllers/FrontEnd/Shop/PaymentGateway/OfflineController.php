<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\OrderController;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\Shop\Product;
use App\Models\ShopManagement\ShippingCharge;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  public function index(Request $request, $paymentFor)
  {
   
    $gatewayId = $request->gateway;
    $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);

    // validation start
    if ($offlineGateway->has_attachment == 1) {
      $rules = [
        'attachment.' . $gatewayId => [
          'required',
          new ImageMimeTypeRule()
        ]
      ];

      $message = [
        'attachment.' . $gatewayId . '.required' => __('Please attach your payment receipt') . '.'
      ];

      $validator = Validator::make($request->only('attachment'), $rules, $message);


      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors())->withInput();
      }
    }
    // validation end

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

    $directory ='./assets/file/attachments/product/';

    if ($request->hasFile('attachment')) {
      $files = $request->file('attachment');
      if (is_array($files)) {
        foreach ($files as $file) {
          $attachmentName = UploadFile::store($directory, $file);
          // Handle the stored file name as needed
        }
      } else {
        $attachmentName = UploadFile::store($directory, $files);
        // Handle the stored file name as needed
      }
    }

    $currencyInfo = $this->getCurrencyInfo();

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
      'paymentMethod' => $offlineGateway->name,
      'gatewayType' => 'offline',
      'paymentStatus' => 'pending',
      'orderStatus' => 'pending',
      'attachment' => $attachmentName ?? null
    );

    // store product order information in database
    $purchaseProcess->storeData($productList, $arrData);

    // then subtract each product quantity from respective product stock
    foreach ($productList as $key => $item) {
      $product = Product::query()->find($key);

      if ($product->product_type == 'physical') {
        $stock = $product->stock - intval($item['quantity']);

        $product->update(['stock' => $stock]);
      }
    }

    // remove all session data
    $request->session()->forget('productCart');
    $request->session()->forget('discount');

    return redirect()->route('shop.purchase_product.complete', ['type' => 'offline_purchase']);
  }
}
