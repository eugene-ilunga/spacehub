<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceService extends Model
{
    use HasFactory;
  protected $fillable = [
    'space_id',
    'seller_id',
    'image',
    'status',
    'serial_number',
    'has_sub_services',
    'subservice_selection_type',
    'price_type',
    'price',
    'is_featured',
    'is_custom_day',
  ];
  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
  public function space()
  {
    return $this->belongsTo(Space::class, 'space_id', 'id');
  }
  public function serviceContents()
  {
    return $this->hasMany(SpaceServiceContent::class, 'space_service_id', 'id');
  }
  public function subServices()
  {
    return $this->hasMany(SubService::class, 'service_id', 'id');
  }


}
