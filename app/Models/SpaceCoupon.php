<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceCoupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'space_type',
        'name',
        'code',
        'coupon_type',
        'value',
        'start_date',
        'end_date',
        'serial_number',
        'spaces',
    ];
}
