<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'fixed_time_slot_rental',
        'hourly_rental',
    ];


}
