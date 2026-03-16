<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class PerfectMoneyController extends Controller
{
    public function index(Request $request, $arrayData, $paymentFor)
    {
        
        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the currency is set to 'INR' or not
        if ($currencyInfo->base_currency_text !== 'USD') {
            return redirect()->back()->with('error', 'Invalid currency for perfect money payment.')->withInput();
        }
        $arrayData['currencyText'] = $currencyInfo->base_currency_text;
        $arrayData['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $arrayData['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $arrayData['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $arrayData['paymentMethod'] = 'Perfect Money';
        $arrayData['gatewayType'] = 'online';
        $arrayData['paymentStatus'] = 'completed';
        $arrayData['bookingStatus'] = 'pending';
        $spaceSlug = $arrayData['slug'];

        $serviceSlug = $arrayData['slug'];
        $info = OnlineGateway::where('keyword', 'perfect_money')->first();
        $information = json_decode($info->information, true);

        $cancel_url = route('service.place_order.cancel', ['slug' => $spaceSlug]);
        $notifyURL = route('service.place_order.perfect_money.notify', ['slug' => $spaceSlug]);

        $randomNo = substr(uniqid(), 0, 8);
        $websiteInfo = Basic::select('website_title', 'base_currency_text')->first();
        $perfect_money = OnlineGateway::where('keyword', 'perfect_money')->first();
        $info = json_decode($perfect_money->information, true);

        $val['PAYEE_ACCOUNT'] = $info['perfect_money_wallet_id'];;
        $val['PAYEE_NAME'] = $websiteInfo->website_title;
        $val['PAYMENT_ID'] = "$randomNo"; //random id
        $val['PAYMENT_AMOUNT'] = $arrayData['grandTotal'];
        $val['PAYMENT_UNITS'] = "$websiteInfo->base_currency_text";

        $val['STATUS_URL'] = $notifyURL;
        $val['PAYMENT_URL'] = $notifyURL;
        $val['PAYMENT_URL_METHOD'] = 'GET';
        $val['NOPAYMENT_URL'] = $cancel_url;
        $val['NOPAYMENT_URL_METHOD'] = 'GET';
        $val['SUGGESTED_MEMO'] = $request->email_address;
        $val['BAGGAGE_FIELDS'] = 'IDENT';

        $data['val'] = $val;
        $data['method'] = 'get';
        $data['url'] = 'https://perfectmoney.com/api/step1.asp';

        Session::put('payment_id', $randomNo);
        Session::put('arrData', $arrayData);
        return view('frontend.payment.perfect-money', compact('data'));
    }

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $spaceSlug = $arrData['slug'];

        $perfect_money = OnlineGateway::where('keyword', 'perfect_money')->first();
        $perfectMoneyInfo = json_decode($perfect_money->information, true);
        $currencyInfo = Basic::select('base_currency_text')->first();

        $amo = $request['PAYMENT_AMOUNT'];
        $unit = $request['PAYMENT_UNITS'];
        $track = $request['PAYMENT_ID'];
        $id = Session::get('payment_id');
        $final_amount = $arrData['grandTotal']; //live amount

        if ($request->PAYEE_ACCOUNT == $perfectMoneyInfo['perfect_money_wallet_id'] && $unit == $currencyInfo->base_currency_text && $track == $id && $amo == round($final_amount, 2)) {

            $orderProcess = new OrderProcessController();

            // store service order information in database
            $selected_service = Space::where('id', $arrData['spaceId'])->select('seller_id')->first();
            if (
                $selected_service->seller_id != 0
            ) {
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

            // remove this session datas
            Session::forget('paymentFor');
            Session::forget('arrData');

            return redirect()->route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'online']);
        } else {
            Session::forget('paymentFor');
            Session::forget('arrData');
            Session::flash('error', 'Your payment has been canceled.');
            return redirect()->route('space.index');
        }
    }
}
