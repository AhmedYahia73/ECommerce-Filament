<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'quantity_at_packet',
        'offer_price',
        'start_date',
        'end_date',
        'image', 
        'short_description',
        'category_id',
        'unit_id',
        "gallery",
        "image_id",
        "gallery_ids",
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function saveImageFromLibrary($mediaItemId)
    {
        $mediaItem = MediaItem::find($mediaItemId);
        $mediaItem->getFirstMedia()->copy($this, 'products-collection');
    }
}
