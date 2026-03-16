<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureCharge extends Model
{
    use HasFactory;
    protected $fillable = [
      'language_id',
      'day',
      'price',
    ];
}
