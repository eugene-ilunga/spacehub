<?php

namespace App\Models;

use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\MailTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDF;

class SpaceFeature extends Model
{
  use HasFactory;
  protected $fillable = [
    'seller_id',
    'feature_charge_id',
    'booking_number',
    'seller_email',
    'space_id',
    'total',
    'currency_text',
    'currency_text_position',
    'currency_symbol',
    'currency_symbol_position',
    'payment_method',
    'gateway_type',
    'payment_status',
    'booking_status',
    'attachment',
    'invoice',
    'days',
    'start_date',
    'end_date',
    'conversation_id',
  ];

  public function space()
  {
    return $this->belongsTo(Space::class, 'space_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }
  public function featureCharge()
  {
    return $this->belongsTo(FeatureCharge::class, 'feature_charge_id', 'id');
  }

  public static function sendPaymentStatusEmail($order, $url, $spaceName, $vendorName, $websiteTitle, $mailType, $invoice = null)
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
}
