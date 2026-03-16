<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalDay extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'seller_id',
    'order',
    'is_weekend',
    'is_holiday',
    'start_of_week',
  ];
}
