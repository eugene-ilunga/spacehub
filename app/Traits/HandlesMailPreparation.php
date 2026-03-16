<?php

namespace App\Traits;

use App\Models\Space;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\MailTemplate;
use App\Models\BasicSettings\Basic;
use App\Models\Seller;
use App\Models\SpaceContent;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

trait HandlesMailPreparation
{
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
        $startTimeFormatted = $startTime;
        $endTimeFormatted = $orderInfo->end_time;
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
    $vendorName = $vendor->username ?? __('Space Owner');
    $bookingNumber = $orderInfo->booking_number;
    $space_slug = $spaceContent->slug ?? '';
    $space_title = $spaceContent->title ?? '';
    $booking_date = $orderInfo->booking_date ?? '';
    $humanReadableDate = date("F j, Y", strtotime($booking_date));

    $spaceDetailsLink = '<a href="' . route('space.details', ['slug' => $space_slug, 'id' => $orderInfo->space_id]) . '" style="font-weight: bold; color: inherit; text-decoration: none; cursor: pointer;">' . __($space_title) . '</a>';

    $bookingLink = '<br/><a href="' . route('vendor.booking_record.show', ['id' => $orderInfo->id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">' . __('View Booking Details') . '</a><br/>';

    // replacing with actual data
    $mailBody = str_replace('{vendor_name}', $vendorName, $mailBody);
    $mailBody = str_replace('{space_title}', $spaceDetailsLink, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingNumber, $mailBody);
    $mailBody = str_replace('{booking_date}', $humanReadableDate, $mailBody);
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{booking_link}', $bookingLink, $mailBody);

    $mailData['body'] = $mailBody;
    $mailData['recipient'] = $vendor->recipient_mail ?? $vendor->email;
    BasicMailer::sendMail($mailData);
    return;
  }
  public function sendMail($request, $booking, $mailFor)
  {
    // first get the mail template info from db
    if ($mailFor == 'Booking approved') {
      $mailTemplate = MailTemplate::where('mail_type', 'space_booking_approved')->first();
    } else {
      $mailTemplate = MailTemplate::where('mail_type', 'space_booking_rejected')->first();
    }


    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp info from db
    $info = DB::table('basic_settings')
    ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
    ->first();

    $customerName = $booking->customer_name;
    $booking_id = $booking->booking_number;
    $booking_email = $booking->customer_email;

    $language = App('currentLanguage');
    $space = Space::where('id', $booking->space_id)->firstOrFail();
    $spaceContent = SpaceContent::where('space_id', $space->id)->select('id', 'title', 'slug')->where('language_id', $language->id)->firstOrFail();
    $spaceTitle = $spaceContent->title;

    $websiteTitle = $info->website_title;

    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{booking_number}', $booking_id, $mailBody);
    $mailBody = str_replace('{space_title}', '<a href="' . route('space.details', ['slug' => $spaceContent->slug, 'id' => $space->id]) . '">' . __($spaceTitle) . '</a>', $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS
      } elseif ($info->encryption == 'SSL') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS
      } else {
        throw new Exception('Unsupported encryption type');
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      // Recipients
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($booking_email);

      // Attachments (Invoice)
      if (!is_null($booking->invoice)) {
        $mail->addAttachment(public_path('./assets/file/invoices/space/') . $booking->invoice);
      }

      // Content
      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;
      $mail->send();

      Session::flash('success', __('Updated Successfully') . '!');
    } catch (Exception $e) {
      Session::flash('warning', __('Mail could not be sent') . '. '. __('Mailer Error') . ': ' . $mail->ErrorInfo);
    }
    return;
  }
}
