<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\AuthorizeNetController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\FreshpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MyFatoorahController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaytabsController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PerfectMoneyController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PhonepeController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\ToyyibpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\XenditController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\YocoController;
use Illuminate\Http\Request;
use App\Http\Helpers\BasicMailer;
use App\Models\Shop\ProductOrder;
use App\Models\BasicSettings\Basic;
use App\Models\Shop\ShippingCharge;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Shop\ProductPurchaseItem;
use App\Models\BasicSettings\MailTemplate;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PayPalController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\StripeController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MidtransController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\IyzipayController;
use App\Models\Admin;
use Mpdf\Mpdf;

class PurchaseProcessController extends Controller
{
  public function index(Request $request)
  {

    $rules = [
      'billing_name' => 'required',
      'billing_email' => 'required',
      'billing_phone' => 'required',
      'billing_city' => 'required',
      'billing_country' => 'required',
      'billing_zip_code' => 'required',
      'billing_address' => 'required',
    ];

    if ($request->differentaddress == 1) {
      $rules['shipping_name'] = 'required';
      $rules['shipping_email'] = 'required';
      $rules['shipping_phone'] = 'required';
      $rules['shipping_country'] = 'required';
      $rules['shipping_city'] = 'required';
      $rules['shipping_zip_code'] = 'required';
      $rules['shipping_address'] = 'required';
    }
    Session::flash('gatewayId', $request->gateway);
    $request->validate($rules);


    if (!$request->exists('gateway')) {
      Session::flash('error', __('Please select a payment method') . '.');

      return redirect()->back()->withInput();
    }
    if (!onlyDigitalItemsInCart()) {
      if (!$request->exists('shipping_method')) {
        Session::flash('error', __('Please select a shipping method') . '.');
        return redirect()->back()->withInput();
      }
    }
    if ($request['gateway'] == 'paypal') {
      $paypal = new PayPalController();

      return $paypal->index($request, 'product purchase');
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->index($request, 'product purchase');
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->index($request, 'product purchase');
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->index($request, 'product purchase');
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->index($request, 'product purchase');
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->index($request, 'product purchase');
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();

      return $mollie->index($request, 'product purchase');
    } else if ($request['gateway'] == 'stripe') {
      $stripe = new StripeController();

      return $stripe->index($request, 'product purchase');
    } else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();

      return $paytm->index($request, 'product purchase');
    } else if ($request['gateway'] == 'midtrans') {

      $midtrans = new MidtransController();
      $userType = 'frontend';
      return $midtrans->index($request, 'product purchase', $userType);
    } else if ($request['gateway'] == 'iyzico') {
      $iyzico = new  IyzipayController();

      return $iyzico->index($request, 'product purchase');
    } else if ($request['gateway'] == 'xendit') {
      $xendit = new XenditController();

      return $xendit->index($request, 'product purchase');
    } else if ($request['gateway'] == 'myfatoorah') {
      $myfatoorah = new MyFatoorahController();
      return $myfatoorah->index($request, 'product purchase');
    } else if ($request['gateway'] == 'perfect_money') {
      $perfect_money = new PerfectMoneyController();
      return $perfect_money->index($request, 'product purchase');
    } else if ($request['gateway'] == 'yoco') {
      $yoco = new YocoController();

      return $yoco->index($request, 'product purchase');
    } else if ($request['gateway'] == 'phonepe') {
      $phonepe = new PhonepeController();

      return $phonepe->index($request, 'product purchase');
    } else if ($request['gateway'] == 'toyyibpay') {
      $toyyibpay = new ToyyibpayController();

      return $toyyibpay->index($request, 'product purchase');
    } else if ($request['gateway'] == 'paytabs') {
      $paytabs = new PaytabsController();

      return $paytabs->index($request, 'product purchase');
    } else if ($request['gateway'] == 'authorize.net') {
      $authorizenet = new AuthorizeNetController();

      return $authorizenet->index($request, 'product purchase');
    } else if ($request['gateway'] == 'freshpay') {
      $freshpay = new FreshpayController();

      return $freshpay->index($request, 'product purchase');
    } else {
      $offline = new OfflineController();

      return $offline->index($request, 'product purchase');
    }
  }

  public function calculation(Request $request, $products)
  {
    $total = 0.00;

    foreach ($products as $key => $item) {
      $price = floatval($item['price']);
      $total += $price;
    }

    if ($request->session()->has('discount')) {
      $discountVal = $request->session()->get('discount');
    }

    $discount = isset($discountVal) ? floatval($discountVal) : 0.00;
    $subtotal = $total - $discount;
    $chargeId = $request->exists('shipping_method') ? $request['shipping_method'] : null;

    if (!is_null($chargeId)) {
      $shippingCharge = ShippingCharge::where('id', $request->shipping_method)->first();
      $shippingCharge = $shippingCharge->shipping_charge;
    } else {
      $shippingCharge = 0.00;
    }

    $taxData = Basic::select('product_tax_amount')->first();

    $taxAmount = floatval($taxData->product_tax_amount);
    $calculatedTax = $subtotal * ($taxAmount / 100);
    $grandTotal = $subtotal + floatval($shippingCharge) + $calculatedTax;

    $calculatedData = array(
      'total' => $total,
      'discount' => $discount,
      'subtotal' => $subtotal,
      'shippingCharge' => $request->exists('shipping_method') ? $shippingCharge : null,
      'tax' => $calculatedTax,
      'grandTotal' => $grandTotal
    );

    return $calculatedData;
  }

  public function storeData($productList, $arrData)
  {

    $orderInfo = ProductOrder::query()->create([
      'user_id' => Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null,
      'order_number' => uniqid(),
      'billing_name' => $arrData['billing_name'],
      'billing_phone' => $arrData['billing_phone'],
      'billing_email' => $arrData['billing_email'],
      'billing_address' => $arrData['billing_address'],
      'billing_city' => $arrData['billing_city'],
      'billing_state' => $arrData['billing_state'],
      'billing_country' => $arrData['billing_country'],
      'shipping_name' => $arrData['shipping_name'],
      'shipping_email' => $arrData['shipping_email'],
      'shipping_phone' => $arrData['shipping_phone'],
      'shipping_address' => $arrData['shipping_address'],
      'shipping_city' => $arrData['shipping_city'],
      'shipping_state' => $arrData['shipping_state'],
      'shipping_country' => $arrData['shipping_country'],

      'total' => $arrData['total'],
      'discount' => $arrData['discount'],
      'product_shipping_charge_id' => $arrData['productShippingChargeId'],
      'shipping_cost' => $arrData['shippingCharge'],
      'tax' => $arrData['tax'],
      'grand_total' => $arrData['grandTotal'],
      'currency_text' => $arrData['currencyText'],
      'currency_text_position' => $arrData['currencyTextPosition'],
      'currency_symbol' => $arrData['currencySymbol'],
      'currency_symbol_position' => $arrData['currencySymbolPosition'],
      'payment_method' => $arrData['paymentMethod'],
      'gateway_type' => $arrData['gatewayType'],
      'payment_status' => $arrData['paymentStatus'],
      'order_status' => $arrData['orderStatus'],
      'conversation_id' => isset($arrData['conversation_id']) ? $arrData['conversation_id'] : null,
      'attachment' => array_key_exists('attachment', $arrData) ? $arrData['attachment'] : null
    ]);

    foreach ($productList as $key => $item) {
      ProductPurchaseItem::create([
        'product_order_id' => $orderInfo->id,
        'product_id' => $key,
        'title' => $item['title'],
        'quantity' => intval($item['quantity'])
      ]);
    }

    return $orderInfo;
  }

  public function generateInvoice($orderInfo, $productList)
  {
    $fileName = $orderInfo->order_number . '.pdf';

    $data['orderInfo'] = $orderInfo;
    $data['productList'] = $productList;

    $directory = public_path('assets/file/invoices/product/');
    @mkdir($directory, 0755, true);

    $fileLocation = $directory . $fileName;

    $basicSetting = Basic::select('product_tax_amount', 'website_title', 'logo')->first();
    $adminInfo = Admin::select('email', 'username', 'phone', 'address', 'first_name as name')->first();

    $adminTimezone = now()->timezoneName;

    $invoiceData = [
      'logo' => $basicSetting->logo ?? null,
      'website_title' => $basicSetting->website_title ?? null,
      'order_number' => $orderInfo['order_number'] ?? null,
      'order_date' => isset($orderInfo['created_at'])
        ? now()->parse($orderInfo['created_at'])
        ->timezone($adminTimezone)
        ->format('Y-m-d')
        : null,
      'currency_symbol' => $orderInfo['currency_symbol'] ?? '',
      'currency_text' => $orderInfo['currency_text'] ?? '',
      'currency_symbol_position' => $orderInfo['currency_text'] ?? '',
      'billing_name' => $orderInfo['billing_name'] ?? '',
      'billing_phone' => $orderInfo['billing_phone'] ?? '',
      'billing_email' => $orderInfo['billing_email'] ?? '',
      'admin_name' => $adminInfo->name ?? $adminInfo->username,
      'admin_email' => $adminInfo->email ?? '',
      'admin_phone' => $adminInfo->phone ?? '',
      'admin_address' => $adminInfo->address ?? '',
      'sub_total' => $orderInfo['total'] ?? 0.00,
      'discount' => $orderInfo['discount'] ?? 0.00,
      'tax_amount' => $orderInfo['tax'] ?? 0.00,
      'payment_method' => $orderInfo['payment_method'] ?? '',
      'tax_percentage' => $basicSetting->product_tax_amount ?? 0,
      'grand_total' => $orderInfo['grand_total'] ?? 0.00,
      'customer_paid' => $orderInfo['grand_total'] ?? 0.00,
      'products' => $productList ?? [],
    ];

    $html = view('pdf.product', compact('invoiceData'))->render();

    // Initialize mPDF with RTL and UTF-8 support
    $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4',
      'autoScriptToLang' => true,
      'autoLangToFont' => true,
    ]);
    // Write HTML content to PDF
    $mpdf->WriteHTML($html);
    $mpdf->Output($fileLocation, \Mpdf\Output\Destination::FILE);

    return $fileName;
  }

  public function prepareMail($orderInfo)
  {
    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'product_order')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $info = Basic::select('website_title')->first();

    $customerName = $orderInfo->billing_first_name . ' ' . $orderInfo->billing_last_name;
    $orderNumber = $orderInfo->order_number;
    $websiteTitle = $info->website_title;

    if (Auth::guard('web')->check()) {
      $orderLink = '<p>' . __('Order Details') . ': <a href="' . route('user.order.details', $orderInfo->id) . '">' . __('Click Here') . '</a></p>';
    } else {
      $orderLink = '';
    }

    // replacing with actual data
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{order_number}', $orderNumber, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{order_link}', $orderLink, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $orderInfo->billing_email;

    $mailData['invoice'] = public_path('assets/file/invoices/product/') . $orderInfo->invoice;

    BasicMailer::sendMail($mailData);

    return;
  }

  public function complete($type = null)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['payVia'] = $type;

    return view('frontend.payment.success', $queryResult);
  }

  public function cancel(Request $request)
  {
    $notification = array('message' => __('Something went wrong'), 'alert-type' => 'error');
    return redirect()->route('shop.products')->with($notification);
  }
}
