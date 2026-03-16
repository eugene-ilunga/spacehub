<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FrontEnd\PaymentGateway\MyFatoorahController as SpaceMyFatoorahController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MyFatoorahController as ShopMyFatoorahController;
use App\Http\Controllers\Payment\MyFatoorahController as PaymentMyFatoorahController;

class MyFatoorahController extends Controller
{

    public function myfatoorah_callback(Request $request)
    {
        
        $type = Session::get('myfatoorah_payment_type');
        if ($type == 'space') {
            $data = new SpaceMyFatoorahController();
            $data = $data->notify($request);
            Session::forget('myfatoorah_payment_type');
            if ($data['status'] == 'success') {
                return redirect()->route('service.place_order.complete', ['slug' => $data['slug'], 'via' => 'online']);
            } else {

                Session::forget('paymentFor');
                Session::forget('arrData');
                Session::forget('myfatoorah_payment_type');
                Session::flash('error', __('Your payment has been canceled') . '.');
                return redirect()->route('space.index');
            }
        }elseif(in_array($type, ['membership', 'extend', 'feature'])){
            
            $data = new PaymentMyFatoorahController();
            $data = $data->successPayment($request);
            Session::forget('myfatoorah_payment_type');
            return redirect($data['url']);

        } elseif ($type == 'shop') {
            $data = new ShopMyFatoorahController();
            $data = $data->successCallback($request);
            Session::forget('myfatoorah_payment_type');
            if ($data['status'] == 'success') {
                return redirect()->route('shop.purchase_product.complete', ['type' => 'online']);
            } else {
                Session::flash('error', __('Your payment has been canceled') . '.');
                return redirect()->route('shop.checkout')->with(['alert-type' => 'error', 'message' => __('Payment Cancel') .  '.']);
            }
        }
    }

    public function myfatoorah_cancel(Request $request)
    {
        $type = Session::get('myfatoorah_payment_type');
        if($type == 'space'){
            return redirect()->route('space.index')->with(['alert-type' => 'error', 'message' => __('Payment failed') . '.']);
        }
        elseif(in_array($type, ['membership', 'extend', 'feature'])){
            $data = new PaymentMyFatoorahController();
            $data->cancelPayment();
        }
        elseif($type == 'shop'){
            Session::flash('error', __('Your payment has been canceled') . '.');
            return redirect()->route('shop.checkout')->with(['alert-type' => 'error', 'message' => __('Payment failed')]);
        }
        else{
            return redirect()->route('index')->with(['alert-type' => 'error', 'message' => __('Payment failed') . '.']);
        }

    }
}

