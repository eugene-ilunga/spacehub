<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'seller_id',
        'name',
        'country',
        'city',
        'state',
        'zip_code',
        'address',
        'details'
    ];
}
