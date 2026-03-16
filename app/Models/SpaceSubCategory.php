<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceSubCategory extends Model
{
    use HasFactory;
  protected $fillable = [
    'language_id',
    'space_category_id',
    'name',
    'slug',
    'status',
    'serial_number'
  ];
  public function category()
  {
    return $this->belongsTo(SpaceCategory::class, 'space_category_id', 'id');
  }

}
