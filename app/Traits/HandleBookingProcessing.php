<?php

namespace App\Traits;

use App\Http\Controllers\FrontEnd\OrderProcessController as FrontEndOrderProcessController;
use App\Models\SpaceBooking;

trait HandleBookingProcessing
{
  use HandlesMailPreparation, HandlesInvoiceGeneration;
  public function processOrder($id, $request)
  {
    $order = SpaceBooking::query()->findOrFail($id);

    if ($request['payment_status'] == 'completed') {
      $order->update([
        'payment_status' => 'completed'
      ]);

      // generate an invoice in pdf format
      $orderProcess = new FrontEndOrderProcessController();
  
      $invoice = $this->backendGenerateInvoice($order);

      // then, update the invoice field info in database
      $order->update(['invoice' => $invoice]);
      $vendorData['sub_total'] = $order->sub_total ?? 0;
      $vendorData['seller_id'] = $order->seller_id ?? null;
      // add balance to vendor
      storeAmountToSeller($vendorData);

      // add balance to admin revenue
      $adminData['life_time_earning'] = $order->grand_total ?? 0;
      if ($order['seller_id'] != null) {
        $adminData['total_profit'] = $order->tax ?? 0;
      } else {
        $adminData['total_profit'] = $order->grand_total ?? 0;
      }
      storeEarnings($adminData);

      // store Transaction data
      $order['transaction_type'] = 1;
      storeTransaction($order);

      // store amount to vendor
      $vendorData['seller_id'] = $order['seller_id'];
      $vendorData['price'] = $order['grandTotal'];
      $vendorData['tax'] = $order->tax;
      storeAmountToSeller($vendorData);

      // send a mail to the customer with the invoice
      $this->prepareMail($order);
      // send a mail to the vendor
      $orderProcess->prepareMailForVendor($order);
    }
  }
}
