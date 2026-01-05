<?php

namespace App\Models;

// استيراد موديل Spatie الأساسي
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class MediaItem extends SpatieMedia
{
    // لا تحتاج لتعريف $table أو $fillable هنا
    // لأن الكلاس الأب (SpatieMedia) يعرفها مسبقاً
}