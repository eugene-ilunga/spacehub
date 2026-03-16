<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Http\Helpers\UploadFile;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\Space;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

    $gatewayId = $request->gateway;
    $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);

    if ($offlineGateway->has_attachment == 1) {
      $rules = [
        'attachment.' . $gatewayId => [
          'required',
          new ImageMimeTypeRule()
        ]
      ];

      $messages = [
        'attachment.' . $gatewayId . '.required' => __('Please attach your payment receipt') . '.'
      ];

      $validator = Validator::make($request->only('attachment'), $rules, $messages);

      if ($validator->fails()) {
        // Set a custom session variable
        session()->flash('offline_validation_errors', true);
        return redirect()->back()->withErrors($validator->errors())->withInput();
      }
    }
    // validation end

    if ($paymentFor == 'space') {
      $directory = './assets/img/attachments/space/';
    } else {
      $directory = './assets/img/attachments/';
    }

    // store attachment in local storage
    if ($request->hasFile('attachment')) {
      $files = $request->file('attachment');
      if (is_array($files)) {
        foreach ($files as $file) {
          $attachmentName = UploadFile::store($directory, $file);
          // Handle the stored file name as needed
        }
      } else {
        $attachmentName = UploadFile::store($directory, $files);
        // Handle the stored file name as needed
      }
    }
    $currencyInfo = $this->getCurrencyInfo();


    $data['currencyText'] = $currencyInfo->base_currency_text;
    $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
    $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
    $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
    $data['paymentMethod'] = $offlineGateway->name;
    $data['gatewayType'] = 'offline';
    $data['paymentStatus'] = 'pending';
    $data['bookingStatus'] = 'pending';
    $data['receiptName'] = $request->exists('attachment') ? $attachmentName : null;


    // store space booking information in database
    $selected_service = Space::where('id', $data['spaceId'])->select('seller_id')->first();

    if ($selected_service->seller_id != 0) {
      $data['seller_id'] = $selected_service->seller_id;
    } else {
      $data['seller_id'] = null;
    }

    $orderProcess = new OrderProcessController();

    // store service order information in database
    $booking = $orderProcess->storeData($data);

    // Process vendor payment
    $vendorData = [
      'sub_total' => $booking->sub_total ?? 0,
      'seller_id' => $booking->seller_id ?? null
    ];
    storeAmountToSeller($vendorData);

    // Process admin earnings
    $adminData = [
      'life_time_earning' => $booking->grand_total ?? 0,
      'total_profit' => $booking->seller_id ? $booking->tax : $booking->grand_total
    ];
    storeEarnings($adminData);

    //store Transaction data
    $booking['transaction_type'] = 1;
    storeTransaction($booking);

    $spaceSlug = $data['slug'];

    return redirect()->route('service.place_order.complete', ['slug' => $spaceSlug, 'via' => 'offline']);
  }
}
