<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\FrontEnd\PaymentGateway\AuthorizeNetController;
use App\Http\Controllers\FrontEnd\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\PaymentGateway\FreshpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\IyzicoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MidtransController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MyFatoorahController;
use App\Http\Controllers\FrontEnd\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PayPalController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytabsController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PerfectMoneyController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PhonePeController;
use App\Http\Controllers\FrontEnd\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\StripeController;
use App\Http\Controllers\FrontEnd\PaymentGateway\ToyyibpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\XenditController;
use App\Http\Controllers\FrontEnd\PaymentGateway\YocoController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\BookTourRequest;
use App\Http\Requests\GetQuoteFormRequest;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\BookForTour;
use App\Models\CouponUsage;
use App\Models\Form;
use App\Models\GetQuote;
use App\Models\GlobalDay;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceContent;
use App\Models\SpaceCoupon;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;


class OrderProcessController extends Controller
{
  public function index(Request $request, $slug)
  {

    $coupon_code = null;
    $space = space::where('id', $request->space_id)->select('id', 'space_type')->firstOrFail();

    if (session()->has('multiDayBookingData')) {
      $bookingData = session('multiDayBookingData');
    }
    if (session()->has('hourlyBookingData')) {
      $bookingData = session('hourlyBookingData');
    }
    if (session()->has('timeSlotBookingData')) {
      $bookingData = session('timeSlotBookingData');
    }

    // Format booking date to 'Y-m-d'
    $bookingDate = isset($bookingData['booking_date'])
      ? Carbon::parse($bookingData['booking_date'])->format('Y-m-d')
      : null;

    if ($space->space_type === 1 && $bookingDate) {
      $timeSlots = $this->getAvailableTimeSlots($bookingDate, $request->space_id, $request->seller_id);

      if ($timeSlots->isEmpty()) {
        return redirect()->route('space.index')
          ->with('error', __('No available time slots found for the selected date') . '.');
      }
    }

    // Store non-file data in the session
    $checkoutInfo = $request->except('attachment');

    session()->put('checkout_info', $checkoutInfo);

    //  Here define validation rules for required fields
    $rules = [
      'booking_date' => 'required|date',
      'time_slot_id' => 'required',
      'space_id' => 'required',
      'seller_id' => 'required',
      'start_time' => 'required',
      'end_time' => 'required',
      'end_time' => 'required',
    ];

    $dataToValidate = $request->only(array_keys($rules));

    // Create a validator instance with the validation rules and extracted request data
    $validator = Validator::make($dataToValidate, $rules);
    // Initialize discount amount
    $discount_amount = 0.00;

    // Check if a coupon code exists in the session
    if (session()->has('couponCode')) {
      $coupon_code = session('couponCode');

      // Retrieve the coupon using the coupon code
      $coupon = SpaceCoupon::where('code', $coupon_code)->first();

      if ($coupon) {
        // Retrieve the coupon usage record
        $couponUsage = CouponUsage::where('coupon_id', $coupon->id)
          ->where([
            ['coupon_code', $coupon->code],
            ['user_id', Auth::guard('web')->user()->id],
          ])
          ->first();

        // Set the discount amount if the coupon usage record exists
        if ($couponUsage) {
          $discount_amount = $couponUsage->discount_amount;
        }
      }
    }

    $grandTotal = $bookingData['subtotal'] + $bookingData['tax'];

    // Ensure grand total is not negative
    if ($grandTotal < 0) {
      $grandTotal = 0.00;
    }

    // only for mutiday rental
    if (isset($space) && !empty($space->space_type) && $space->space_type == 3) {
      $request['booking_date'] = Carbon::parse($bookingData['start_date'])->format('Y-m-d');
    }


    $allData = [
      'userId'        => Auth::guard('web')->user()->id ?? null,
      'orderNumber'   => uniqid(),
      'firstName'     => $request['first_name'] ?? null,
      'lastName'      => $request['last_name'] ?? null,
      'customerPhone' => $request['customer_phone'] ?? null,
      'numberOfGuest' => $bookingData['number_of_guest'] ?? null,
      'grandTotal'    => $grandTotal ?? null,
      'discount'    => $discount_amount,
      'subTotal'      => $bookingData['subtotal'] ?? null,
      'spaceRent'     => $bookingData['rent'] ?? null,
      'serviceTotal'     => $bookingData['service_total'] ?? null,
      'tax'           => $bookingData['tax'] ?? null,
      'taxPercentage' => $bookingData['tax_percentage'] ?? null,
      'emailAddress'  => $request['email_address'] ?? null,
      'spaceId'       => $bookingData['space_id'] ?? null,
      'timeSlotId'    => $request['time_slot_id'] ?? null,
      'startTime'     => $bookingData['start_time'] ?? null,
      'endTime'       => $bookingData['end_time'] ?? null,
      'endTimeWithoutInterval'       => $bookingData['end_time_without_interval'] ?? null,
      'sellerId'      => $bookingData['seller_id'] ?? null,
      'numberOfDay'   => $bookingData['number_of_day'] ?? null,
      'serviceStageInfo' => json_encode($bookingData['space_services_with_subservice'] ?? []),
      'otherServiceInfo' => json_encode($bookingData['space_services_without_subservice'] ?? []),
      'subServiceIds'     => json_encode($bookingData['sub_service_ids'] ?? []),
    ];

    if (isset($space) && !empty($space->space_type) && $space->space_type == 3) {
      $allData['startDate'] = isset($bookingData['start_date']) ? Carbon::parse($bookingData['start_date'])->format('Y-m-d') : null;
      $allData['endDate']   = isset($bookingData['end_date']) ? Carbon::parse($bookingData['end_date'])->format('Y-m-d') : null;
      $allData['bookingDate']   = isset($bookingData['start_date']) ? Carbon::parse($bookingData['start_date'])->format('Y-m-d') : null;
    }
    // Conditionally add startTime, endTime, sellerId, fromHour, and hours based on space type 2
    if (isset($space) && !empty($space->space_type) && $space->space_type == 2) {
      $allData['customHour']    = $bookingData['custom_hour'] ?? null;
      $allData['totalHour']     = $bookingData['total_hour'] ?? null;  // with interval time
      $allData['bookingDate']   = isset($bookingData['booking_date']) ? Carbon::parse($bookingData['booking_date'])->format('Y-m-d') : null;
    }
    if (isset($space) && !empty($space->space_type) && $space->space_type == 1) {
      $allData['bookingDate']   = isset($bookingData['booking_date']) ? Carbon::parse($bookingData['booking_date'])->format('Y-m-d') : null;
    }
    $allData['slug'] = $slug ?? null;

    // redirect to respective payment-gateway controller
    if (!$request->exists('gateway')) {
      $request->session()->flash('error', __('Please select a payment method') . '.');
      return redirect()->back()->withInput();
    } else if ($request['gateway'] == 'paypal') {
      $paypal = new PayPalController();
      return $paypal->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'phonepe') {
      $phonepe = new PhonePeController();

      return $phonepe->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'toyyibpay') {
      $toyyibpay = new ToyyibpayController();

      return $toyyibpay->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'xendit') {
      $xendit = new XenditController();

      return $xendit->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'yoco') {
      $yoco = new YocoController();

      return $yoco->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();
      return $instamojo->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();
      return $paystack->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();
      return $flutterwave->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();
      return $razorpay->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();
      return $mercadopago->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();
      return $mollie->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'stripe') {

      $stripe = new StripeController();
      return $stripe->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();
      return $paytm->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'authorize.net') {
      $authorizenet = new AuthorizeNetController();
      return $authorizenet->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'iyzico') {
      $iyzico = new IyzicoController();

      return $iyzico->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'midtrans') {
      $midtrans = new MidtransController();

      return $midtrans->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'myfatoorah') {
      $myfatoorah = new MyFatoorahController();

      return $myfatoorah->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'paytabs') {
      $paytabs = new PaytabsController();

      return $paytabs->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'perfect_money') {
      $perfectMoney = new PerfectMoneyController();
      return $perfectMoney->index($request, $allData, 'space');
    } else if ($request['gateway'] == 'freshpay') {
      $freshpay = new FreshpayController();

      return $freshpay->index($request, $allData, 'space');
    } else {
      $offline = new OfflineController();
      return $offline->index($request, $allData, 'space');
    }
  }

  public function storeData($data)
  {

    $space = Space::where('id', $data['spaceId'])->select('space_type')->firstOrfail();
    $userId = is_array($data['userId']) ? implode(',', $data['userId']) : $data['userId'];
    if (isset($data['bookedBy']) && $data['bookedBy'] == 'admin') {
      $customerName = $data['customerName'];
    } else {
      $customerName = ($data['firstName'] ?? '') . ' ' . ($data['lastName'] ?? '');
    }

    // Raw input from form (from admin timezone)
    $adminTimeZone = now()->timezoneName;
    $timeFormatSetting = Basic::query()->value('time_format');

    $targetFormat = $timeFormatSetting === '12h' ? 'h:i A' : 'H:i';

    if ($space->space_type == 1) {
      $inputStartTime = $data['startTime'] ? Carbon::createFromFormat('H:i:s', $data['startTime'], 'UTC')->setTimezone($adminTimeZone)->format($targetFormat) : null;
      $inputEndTime =  $data['endTime'] ? Carbon::createFromFormat('H:i:s', $data['endTime'], 'UTC')->setTimezone($adminTimeZone)->format($targetFormat) : null;
    } elseif ($space->space_type == 2) {

      // already this value is in the admin timezone and 24 hour format
      $inputStartTime = $data['startTime'] ? $data['startTime'] : null;
      $inputEndTime = $data['endTime'] ? $data['endTime'] : null;
      $endTimeWithoutInterval = $data['endTimeWithoutInterval'] ? $data['endTimeWithoutInterval'] : null;
    } else {
      $inputStartTime = null;
      $inputEndTime = null;
      $endTimeWithoutInterval = null;
    }

    $orderInfo = SpaceBooking::query()->create([
      'user_id'                  => $userId,
      'seller_id'                => $data['seller_id'] ?? null,
      'booking_number'           => $data['orderNumber'] ?? null,
      'number_of_guest'          => $data['numberOfGuest'] ?? null,
      'customer_name'            => $customerName,
      'customer_phone'           => $data['customerPhone'] ?? null,
      'customer_email'           => $data['emailAddress'] ?? null,
      'space_id'                 => $data['spaceId'] ?? null,
      'service_stage_info'       => $data['serviceStageInfo'] ?? null,
      'sub_service_info'         => $data['subServiceIds'] ?? null,
      'other_service_info'       => $data['otherServiceInfo'] ?? null,
      'space_rent_price'         => $data['spaceRent'] ?? null,
      'sub_total'                => $data['subTotal'] ?? null,
      'service_total'            => $data['serviceTotal'] ?? null,
      'tax_percentage'           => $data['taxPercentage'] ?? null,
      'tax'                      => $data['tax'] ?? null,
      'grand_total'              => $data['grandTotal'] ?? null,
      'currency_text'            => $data['currencyText'] ?? null,
      'currency_text_position'   => $data['currencyTextPosition'] ?? null,
      'currency_symbol'          => $data['currencySymbol'] ?? null,
      'currency_symbol_position' => $data['currencySymbolPosition'] ?? null,
      'payment_method'           => $data['paymentMethod'] ?? null,
      'gateway_type'             => $data['gatewayType'] ?? null,
      'payment_status'           => $data['paymentStatus'] ?? null,
      'booking_status'           => $data['bookingStatus'] ?? null,
      'booking_date'             => $data['bookingDate'] ?? null,
      'start_time'               => $inputStartTime ?? null,
      'end_time'                 => $inputEndTime ?? null,
      'end_time_without_interval'  => $endTimeWithoutInterval ?? null,
      'custom_hour'              => $data['customHour'] ?? null,
      'total_hour'              => $data['totalHour'] ?? null,
      'start_date'               => $data['startDate'] ?? null,
      'end_date'                 => $data['endDate'] ?? null,
      'number_of_day'            => $data['numberOfDay'] ?? null,
      'booking_type'             => $space->space_type ?? null,
      'time_slot_id'             => $data['timeSlotId'] ?? null,
      'receipt'                  => $data['receiptName'] ?? null,
      'booked_by'                => $data['bookedBy'] ?? null,
      'discount'                 => $data['discount'],
      'conversation_id' => array_key_exists('conversation_id', $data) ? $data['conversation_id'] : null,
    ]);

    // this function remove used coupon from session
    $this->removeUsedCouponFromSession($userId);

    session()->forget('couponCode');
    return $orderInfo;
  }

  public function generateInvoice($bookingInfo)
  {
    $invoiceName = $bookingInfo->booking_number . '.pdf';
    $directory = public_path('assets/file/invoices/space/');
    @mkdir($directory, 0775, true);
    $fileLocation = $directory . $invoiceName;

    // data prepare for invoice
    $space = $this->prepareInvoiceData($bookingInfo);

    // get package title
    $html = view('pdf.space', compact('space'))->render();

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
    return $invoiceName;
  }

  public function prepareMail($orderInfo)
  {
    $spaceType = Space::where('id', $orderInfo->space_id)->select('space_type')->first();

    // get the mail template info from db
    if (isset($spaceType) && $spaceType->space_type == 1) {
      $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'fixed_time_slot_rental_space_booking')->first();
    } elseif (isset($spaceType) &&  $spaceType->space_type == 2) {
      $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'hourly_rental_space_booking')->first();
    } else {
      $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'multiday_rental_space_booking')->first();
    }

    $mailData['subject'] = $mailTemplate->mail_subject ?? __('We have confirmed your space booking');
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();
    $customerName = $orderInfo->customer_name;
    $bookingNumber = $orderInfo->booking_number;

    if (isset($spaceType) && ($spaceType->space_type == 1 || $spaceType->space_type == 2)) {
      $startTime = $orderInfo->start_time;
      $endTime = $orderInfo->end_time_without_interval;

      $bookingDate = $orderInfo->booking_date;
      $humanReadableDate = date("F j, Y", strtotime($bookingDate));
      // Conditional formatting based on space_type
      if ($spaceType->space_type !== 1) {
        // Convert to 12-hour format if space_type is not 1
        $startTimeFormatted = date("h:i A", strtotime($startTime));
        $endTimeFormatted = date("h:i A", strtotime($endTime));
      } else {
        // Keep original format for space_type 1
        $startTimeFormatted = date("h:i A", strtotime($startTime));
        $endTimeFormatted =  date("h:i A", strtotime($orderInfo->end_time));
      }

      $mailBody = str_replace('{booking_date}', $humanReadableDate, $mailBody);
      $mailBody = str_replace('{start_time}', $startTimeFormatted, $mailBody);
      $mailBody = str_replace('{end_time}', $endTimeFormatted, $mailBody);
    } else {
      $startDate = $orderInfo->start_date;
      $endDate = $orderInfo->end_date;
      $numberOfDay = $orderInfo->number_of_day;
      $mailBody = str_replace('{start_date}', $startDate, $mailBody);
      $mailBody = str_replace('{end_date}', $endDate, $mailBody);
      $mailBody = str_replace('{number_of_day}', $numberOfDay, $mailBody);
    }

    $bookingLink = '<br/><a href="' . route('frontend.user.space-booking-details', ['id' => $orderInfo->id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">' . __('View Booking Details') . '</a><br/>';

    // replacing with actual data
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingNumber, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{booking_link}', $bookingLink, $mailBody);

    $mailData['body'] = $mailBody;
    $mailData['recipient'] = $orderInfo->customer_email;

    $mailData['invoice'] = public_path('assets/file/invoices/space/' . $orderInfo->invoice);

    BasicMailer::sendMail($mailData);
    return;
  }

  public function prepareMailForVendor($orderInfo)
  {

    $vendor = null;
    if ($orderInfo->seller_id !== 0) {
      $vendor = Seller::where('id', $orderInfo->seller_id)
        ->select('recipient_mail', 'email', 'username')
        ->first();
    }

    // If vendor is null, exit the function
    if ($vendor === null) {
      return; // No need to send mail
    }

    $sessionLang = getAdminLanguage();
    $spaceContent = SpaceContent::where([
      ['space_id', $orderInfo->space_id],
      ['language_id', $sessionLang->id],
    ])->select('slug', 'title')->first();

    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'vendor_space_booking_notification')->first();


    $mailData['subject'] = $mailTemplate->mail_subject ?? __('Congratulations! Your Space Has Been Reserved');
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();
    $customerName = $orderInfo->customer_name ?? __('Dear Customer');
    $customerEmail = $orderInfo->customer_email ?? '';
    $vendorName = $vendor->username ?? __('Space Owner');
    $bookingNumber = $orderInfo->booking_number;
    $space_slug = $spaceContent->slug ?? '';
    $space_title = $spaceContent->title ?? '';
    $booking_date = $orderInfo->booking_date ?? '';
    $humanReadableDate = date("F j, Y", strtotime($booking_date));

    $spaceDetailsLink = '<a href="' . route('space.details', ['slug' => $space_slug, 'id' => $orderInfo->space_id]) . '" style="font-weight: bold; color: inherit; text-decoration: none; cursor: pointer;">' . $space_title . '</a>';

    $bookingLink = '<br/><a href="' . route('vendor.booking_record.show', ['id' => $orderInfo->id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">' . __('View Booking Details') . '</a><br/>';

    // replacing with actual data
    $mailBody = str_replace('{vendor_name}', $vendorName, $mailBody);
    $mailBody = str_replace('{space_title}', $spaceDetailsLink, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingNumber, $mailBody);
    $mailBody = str_replace('{booking_date}', $humanReadableDate, $mailBody);
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{customer_email}', $customerEmail, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{booking_link}', $bookingLink, $mailBody);

    $mailData['body'] = $mailBody;
    $mailData['recipient'] = $vendor->recipient_mail ?? $vendor->email;
    $mailData['customer_email'] = $customerEmail;
    BasicMailer::sendMail($mailData);
    return;
  }

  public function getQuoteInfo(GetQuoteFormRequest $request)
  {
    if (!Auth::guard('web')->check()) {
      return redirect()->route('user.login', ['redirectPath' => 'space-details']);
    }
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    // get data of form input-fields
    $inputFields = collect();
    $formId = $request->get_quote_form_id;
    $form = Form::query()->find($formId);

    if (!is_null($form)) {
      $inputFields = $form->input()->orderBy('order_no', 'asc')->get();
    }
    if (count($inputFields) > 0) {
      $infos = [];
      foreach ($inputFields as $inputField) {
        if ($inputField->type == 8) {
          $inputName = 'form_builder_' . $inputField->name;
        } else {
          $inputName = $inputField->name;
        }
        if (array_key_exists($inputName, $request->all())) {
          if ($request->hasFile($inputName)) {
            $originalName = $request->file($inputName)->getClientOriginalName();
            $uniqueName = UploadFile::store('./assets/file/zip-files/', $request->file($inputName));

            $infos[$inputField->name] = [
              'originalName' => $originalName,
              'value' => $uniqueName,
              'type' => $inputField->type
            ];
          } else {
            $infos[$inputName] = [
              'value' => $request[$inputName],
              'type' => $inputField->type
            ];
          }
        }
      }
      $allData['infos'] = json_encode($infos);
    } else {
      $allData['infos'] = null;
    }

    // Check if there's an existing record with the same user_id and seller_id
    $getQuote = GetQuote::where('user_id', $request->user_id)
      ->where('seller_id', $request->seller_id)
      ->first();

    if (isset($getQuote) && !empty($getQuote)) {
      // Update the existing record
      $getQuote->booking_number =
        $this->generateUniqueBookingNumber('get_quotes');
      $getQuote->space_id = $request->space_id;
      $getQuote->language_id = $language->id;
      $getQuote->customer_name = $request->name;
      $getQuote->customer_email = $request->email_address;
      $getQuote->status = 'pending';
      $getQuote->information = $allData['infos'];
      $getQuote->save();
      session()->flash('success', __('Your quote information has been successfully updated') . '.');
    } else {
      // If no existing record, create a new one
      $getQuote = new GetQuote();
      $getQuote->booking_number = $this->generateUniqueBookingNumber('get_quotes');
      $getQuote->user_id = $request->user_id;
      $getQuote->language_id = $language->id;
      $getQuote->space_id = $request->space_id;
      $getQuote->seller_id = isset($request->seller_id) ? $request->seller_id : null;
      $getQuote->customer_name = $request->name;
      $getQuote->status = 'pending';
      $getQuote->customer_email = $request->email_address;
      $getQuote->information = $allData['infos'];
      $getQuote->save();
      session()->flash('success', __('Your quote has been successfully submitted') . '.');
    }
    GetQuote::prepareMailForQuoteRequest($getQuote, 'quote_request');
    return redirect()->back();
  }

  public function bookForTourInfo(BookTourRequest $request)
  {
    if (!Auth::guard('web')->check()) {
      return redirect()->route('user.login', ['redirectPath' => 'space-details']);
    }
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    // get data of form input-fields

    $formId = $request->tour_request_form_id;
    $form = Form::query()->find($formId);

    $inputFields = collect();
    if (!is_null($form)) {
      $inputFields = $form->input()->orderBy('order_no', 'asc')->get();
    }
    if ($inputFields->isNotEmpty()) {
      $infos = [];
      foreach ($inputFields as $inputField) {
        if ($inputField->type == 8) {
          $inputName = 'form_builder_' . $inputField->name;
        } else {
          $inputName = $inputField->name;
        }
        if (array_key_exists($inputName, $request->all())) {
          if ($request->hasFile($inputName)) {
            $originalName = $request->file($inputName)->getClientOriginalName();
            $uniqueName = UploadFile::store('./assets/file/zip-files/', $request->file($inputName));
            $infos[$inputField->name] = [
              'originalName' => $originalName,
              'value' => $uniqueName,
              'type' => $inputField->type
            ];
          } else {
            $infos[$inputName] = [
              'value' => $request[$inputName],
              'type' => $inputField->type
            ];
          }
        }
      }

      $allData['infos'] = json_encode($infos);
    } else {
      $allData['infos'] = null;
    }

    // Check if there's an existing record with the same user_id and seller_id
    $bookForTour = BookForTour::where([
      ['user_id', $request->user_id],
      ['space_id', $request->space_id],
    ])
      ->first();
    if (isset($bookForTour) && !empty($bookForTour)) {
      // Update the existing record
      $bookForTour->booking_number = $this->generateUniqueBookingNumber('book_for_tours');
      $bookForTour->space_id = $request->space_id;
      $bookForTour->language_id = $language->id;
      $bookForTour->customer_name = $request->user_name;
      $bookForTour->status = 'pending';
      $bookForTour->customer_email = $request->user_email_address;
      $bookForTour->information = $allData['infos'];
      $bookForTour->save();
      session()->flash('success', __('Your tour request information has been successfully updated') . '.');
    } else {
      // If no existing record, create a new one
      $bookForTour = new BookForTour();
      $bookForTour->booking_number = $this->generateUniqueBookingNumber('book_for_tours');
      $bookForTour->user_id = $request->user_id;
      $bookForTour->language_id = $language->id;
      $bookForTour->space_id = $request->space_id;
      $bookForTour->seller_id = $request->seller_id;
      $bookForTour->customer_name = $request->user_name;
      $bookForTour->status = 'pending';
      $bookForTour->customer_email = $request->user_email_address;
      $bookForTour->information = $allData['infos'];
      $bookForTour->save();
      session()->flash('success', __('Your tour request has been successfully submitted') . '.');
    }
    BookForTour::prepareMailForTourRequest($bookForTour, 'tour_request');

    return redirect()->back();
  }

  public function complete($slug, Request $request)
  {
    $misc = new MiscellaneousController();
    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['payVia'] = $request->input('via');
    return view('frontend.payment.success', $queryResult);
  }

  public function cancel($slug, Request $request)
  {

    $request->session()->flash('error', __('Payment Cancel') . '!');
    $space_content = SpaceContent::where('slug', $slug)->select('space_id')->first();
    if ($space_content) {
      return redirect()->route('space.details', ['slug' => $slug, 'id' => $space_content->space_id]);
    } else {
      return redirect()->route('space.index');
    }
  }

  function generateUniqueBookingNumber($tableName)
  {
    do {
      // Generate a random 6-digit number
      $bookingNumber = random_int(100000, 999999);

      // Check if the booking number already exists in the database
      if ($tableName == 'book_for_tours') {
        $exists = DB::table('book_for_tours')
          ->where('booking_number', $bookingNumber)
          ->exists();
      }
      if ($tableName == 'get_quotes') {
        $exists = DB::table('get_quotes')
          ->where('booking_number', $bookingNumber)
          ->exists();
      }
    } while ($exists); // Continue generating until a unique number is found

    return $bookingNumber;
  }

  private function getAvailableTimeSlots($bookingDate, $space_id, $seller_id)
  {
    $timezone = now()->timezoneName ?? config('app.timezone');
    $settings = Basic::select('time_format')->first();
    $timeFormat = $settings && $settings->time_format === '12h' ? '12' : '24';

    $selectedDate = Carbon::parse($bookingDate, $timezone);
    $parsedBookingDate = $selectedDate->format('Y-m-d');
    $dayOfWeek = $selectedDate->dayOfWeek;

    $day = GlobalDay::select('id', 'name', 'start_of_week')
      ->where('start_of_week', $dayOfWeek)
      ->where('space_id', $space_id)
      ->first();

    if (!$day) {
      return collect(); // return empty collection
    }

    $bookedTimeSlots = SpaceBooking::query()
      ->select('time_slot_id', DB::raw('COUNT(*) as booking_count'))
      ->where('space_id', $space_id)
      ->where('booking_date', $parsedBookingDate)
      ->where('booking_status', '!=', 'rejected')
      ->groupBy('time_slot_id')
      ->get();

    $allTimeSlots = TimeSlot::query()
      ->select('id as time_slot_id', 'start_time', 'end_time', 'number_of_booking', 'time_slot_rent', 'global_day_id')
      ->where('space_id', $space_id)
      ->where('global_day_id', $day->id)
      ->get();

    $arrayBookedId = [];
    foreach ($bookedTimeSlots as $bookedSlot) {
      $slot = $allTimeSlots->firstWhere('time_slot_id', $bookedSlot->time_slot_id);
      if ($slot && $bookedSlot->booking_count >= $slot->number_of_booking) {
        $arrayBookedId[] = $bookedSlot->time_slot_id;
      }
    }

    $now = now($timezone);
    $isToday = $parsedBookingDate === $now->format('Y-m-d');

    $availableTimeSlots = $allTimeSlots->filter(function ($slot) use ($arrayBookedId, $isToday, $timezone, $now) {
      if (in_array($slot->time_slot_id, $arrayBookedId)) {
        return false;
      }

      if ($isToday) {
        try {
          $slotStartTime = Carbon::createFromFormat('H:i:s', $slot->start_time, 'UTC')->setTimezone($timezone);
          if ($slotStartTime->lessThanOrEqualTo($now)) {
            return false;
          }
        } catch (\Exception $e) {
          return false;
        }
      }

      return true;
    })->values();

    return $availableTimeSlots;
  }

  private function prepareInvoiceData($bookingInfo)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $basicSetting = Basic::select('logo', 'website_title', 'favicon')->first();

    $spaceRecord = isset($bookingInfo['space_id']) ? Space::query()
      ->join('space_contents', 'space_contents.space_id', '=', 'spaces.id')
      ->where('space_contents.language_id', $language->id)
      ->select('space_contents.title', 'space_contents.space_id', 'spaces.space_type')
      ->where('spaces.id', $bookingInfo['space_id'])->first() : null;

    if (!$spaceRecord) {
      throw new \Exception("Space not found for booking.");
    }

    if ($spaceRecord->space_type == 1) {
      $startTime = Carbon::parse($bookingInfo['start_time']);
      $endTime = Carbon::parse($bookingInfo['end_time']);
      $durationInHours = $startTime->floatDiffInHours($endTime);
    } elseif ($spaceRecord->space_type == 2) {
      $durationInHours = $bookingInfo['total_hour'];
    } else {
      $durationInHours = $bookingInfo['number_of_day'];
    }

    if ($bookingInfo['seller_id'] != null) {
      $sellerWithInfo = Seller::join('seller_infos', 'sellers.id', '=', 'seller_infos.seller_id')
        ->select('sellers.recipient_mail', 'sellers.email', 'sellers.phone', 'sellers.username', 'seller_infos.name', 'seller_infos.country', 'seller_infos.city', 'seller_infos.state', 'seller_infos.address')
        ->where('sellers.id', $bookingInfo['seller_id'])
        ->first();
    } else {
      $sellerWithInfo = Admin::select('email', 'username', 'phone', 'address', 'first_name as name')->first();
    }

    return [
      'space_type' => $spaceRecord->space_type,
      'name' => $spaceRecord->title,
      'start_date' => $bookingInfo['start_date'] ?? null,
      'end_date' => $bookingInfo['end_date'] ?? null,
      'start_time' => ($spaceRecord->space_type == 1 || $spaceRecord->space_type == 2) ? formatTo12Hour($bookingInfo['start_time']) : null,

      'end_time' => empty($bookingInfo['end_time']) && empty($bookingInfo['end_time_without_interval'])
        ? null
        : formatTo12Hour($spaceRecord->space_type == 1 ? $bookingInfo['end_time'] : $bookingInfo['end_time_without_interval']),

      'duration' => $durationInHours,
      'discount' => $bookingInfo['discount'] ?? 0.00,
      'amount' => $bookingInfo['grand_total'] ?? 0.00,
      'payment_method' => $bookingInfo['payment_method'],
      'subtotal' => $bookingInfo['sub_total'] ?? 0.00,
      'tax_percentage' => $bookingInfo['tax_percentage'] ?? 0,
      'tax_amount' => $bookingInfo['tax'] ?? 0.00,
      'total' => $bookingInfo['sub_total'] + $bookingInfo['discount'],
      'received_amount' => $bookingInfo['grand_total'] - $bookingInfo['tax'],
      'currency_symbol' => $bookingInfo['currency_symbol'],
      'currency_symbol_position' => $bookingInfo['currency_symbol_position'],
      'customer_name' => $bookingInfo['customer_name'],
      'customer_email' => $bookingInfo['customer_email'],
      'customer_phone' => $bookingInfo['customer_phone'],
      'vendor_name' => $sellerWithInfo->username ?? $sellerWithInfo->name,
      'vendor_email' => $sellerWithInfo->recipient_mail ?? $sellerWithInfo->email,
      'vendor_phone' => $sellerWithInfo->phone ?? null,
      'vendor_address' => $sellerWithInfo->address ?? null,
      'logo' => $basicSetting->logo ?? null,
      'booking_number' => $bookingInfo['booking_number'],
      'booking_date' => $bookingInfo['booking_date'],
      'currency_text' => $bookingInfo['currency_text'],
      'website_title' => $basicSetting->website_title ?? null,
      'favicon' => $basicSetting->favicon ?? null,
      'created_at' => $bookingInfo['created_at'] ? Carbon::parse($bookingInfo['created_at'])->format('Y-m-d') : null,
    ];
  }

  // this function remove used coupon from session
  public function removeUsedCouponFromSession($userId)
  {
    $couponCode = session('couponCode');
    $userIp = request()->ip();

    if ($couponCode) {
      CouponUsage::where('user_id', $userId)
        ->where('coupon_code', $couponCode)
        ->delete();
    }
  }
}
