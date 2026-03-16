<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\Space;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

    $allowedCurrencies = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', __('Invalid currency for mollie payment') . '.')->withInput();
    }


    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = 'Mollie';
    $data['gatewayType'] = 'online';
    $data['paymentStatus'] = 'completed';
    $data['bookingStatus'] = 'pending';

    $title = __('Booking a Space');
    $serviceSlug = $data['slug'];
    $notifyURL = route('service.place_order.mollie.notify', ['slug' => $serviceSlug]);


    /**
     * we must send the correct number of decimals.
     * thus, we have used sprintf() function for format.
     */
    $payment = Mollie::api()->payments->create([
      'amount' => [
        'currency' => $currencyInfo->base_currency_text,
        'value' => sprintf('%0.2f', $data['grandTotal'])
      ],
      'description' => $title . ' via Mollie',
      'redirectUrl' => $notifyURL
    ]);

    // put some data in session before redirect to mollie url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('payment', $payment);

    return redirect($payment->getCheckoutUrl(), 303);
  }

  public function notify(Request $request)
  {
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');
    $payment = $request->session()->get('payment');

    if ($paymentFor == 'space') {
      $serviceSlug = $arrData['slug'];
    }

    $paymentInfo = Mollie::api()->payments->get($payment->id);

    if ($paymentInfo->isPaid() == true) {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('payment');

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
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('payment');

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      }
    }
  }
}
