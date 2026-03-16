<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Space;
use Basel\MyFatoorah\MyFatoorah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MyFatoorahController extends Controller
{
  private $myfatoorah;

  public function __construct()
  {
    $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
    $information = json_decode($info->information, true);
    $this->myfatoorah = MyFatoorah::getInstance($information['sandbox_status'] == 1 ? true : false);
  }
  public function index(Request $request, $data, $paymentFor)
  {
    
    $currencyInfo = $this->getCurrencyInfo();

    $available_currency = array('KWD', 'SAR', 'BHD', 'AED', 'QAR', 'OMR', 'JOD');
    if (!in_array($currencyInfo->base_currency_text, $available_currency)) {
      return redirect()->back()->with('error', __('Invalid currency for myfatoorah payment') . '.')->withInput();
    }
    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Myfatoorah';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $spaceSlug = $data['slug'];

    $cancel_url = route('service.place_order.cancel', ['slug' => $spaceSlug]);

    /********************************************************
     * send payment request to myfatoorah for create a payment url
     ********************************************************/
    $payAmount = intval($data['grandTotal']);
    $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
    $information = json_decode($info->information, true);
    $random_1 = rand(999, 9999);
    $random_2 = rand(9999, 99999);

    $result = $this->myfatoorah->sendPayment(
      $request->first_name,
      $payAmount,
      [
        'CustomerMobile' => $information['sandbox_status'] == 1 ? '56562123544' : $request->email_address,
        'CustomerReference' => "$random_1",  //orderID
        'UserDefinedField' => "$random_2", //clientID
        "InvoiceItems" => [
          [
            "ItemName" => "Space Booking",
            "Quantity" => 1,
            "UnitPrice" => $payAmount
          ]
        ]
      ]
    );
    if ($result && $result['IsSuccess'] == true) {
      Session::put('myfatoorah_payment_type', 'space');
      Session::put('arrData', $data);
      Session::put('cancel_url', $cancel_url);
      return redirect($result['Data']['InvoiceURL']);
    } else {
      return redirect($cancel_url);
    }
  }

  public function notify(Request $request)
  {

    // get the information from session
    $arrData = Session::get('arrData');
    $spaceSlug = $arrData['slug'];

    if (!empty($request->paymentId)) {
      $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);
      if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
        // remove this session datas
        Session::forget('paymentFor');
        Session::forget('arrData');

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
        return [
          'status' => 'success',
          'slug' => $spaceSlug,

        ];

      } else {
        return [
          'status' => 'fail'
        ];
      }
    } else {
      return [
        'status' => 'fail'
      ];
    }
  }
  public function failCallback(Request $request)
  {
    if (!empty($request->paymentId)) {
      $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

      if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Pending") {
        return redirect()->route('check-out')->with(['alert-type' => 'error', 'message' => 'Payment Cancel']);
      }
    }
  }
}
