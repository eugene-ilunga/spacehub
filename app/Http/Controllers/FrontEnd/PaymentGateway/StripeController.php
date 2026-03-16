<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Models\Space;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StripeController extends Controller
{

  public function index(Request $request, $data, $paymentFor)
  {
    
  
    $currencyInfo = $this->getCurrencyInfo();

    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(($data['grandTotal'] / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? $data['grandTotal'] : $convertedTotal;

    if ($request->billing_email_address) {
      $billingEmail = $request->billing_email_address;
    } else {
      //service payment
      $billingEmail = $request->email_address;
    }
    if ($request->billing_first_name) {
      //shop payment
      $billingName = $request->billing_first_name . ' ' . $request->billing_last_name;
    } else {
      //service payment
      $billingName = $request->first_name ?? '';
    }


    $currencySym =  $currencyInfo->base_currency_symbol ?? '';
    $descriptions =  $data['grandTotal'] . $currencySym . ' paid for booking';

      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Stripe';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['bookingStatus'] = 'pending';

    try {
      // initialize stripe
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      // create a Stripe customer
      $customer = $stripe->customers()->create([
        'email' => $billingEmail,
        'source' => $request->stripeToken,
      ]);


      // Check if the customer response is an array
      if (is_array($customer)) {
        // Extract the customer ID from the array
        $customerId = $customer['id'];
      } else {
        // The response is an object, use the id property
        $customerId = $customer->id;
      }

      // create the charge using the customer's ID
      $charge = $stripe->charges()->create([
        'customer' => $customerId,
        'currency' => 'USD',
        'amount'   => $stripeTotal,
        'description' => $descriptions,
        'receipt_email' => $billingEmail,
        'metadata' => [
          'customer_name' => $billingName,
        ]
      ]);
 

      if ($paymentFor == 'space') {
        $serviceSlug = $data['slug'];
      }

      if ($charge['status'] == 'succeeded') {
        if ($paymentFor == 'space') {
          $orderProcess = new OrderProcessController();

          $selected_service = Space::where('id', $data['spaceId'])->select('seller_id')->first();
 
          if ($selected_service->seller_id != 0) {
            $data['seller_id'] = $selected_service->seller_id;
          } else {
            $data['seller_id'] = null;
          }
          $bookingInfo = $orderProcess->storeData($data);

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
        if ($paymentFor == 'space') {
          return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
        }
      }
    } catch (CardErrorException $e) {
      $request->session()->flash('error', $e->getMessage());

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $data['slug']]);
      }
    } catch (UnauthorizedException $e) {
      $request->session()->flash('error', $e->getMessage());

      if ($paymentFor == 'space') {
        return redirect()->route('service.place_order.cancel', ['slug' => $data['slug']]);
      }
    }
  }

}
