<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'category_section_title',
        'featured_section_title',
        'banner_section_title',
        'banner_section_button_text',
        'workprocess_section_title',
        'testimonial_title',
        'video_banner_video_link',
        'hero_section_title',
        'hero_section_text',
        'popular_city_section_title',
        'popular_city_section_text',
        'popular_city_section_button_name',
    ];
}
