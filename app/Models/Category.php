<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use \BezhanSalleh\FilamentShield\Traits\HasPageShield;

    protected $fillable = [
        'name',
        'description',
        'long_description',
        'image',
        'status',
        "image_id",
    ];

    public function products(){
        return $this->hasMany(Product::class, 'category_id');
    }
}
