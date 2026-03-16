<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Http\Controllers\Payment\MidtransController as PackageMidtransController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MidtransController as SpaceMidtransController;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MidtransBankNotifyController extends Controller
{
    public function onlineBankNotify(Request $request)
    {
        $cancel_url = route('midtrans.cancel');
        $token = Session::get('token');
        $payment_type = Session::get('midtrans_payment_type');
        

        if ($request->status_code == 200 && $token == $request->order_id) {
            if ($payment_type && $payment_type == 'package_feature') {

                $packageAndFeatureOrder = new PackageMidtransController();
                $data = $packageAndFeatureOrder->OnlineBackNotify($request->order_id);
                return redirect($data['url']);
            } elseif ($payment_type && $payment_type == 'space_booking') {

                $spaceBooking = new SpaceMidtransController();
                $data = $spaceBooking->OnlineBackNotify($request->order_id);
                return redirect($data['url']);
            } elseif ($payment_type && $payment_type == 'shop_product_order') {
                // get the information from session
                $productList = session()->get('productCart');

                $arrData = session()->get('arrData');

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

                // send a mail to the customer with the invoice
                $purchaseProcess->prepareMail($orderInfo);

                // remove all session data
                session()->forget('paymentFor');
                session()->forget('arrData');

                // remove session data
                session()->forget('productCart');
                session()->forget('discount');
                session()->forget('midtrans_payment_type');
                return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
            } else {
                //redirect to cancel url
                
                Session::flash("error", __('Payment Canceled') . '.');
                return redirect($cancel_url);
            }
        } else {
            
            return redirect($cancel_url);
        }
    }

    public function cancel()
    {
        Session::flash("error", __('Payment Canceled') . '.');
        $payment_area = Session::get('midtrans_payment_type');
        if($payment_area && $payment_area == 'package_feature'){
            Session::forget('midtrans_payment_type');
            return redirect()->route('vendor.dashboard');
        }
        elseif($payment_area && $payment_area == 'space_booking'){

            Session::forget('midtrans_payment_type');
            return redirect()->route('space.index');

        }
        elseif($payment_area && $payment_area == 'shop_product_order'){
            Session::forget('midtrans_payment_type');
            return redirect()->route('shop.products');
        }
        else{
            Session::forget('midtrans_payment_type');
            return redirect()->route('index');
        }
        
    }
}
