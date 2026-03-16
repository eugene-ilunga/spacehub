<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'serial_number',
        'page_type',
        'position'
    ];
    public function page_content()
    {
        return $this->belongsTo(AdditionalSection::class, 'addition_section_id', 'id');
    }
    public function contents()
    {
        return $this->hasMany(AdditionalSectionContent::class, 'addition_section_id');
    }
}
