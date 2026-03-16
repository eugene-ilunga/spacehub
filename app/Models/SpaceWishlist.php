<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceWishlist extends Model
{
    use HasFactory;
    protected  $fillable =[
      'space_id',
      'user_id',
      ];

  public function space()
  {
    return $this->belongsTo(Space::class, 'space_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
