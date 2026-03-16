<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
  use HasFactory;
  protected $fillable = [
    'seller_id',
    'space_id',
    'global_day_id',
    'start_time',
    'end_time',
    'booking_duration',
    'number_of_booking',
    'is_available',
    'time_range',
    'time_slot_rent',
  ];
  public function spaceBookings()
  {
    return $this->hasMany(SpaceBooking::class, 'time_slot_id', 'id');
  }
}
