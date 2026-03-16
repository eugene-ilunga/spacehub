<?php

namespace App\Http\Controllers\Admin\SpaceFeatureManagement;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\FeatureCharge;
use App\Models\Language;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Space;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;

class FeatureManagementController extends Controller
{
  public function index(Request $request)
  {

    $bookingNumber = $paymentStatus = $bookingStatus = $seller = null;

    if ($request->filled('booking_number')) {
      $bookingNumber = $request['booking_number'];
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request['payment_status'];
    }
    if ($request->filled('feature_status')) {
      $bookingStatus = $request['feature_status'];
    }
    if ($request->filled('seller')) {
      $seller = $request['seller'];
    }
    $bookings = SpaceFeature::query()

      ->when($bookingNumber, function (Builder $query, $bookingNumber) {
        return $query->where('booking_number', 'like', '%' . $bookingNumber . '%');
      })
      ->when($paymentStatus, function (Builder $query, $paymentStatus) {
        return $query->where('payment_status', '=', $paymentStatus);
      })
      ->when($bookingStatus, function (Builder $query, $bookingStatus) {
        return $query->where('booking_status', '=', $bookingStatus);
      })
      ->when($seller, function (Builder $query, $seller) {
        if ($seller == 'admin') {
          $seller_id = 0;
        } else {
          $seller_id = $seller;
        }
        return $query->where('seller_id', '=', $seller_id);
      })
      ->orderByDesc('id')
      ->paginate(10);

    $language = Language::query()->where('is_default', '=', 1)->first();
    $bookings->map(function ($order) use ($language) {
      $space = $order->space()->with('spaceContents')->first();
      if ($space) {
        $titleAndSlug = $space->spaceContents->where('language_id', $language->id)->first();
        if ($titleAndSlug) {
          $order['space_title'] = $titleAndSlug->title;
          $order['space_slug'] = $titleAndSlug->slug;
        }
      }
      $order['featureCharge'] = $order->featureCharge()->first();
      $order['sellerInfo'] = $order->seller()->first();
    });

    $sellers = Seller::select('id', 'username')->where('id', '!=', 0)->get();

    return view('admin.featured-management.index', compact('bookings', 'sellers'));
  }
  public function updateBookingStatus(Request $request, $id)
  {

    $order = SpaceFeature::query()->findOrFail($id);
    $numberOfDays = FeatureCharge::select('day')
      ->where('id', $order->feature_charge_id)
      ->first();

    $bs = Basic::first();

    if ($order->payment_status == 'completed') {
      if ($request['booking_status'] == 'approved') {
        $order->update([
          'booking_status' => 'approved',
          'start_date' => Carbon::now(),
          'end_date' => Carbon::now()->addDays($numberOfDays->day),
        ]);

        // Update the is_featured column in the spaces table
        $space = Space::find($order->space_id);
        if ($space) {
          $space->is_featured = 1;
          $space->save();
        }

        $defaultLang = getAdminLanguage();

        // Get space content details
        $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
          ['space_id', $order->space_id],
          ['language_id', $defaultLang->id]
        ])->first();

        //set url and space title
        $url = $spaceContent ? route('space.details', [
          'slug' => $spaceContent->slug,
          'id' => $order->space_id
        ]) : null;

        $spaceName = $spaceContent ? $spaceContent->title : '';
        // Get vendor info
        $vendorInfo = $this->getVendorDetails($order->seller_id);

        $vendorName = $vendorInfo->seller_name;

        $featureInvoiceData = [
          'name'      => $vendorInfo->seller_name,
          'username'  => $vendorInfo->username,
          'email'     => $vendorInfo->email,
          'phone'     => $vendorInfo->phone,
          'order_id'  => $order->booking_number,
          'base_currency_text_position'  => $bs->base_currency_text_position,
          'base_currency_text'  => $bs->base_currency_text,
          'base_currency_symbol'  => $bs->base_currency_symbol,
          'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
          'amount'  => $order->total,
          'payment_method'  => $order->payment_method,
          'space_title'  => $spaceName,
          'start_date'  => Carbon::parse($order->start_date)->format('Y-m-d'),
          'expire_date'  => Carbon::parse($order->end_date)->format('Y-m-d'),
          'website_title'  => $bs->website_title,
          'logo'  => $bs->logo,
          'day'  => $order->days,
          'purpose'  => 'feature',
        ];

        // Generate and update invoice
        $invoice = $this->makeInvoice($featureInvoiceData);
        $order->update(['invoice' => $invoice]);

        // Send payment confirmation email
        SpaceFeature::sendPaymentStatusEmail(
          $order,
          $url,
          $spaceName,
          $vendorName,
          $bs->website_title,
          'featured_request_payment_approved',
          $order->invoice
        );
      
        // Store transaction
        if ($order->gateway_type == 'offline') {

          $transaction_data = [
            'order_number' => $order->booking_number,
            'transaction_type' => 6,
            'user_id' => null,
            'seller_id' => $order->seller_id,
            'payment_status' => 'completed',
            'payment_method' => $order->payment_method,
            'grand_total' => $order->total,
            'sub_total' => $order->total,
            'tax' => null,
            'gateway_type' => 'offline',
            'currency_symbol' => $order->currency_symbol,
            'currency_symbol_position' => $order->currency_symbol_position
          ];
          storeTransaction($transaction_data);

          // Store earnings
          storeEarnings([
            'life_time_earning' => $order->total,
            'total_profit' => $order->total
          ]);
        }

        $request->session()->flash('success', __('Booking status updated successfully') . '!');
      } elseif ($request['booking_status'] == 'pending') {
        $order->update([
          'booking_status' => 'pending',
        ]);
        $request->session()->flash('success', __('Booking status updated successfully') . '!');
      } else {

        $order->update([
          'booking_status' => 'rejected',
        ]);

        $transaction = Transaction::where('booking_number', $order->booking_number)->first();
        $bs = Basic::first();

        if ($order->gateway_type != 'offline') {
          if ($transaction) {
            $featureCharge = $transaction->grand_total ?? 0;
            $transaction->after_balance = max(0, $transaction->after_balance - $featureCharge);
            $transaction->save();

            $bs->life_time_earning = max(0, $bs->life_time_earning - $featureCharge);
            $bs->total_profit = max(0, $bs->total_profit - $featureCharge);
            $bs->save();
          }
        }

        // also update the space's `is_featured` status
        $space = Space::find($order->space_id);
        if ($space) {
          $space->is_featured = 0; 
          $space->save();
        }

        $defaultLang = getAdminLanguage();

        // Get space content details
        $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
          ['space_id', $order->space_id],
          ['language_id', $defaultLang->id]
        ])->first();

        //set url and space title
        $url = $spaceContent ? route('space.details', [
          'slug' => $spaceContent->slug,
          'id' => $order->space_id
        ]) : null;

        $spaceName = $spaceContent ? $spaceContent->title : null;

        // Get vendor info
        $vendorInfo = $this->getVendorDetails($order->seller_id);

        $vendorName = $vendorInfo->seller_name;

        // Send payment confirmation email
        SpaceFeature::sendPaymentStatusEmail(
          $order,
          $url,
          $spaceName,
          $vendorName,
          $bs->website_title,
          'featured_request_payment_rejected',
          null
        );

        $request->session()->flash('success', __('Booking status updated successfully') . '!');
      }
    } else {
      $request->session()->flash('error', __('Payment is not completed') . ', ' . __('cannot update booking status') . '.');
    }

    return redirect()->back();
  }


  public function updatePaymentStatus(Request $request, $id)
  {
    $order = SpaceFeature::query()->findOrFail($id);
    $language = getAdminLanguage();
    $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
      ['space_id', $order->space_id],
      ['language_id', $language->id]
    ])->first();

    if (!empty($spaceContent)) {
      $url = route('space.details', ['slug' => $spaceContent->slug, 'id' => $order->space_id]);
      $spaceName = $spaceContent->title;
    } else {
      $url = null;
      $spaceName = null;
    }

    // Get the website title info from db
    $info = Basic::select('website_title', 'base_currency_symbol', 'base_currency_symbol_position')->first();
    $websiteTitle = $info->website_title;
    $vendorName = SellerInfo::where('seller_id', $order->seller_id)->first()->name;

    // Handle payment status update
    if ($request['payment_status'] == 'completed') {
      // Update payment status
      $order->update(['payment_status' => 'completed']);

      $request->session()->flash('success', __('Payment status updated successfully') . '!');
    } elseif ($request['payment_status'] == 'pending') {
      $order->update(['payment_status' => 'pending']);
      $request->session()->flash('success', __('Payment status updated successfully') . '!');
    } elseif ($request['payment_status'] == 'rejected') {
      // Update payment status
      $order->update(['payment_status' => 'rejected']);
      $request->session()->flash('success', __('Payment status updated successfully') . '!');
    }

    return redirect()->back();
  }

  public function destroy(Request $request)
  {
    $this->deleteBooking($request->id);

    return redirect()->back()->with('success', __('Booking record deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteBooking($id);
    }

    $request->session()->flash('success', __('Booking records deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  // order deletion code
  public function deleteBooking($id)
  {
    $booking = SpaceFeature::query()->find($id);

    // delete the invoice
    @unlink(public_path('assets/front/img/feature/receipt/' . $booking->attachment));

    // delete s-order
    $booking->delete();
  }

  public function generateInvoice($requestInfo)
  {
    $fileName = $requestInfo->order_number . '.pdf';

    $data['orderInfo'] = $requestInfo;

    $directory = public_path('assets/file/invoices/space/featured/');
    // Create the directory if it doesn't exist
    if (!is_dir($directory)) {
      mkdir($directory, 0775, true);
    }

    $fileLocated = $directory . $fileName;


    PDF::loadView('frontend.space.featured.invoice', $data)->save($fileLocated);

    return $fileName;
  }

  /**
   * Common function to send payment status email.
   */
  protected function sendPaymentStatusEmail($order, $url, $spaceName, $vendorName, $websiteTitle, $mailType, $invoice = null)
  {
    // Get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', $mailType)->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // Replace placeholders with actual data
    $mailBody = str_replace('{space_title}', "<a href=" . $url . ">$spaceName</a>", $mailBody);
    $mailBody = str_replace('{amount}', symbolPrice($order->total), $mailBody);
    $mailBody = str_replace('{vendor_name}', $vendorName, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{request_id}', $order->booking_number, $mailBody);

    $mailData['body'] = $mailBody;
    $mailData['recipient'] = $order->seller_email;

    // Attach invoice if payment is completed
    if ($mailType == 'featured_request_payment_approved' && !is_null($invoice)) {
      $mailData['invoice'] = public_path('assets/file/invoices/space/featured/') . $invoice;
    }

    BasicMailer::sendMail($mailData);
  }

  protected function getVendorDetails($sellerId)
  {
    return Seller::select('sellers.*', 'seller_infos.name as seller_name')
      ->leftJoin('seller_infos', function ($join) use ($sellerId) {
        $join->on('sellers.id', '=', 'seller_infos.seller_id')
          ->where('sellers.id', $sellerId);
      })
      ->where('sellers.id', '=', $sellerId)
      ->first();
  }
}
