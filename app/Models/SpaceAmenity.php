<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceAmenity extends Model
{
    use HasFactory;
   protected $fillable = [
     'language_id',
     'icon',
     'name',
     'serial_number',
   ];
}
