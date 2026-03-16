<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\IyzipayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MidtransController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MyFatoorahController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaypalController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaytabsController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PerfectMoneyController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PhonepeController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\StripeController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\ToyyibpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\XenditController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\YocoController;
use App\Models\BasicSettings\Basic;
use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;

use App\Models\BasicSettings\MailTemplate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Earning;
use PDF;

class OrderController extends Controller
{

  public function index()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $information['breadcrumb'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    $information['orders'] = \App\Models\Shop\ProductOrder::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'desc')->get();

    return view('frontend.user.order.index', $information);
  }

  public function details($id)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $language = $misc->getLanguage();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $order = ProductOrder::query()->find($id);
    if ($order) {
      if ($order->user_id != Auth::guard('web')->user()->id) {
        return redirect()->route('user.dashboard');
      }

      $queryResult['order'] = $order;

      $queryResult['tax'] = Basic::select('product_tax_amount')->first();

      $items = $order->item()->get();

      $items->map(function ($item) use ($language) {
        $product = $item->productInfo()->first();
        $item['price'] = $product->current_price;
        $item['productType'] = $product->product_type;
        $item['inputType'] = $product->input_type;
        $item['link'] = $product->link;
        $content = $product->content()->where('language_id', $language->id)->first();

        $item['productTitle'] = $content ? $content->title : '';
        $item['slug'] =
          $content ? $content->slug : '';
      });

      $queryResult['items'] = $items;

      return view('frontend.user.order.details', $queryResult);
    } else {
      return view('errors.404');
    }
  }

  public function download($id)
  {
    $product = Product::findOrFail($id);
    $filePath = public_path('assets/file/products/' . $product->file);

    // Get the file name from the file path
    $fileName = pathinfo($filePath, PATHINFO_FILENAME);

    // Return a download response
    return response()->download($filePath, $fileName);
  }

}
