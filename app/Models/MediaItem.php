<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia; // السطر المطلوب 1
use Spatie\MediaLibrary\InteractsWithMedia; // السطر المطلوب 2

// تأكد من إضافة "implements HasMedia" بعد اسم الكلاس
class MediaItem extends Model implements HasMedia 
{
    protected $table = 'media';
    // لا تحتاج لتعريف $table أو $fillable هنا
    // لأن الكلاس الأب (SpatieMedia) يعرفها مسبقاً
  use InteractsWithMedia;
}