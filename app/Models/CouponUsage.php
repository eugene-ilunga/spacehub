<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'host_ip',
        'user_id',
    ];
}
