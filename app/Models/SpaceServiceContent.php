<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceServiceContent extends Model
{
  use HasFactory;
  protected $fillable = [
    'language_id',
    'space_service_id',
    'title',
    'slug',
    'description',
    'meta_keywords',
    'meta_description',
  ];
  public function spaceService()
  {
    return $this->belongsTo(SpaceService::class, 'space_service_id', 'id');
  }


  public static function getSpaceServiceTitle($id, $sessionLang)
  {
    $title = SpaceServiceContent::where([
      ['space_service_id', $id],
      ['language_id', $sessionLang->id],
    ])->select('title')->first();
    return $title;
  }
}
