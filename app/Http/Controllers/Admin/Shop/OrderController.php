<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Exports\ProductOrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\ProductOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
  public function orders(Request $request)
  {
    $orderNumber = $paymentStatus = $orderStatus = null;

    if ($request->filled('order_no')) {
      $orderNumber = $request['order_no'];
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request['payment_status'];
    }
    if ($request->filled('order_status')) {
      $orderStatus = $request['order_status'];
    }

    $orders = ProductOrder::query()->when($orderNumber, function ($query, $orderNumber) {
      return $query->where('order_number', 'like', '%' . $orderNumber . '%');
    })
      ->when($paymentStatus, function ($query, $paymentStatus) {
        return $query->where('payment_status', '=', $paymentStatus);
      })
      ->when($orderStatus, function ($query, $orderStatus) {
        return $query->where('order_status', '=', $orderStatus);
      })
      ->orderByDesc('id')
      ->paginate(10);


    return view('admin.shop.order.index', compact('orders'));
  }

  public function updatePaymentStatus(Request $request, $id)
  {
    $order = ProductOrder::findOrFail($id);

    if ($request['payment_status'] == 'pending') {
      if ($order->payment_status == 'rejected' && $order->order_status != 'rejected') {
        $this->changeProductQuantity($order, 'decrease');
      }

      $order->update([
        'payment_status' => 'pending'
      ]);

      $statusMsg = __('Your payment is pending') . '.';
    } else if ($request['payment_status'] == 'completed') {
      if ($order->payment_status == 'rejected' && $order->order_status != 'rejected') {
        $this->changeProductQuantity($order, 'decrease');
      }

      $order->update([
        'payment_status' => 'completed'
      ]);

      $statusMsg = __('Your payment is complete') . '.';

      // generate an invoice in pdf format

      $items = $order->item()->get();

      $items->map(function ($item) {
        $product = $item->productInfo()->first();
        $item['price'] = $product->current_price * $item->quantity;
      });

      $productList = $items;

      $purchaseProcess = new PurchaseProcessController();

      $invoice = $purchaseProcess->generateInvoice($order , $productList);

      // then, update the invoice field info in database
      $order->update([
        'invoice' => $invoice
      ]);

      //add balance to admin revenue
      $adminData['life_time_earning'] =  $order['grand_total'];
      if ($order['seller_id'] != null) {
        $adminData['total_profit'] =  $order['grand_total'];
      } else {
        $adminData['total_profit'] =  $order['grand_total'];
      }
      

      //storeTransaction
      $order['transaction_type'] = 7;
      storeTransaction($order);
      storeEarnings($adminData);
    } else {
      if ($order->payment_status != 'rejected' && $order->order_status != 'rejected') {
        $this->changeProductQuantity($order, 'increase');
      }

      $order->update([
        'payment_status' => 'rejected'
      ]);

      $statusMsg = __('Your payment has been rejected') . '.';
    }

    $mailData = [];

    if (isset($invoice)) {
      $mailData['invoice'] = public_path('assets/file/invoices/product/') . $invoice;
    }

    $mailData['subject'] = __('Notification of payment status');

    $mailData['body'] = __('Hi') . ' ' . $order->billing_name . ',<br/><br/>' . __('This email is to notify the payment status of your product purchase') . ' ' . $statusMsg;


    $mailData['recipient'] = $order->billing_email;

    $mailData['sessionMessage'] = __('Payment status updated & mail has been sent successfully') . '!';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function updateOrderStatus(Request $request, $id)
  {
    $order = ProductOrder::find($id);

    if ($request['order_status'] == 'pending') {
      if ($order->order_status == 'rejected' && $order->payment_status != 'rejected') {
        $this->changeProductQuantity($order, 'decrease');
      }

      $order->update([
        'order_status' => 'pending'
      ]);

      $statusMsg = __('We want to inform you that, your order') . ' #' . $order->order_number . ' ' .__('is pending') . '.';
    } else if ($request['order_status'] == 'processing') {
      if ($order->order_status == 'rejected' && $order->payment_status != 'rejected') {
        $this->changeProductQuantity($order, 'decrease');
      }

      $order->update([
        'order_status' => 'processing'
      ]);

      $statusMsg = __('We want to inform you that, your order') . ' #' . $order->order_number . ' ' . __('is now processing') .'.';
    } else if ($request['order_status'] == 'completed') {
      if ($order->order_status == 'rejected' && $order->payment_status != 'rejected') {
        $this->changeProductQuantity($order, 'decrease');
      }

      $order->update([
        'order_status' => 'completed'
      ]);

      $statusMsg = __('We want to inform you that, your order') . ' #' . $order->order_number . ' ' . __('has been completed') . '.<br/><br/>' . __('Thank you') ;

    } else {
      if ($order->order_status != 'rejected' && $order->payment_status != 'rejected') {
        $this->changeProductQuantity($order, 'increase');
      }

      $order->update([
        'order_status' => 'rejected'
      ]);

      $statusMsg = __('We want to inform you that, your order') . ' #' . $order->order_number . ' ' . __('has been rejected') . '.';

    }

    $mailData['subject'] = __('Notification of order status');

    $mailData['body'] = __('Hi') . ' ' . $order->billing_name . ',<br/><br/>' . __('This email is to notify the order status of your purchased item') . ' ' . $statusMsg;


    $mailData['recipient'] = $order->billing_email;

    $mailData['sessionMessage'] = __('Order status updated & mail has been sent successfully') . '!';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function changeProductQuantity($productOrder, $changeType)
  {
    $purchaseItems = $productOrder->item()->get();

    foreach ($purchaseItems as $purchaseItem) {
      $product = $purchaseItem->productInfo()->first();

      if ($product->product_type == 'physical') {
        if ($changeType == 'increase') {
          $product->update([
            'stock' => $product->stock + $purchaseItem->quantity
          ]);
        } else {
          $product->update([
            'stock' => $product->stock - $purchaseItem->quantity
          ]);
        }
      }
    }
  }

  public function show($id)
  {
    $order = ProductOrder::findOrFail($id);

    $information['details'] = $order;

    $information['tax'] = Basic::select('product_tax_amount')->first();

    $items = $order->item()->get();

    $items->map(function ($item) {
      $product = $item->productInfo()->first();
      $item['featured_image'] = $product->featured_image;
      $item['current_price'] = $product->current_price;
    });

    $information['items'] = $items;

    return view('admin.shop.order.details', $information);
  }

  public function destroy($id)
  {
    $order = ProductOrder::find($id);

    // delete the attachment
    @unlink(public_path('assets/file/attachments/product/') . $order->attachment);

    // delete the invoice
    @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

    // delete purchase infos of this order
    $items = $order->item()->get();

    if (count($items) > 0) {
      foreach ($items as $item) {
        $item->delete();
      }
    }

    $order->delete();

    return redirect()->back()->with('success', __('Order deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $order = ProductOrder::find($id);

      // delete the attachment
      @unlink(public_path('assets/file/attachments/product/') . $order->attachment);

      // delete the invoice
      @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

      // delete purchase infos of this order
      $items = $order->item()->get();

      if (count($items) > 0) {
        foreach ($items as $item) {
          $item->delete();
        }
      }

      $order->delete();
    }

    $request->session()->flash('success', __('Orders deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }


  public function report(Request $request)
  {

    $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
    $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

    $from = $to = $paymentGateway = $paymentStatus = $orderStatus = null;

    if ($request->filled('payment_gateway')) {
      $paymentGateway = $request->payment_gateway;
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request->payment_status;
    }
    if ($request->filled('order_status')) {
      $orderStatus = $request->order_status;
    }

    if ($request->filled('from') && $request->filled('to')) {
      $from = Carbon::parse($request->from)->toDateString();
      $to = Carbon::parse($request->to)->toDateString();

      $records = ProductOrder::query()
        ->whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->when($paymentGateway, function (Builder $query, $paymentGateway) {
          return $query->where('payment_method', '=', $paymentGateway);
        })
        ->when($paymentStatus, function (Builder $query, $paymentStatus) {
          return $query->where('payment_status', '=', $paymentStatus);
        })
        ->when($orderStatus, function (Builder $query, $orderStatus) {
          return $query->where('order_status', '=', $orderStatus);
        })
        ->select('order_number', 'billing_name', 'billing_email', 'billing_phone', 'billing_address', 'billing_city', 'billing_state', 'billing_country', 'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_country', 'total', 'discount', 'product_shipping_charge_id', 'shipping_cost', 'tax', 'grand_total', 'currency_text', 'currency_text_position', 'payment_method', 'payment_status', 'order_status', 'created_at')
        ->orderByDesc('id');

      $collection_1 = $this->manipulateCollection($records->get());
      Session::put('product_orders', $collection_1);

      $collection_2 = $this->manipulateCollection($records->paginate(10));
      $queryResult['orders'] = $collection_2;
    } else {
      Session::put('product_orders', null);
      $queryResult['orders'] = [];
    }

    return view('admin.shop.order.report', $queryResult);
  }

  public function manipulateCollection($orders)
  {
    $orders->map(function ($order) {
      // shipping charge title
      $order['shippingMethod'] = $order->shippingMethod()->pluck('title')->first();

      // format created_at date
      $dateObj = Carbon::parse($order->created_at);
      $order['createdAt'] = $dateObj->format('M d, Y');
    });

    return $orders;
  }

  public function exportReport()
  {
    if (Session::has('product_orders')) {
      $productOrders = Session::get('product_orders');

      if (count($productOrders) == 0) {
        Session::flash('warning', __('No order found to export') . '!');

        return redirect()->back();
      } else {
        return Excel::download(new ProductOrdersExport($productOrders), 'product-orders.csv');
      }
    } else {
      Session::flash('error', __('There has no order to export') . '.');

      return redirect()->back();
    }
  }
}
