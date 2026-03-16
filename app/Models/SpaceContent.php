<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceContent extends Model
{
    use HasFactory;
  protected $fillable = [
    'language_id',
    'space_id',
    'country_id',
    'state_id',
    'city_id',
    'sub_category_id',
    'space_category_id',
    'get_quote_form_id',
    'tour_request_form_id',
    'title',
    'slug',
    'address',
    'description',
    'amenities',
    'meta_keywords',
    'meta_description',
  ];
  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
  public function space()
  {
    return $this->belongsTo(Space::class, 'space_id', 'id');
  }

  public static function getSpaceTitle($id, $sessionLang){
    $title = SpaceContent::where([
      ['space_id', $id],
      ['language_id', $sessionLang->id],
    ])->select('title')->first();

    return $title;
  }
}
