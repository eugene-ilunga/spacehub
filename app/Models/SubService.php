<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    use HasFactory;
    protected $fillable = [
      'space_id',
      'service_id',
      'image',
      'price',
      'price_type',
      'status',
    ];

    public  function subServiceContents()
    {
      return $this->hasMany(SubServiceContent::class,'sub_service_id', 'id');

    }

  public function spaceService()
  {
    return $this->belongsTo(SpaceService::class, 'service_id', 'id');
  }
}
