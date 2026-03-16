<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularCitySection extends Model
{
    use HasFactory;
    protected $table = 'popular_city_sections';
    protected $fillable = [
      'language_id',
      'title',
      'text',
      'button_name',
    ];
}
