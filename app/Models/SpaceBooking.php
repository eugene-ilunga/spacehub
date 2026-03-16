<?php

namespace App\Models;

use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\ClientService\ServicePackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceBooking extends Model
{
  use HasFactory;
  protected $guarded = [];
  protected $fillable = [
    'user_id',
    'seller_id',
    'booking_number',
    'customer_name',
    'customer_email',
    'customer_phone',
    'space_id',
    'package_id',
    'seller_membership_id',
    'service_stage_info',
    'sub_total',
    'service_total',
    'sub_service_info',
    'number_of_guest',
    'other_service_info',
    'space_rent_price',
    'grand_total',
    'tax_percentage',
    'tax',
    'currency_text',
    'currency_text_position',
    'currency_symbol',
    'currency_symbol_position',
    'payment_method',
    'gateway_type',
    'booking_type',
    'payment_status',
    'booking_status',
    'booking_date',
    'time_slot_id',
    'start_time',
    'end_time',
    'start_date',
    'end_date',
    'number_of_day',
    'custom_hour',
    'end_time_without_interval',
    'total_hour',
    'duration',
    'booked_by',
    'discount',
    'receipt',
    'invoice',
    'raise_status',
    'conversation_id',
  ];

  public function timeSlot()
  {
    return $this->belongsTo(TimeSlot::class, 'time_slot_id', 'id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }

  public function space()
  {
    return $this->belongsTo(Space::class, 'space_id', 'id');
  }

  public static function getAllDates($arrivalDate, $departureDate, $format)
  {
    $dates = [];

    // convert string to timestamps
    $currentTimestamps = strtotime($arrivalDate);
    $endTimestamps = strtotime($departureDate);

    // set an increment value
    $stepValue = '+1 day';

    // push all the timestamps to the 'dates' array by formatting those timestamps into date
    while ($currentTimestamps <= $endTimestamps) {
      $formattedDate = date($format, $currentTimestamps);
      array_push($dates, $formattedDate);
      $currentTimestamps = strtotime($stepValue, $currentTimestamps);
    }
    return $dates;
  }

  public static function isTimeIn24HourFormat($time)
  {
    // Regular expression to match 24-hour format (HH:mm)
    return preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $time) === 1;
  }

  public static function convertTo24HourFormat($time)
  {
    // Use Carbon to parse the time in 12-hour format
    try {
      $convertedTime = \Carbon\Carbon::createFromFormat('h:i A', $time)->format('H:i');
      return $convertedTime;
    } catch (\Exception $e) {
      return null; // Return null or handle the error as needed
    }
  }

}
