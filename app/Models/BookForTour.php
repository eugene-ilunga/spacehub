<?php

namespace App\Models;

use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookForTour extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'booking_number',
        'seller_id',
        'space_id',
        'customer_name',
        'customer_email',
        'status',
        'information',
        'language_id'
    ];

    public static function prepareMailForTourRequest($tourInfo, $tourType = null)
    {

        // get the mail template info from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', $tourType)->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;
        $space = SpaceContent::where([
            ['space_id', $tourInfo->space_id],
            ['language_id', $tourInfo->language_id]
        ])->select('title', 'slug')->firstOrFail();
        $info = json_decode($tourInfo->information, true);

        // Initialize variables for date and time
        $humanReadableDate = '';
        $time12Hour = '';

        // Loop through info to find date and time by type
        if (!empty($info) && is_array($info)) {
            foreach ($info as $key => $field) {
                if (isset($field['type'], $field['value'])) {
                    // Type 6 = date
                    if ($field['type'] == 6) {
                        $dateObject = DateTime::createFromFormat('m/d/Y', $field['value']);
                        if ($dateObject) {
                            $humanReadableDate = $dateObject->format('F j, Y');
                        }
                    }

                    // Type 7 = time
                    if ($field['type'] == 7) {
                        $timeString = $field['value'];
                        // Try parsing common formats
                        $timeObject = DateTime::createFromFormat('H:i', $timeString);
                        if (!$timeObject) {
                            $timeObject = DateTime::createFromFormat('h:i A', $timeString);
                        }
                        if ($timeObject) {
                            $time12Hour = $timeObject->format('g:i A');
                        }
                    }
                }
            }
        }


        // get the website title info from db
        $websiteTitle = Basic::query()->pluck('website_title')->first();

        $customerName = $tourInfo->customer_name;
        $bookingNumber = $tourInfo->booking_number;

        $bookingLink = '<br/><a href="' . route('space.details', ['slug' => $space->slug, 'id' => $tourInfo->space_id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">' . $space->title . '</a><br/>';

        // replacing with actual data
        $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
        $mailBody = str_replace('{request_number}', $bookingNumber, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
        $mailBody = str_replace('{title}', $bookingLink, $mailBody);
        if($tourType == 'tour_request_confirm_status'){
            $mailBody = str_replace('{visit_date}', $humanReadableDate, $mailBody);
            if(isset($time12Hour) && !empty($time12Hour)){
                $mailBody = str_replace('{visit_time}', $time12Hour, $mailBody);
            }
        }

        $mailData['body'] = $mailBody;
        $mailData['recipient'] = $tourInfo->customer_email;
        BasicMailer::sendMail($mailData);
        return;
    }
}
