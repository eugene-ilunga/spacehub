<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
  protected $fillable = [
    'language_id',
    'name',
    'slug',
    'status',
  ];
  public function states()
  {
    return $this->hasMany(State::class, 'country_id', 'id');
  }
}
