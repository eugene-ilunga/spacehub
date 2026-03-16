<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    '*/razorpay/notify',
    '*/mercadopago/notify',
    '*/paytm/notify',
    '/shop/purchase-product/razorpay/notify',
    '/shop/purchase-product/mercadopago/notify',
    '/shop/purchase-product/paytm/notify',
    '/pay/razorpay/notify',
    '/pay/mercadopago/notify',
    '/pay/paytm/notify',
    '/vendor/membership/flutterwave/success',
    '/vendor/membership/razorpay/success',
    '/vendor/membership/mercadopago/notify',
    '/vendor/membership/paytm/payment-status',
    '*/iyzico/success',
    '*/iyzico/notify',
    '*/paytabs/notify',
    '*/freshpay/notify',
    '*/midtrans/success',
    '*/midtrans/notify',
    '*paytabs/success',
  ];
}
