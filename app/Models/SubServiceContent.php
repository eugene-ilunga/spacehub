<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubServiceContent extends Model
{
    use HasFactory;
  protected $fillable = [
    'sub_service_id',
    'language_id',
    'title',
    'slug',
    'description',
    'meta_keywords',
    'meta_description',
  ];


  public function subService()
  {
    return $this->belongsTo(SubService::class, 'sub_service_id', 'id');
  }

}


