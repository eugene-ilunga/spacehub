<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceReview extends Model
{
    use HasFactory;
  protected $fillable = [
    'user_id',
    'space_id',
    'rating',
    'comment',
  ];
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
