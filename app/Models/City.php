<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
  protected $fillable = [
    'language_id',
    'country_id',
    'state_id',
    'image',
    'name',
    'slug',
    'status',
    'is_featured',
  ];

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id', 'id');
  }
}
