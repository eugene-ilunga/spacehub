<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceCategory extends Model
{
    use HasFactory;
  protected $fillable = [
    'language_id',
    'icon_image',
    'bg_image',
    'icon',
    'category_description',
    'name',
    'slug',
    'status',
    'serial_number',
    'is_featured',
  ];

  public function spaceCategory()
  {
    return $this->belongsTo(SpaceCategory::class, 'language_id', 'id');
  }
  public function subcategory()
  {
    return $this->hasMany(SpaceSubCategory::class, 'space_category_id', 'id');
  }
  public function spaceContents()
  {
    return $this->hasMany(SpaceContent::class, 'space_category_id', 'id');
  }

}
