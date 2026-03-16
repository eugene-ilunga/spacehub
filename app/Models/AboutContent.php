<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasFactory;
  protected $fillable = [
    'sub_title',
    'sub_text',
    'language_id',
  ];
  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

}
