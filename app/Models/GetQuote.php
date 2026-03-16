<?php

namespace App\Models;

use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetQuote extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'booking_number',
        'seller_id',
        'space_id',
        'status',
        'customer_name',
        'language_id',
        'customer_email',
        'customer_phone	',
        'information'
    ];

    public static function prepareMailForQuoteRequest($quoteInfo, $quoteType=null){
        
        // get the mail template info from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', $quoteType)->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        
        $mailBody = $mailTemplate->mail_body;
        $space = SpaceContent::where([
            ['space_id', $quoteInfo->space_id],
            ['language_id', $quoteInfo->language_id]
        ])->select('title', 'slug')->firstOrFail();
        
        // get the website title info from db
        $websiteTitle = Basic::query()->pluck('website_title')->first();

        $customerName = $quoteInfo->customer_name;
        $bookingNumber = $quoteInfo->booking_number;

        $bookingLink = '<br/><a href="' . route('space.details', ['slug' => $space->slug, 'id' => $quoteInfo->space_id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">' . $space->title. '</a><br/>';

        // replacing with actual data
        $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
        $mailBody = str_replace('{request_number}', $bookingNumber, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
        $mailBody = str_replace('{title}', $bookingLink, $mailBody);

        $mailData['body'] = $mailBody;
        $mailData['recipient'] = $quoteInfo->customer_email;
        BasicMailer::sendMail($mailData);
        return;

    }
}
