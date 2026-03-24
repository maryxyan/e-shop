<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    static function getSliderImages()
    {
        return self::active()->ordered()->get();
    }
}
