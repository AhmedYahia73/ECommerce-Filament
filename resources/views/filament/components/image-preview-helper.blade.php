@php
    // نستخدم المتغير الذي مررناه من الـ Resource
    $imageUrl = null;

    if (isset($state) && $state && str_contains($state, '/')) {
        $parts = explode('/', $state);
        $mediaId = $parts[0];
        
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
        if ($media) {
            $imageUrl = $media->getUrl();
        }
    }
@endphp

@if($imageUrl)
    <div class="mt-4 relative group w-40">
        <img src="{{ $imageUrl }}" 
             class="h-40 w-40 object-cover rounded-lg shadow-md border-2 border-primary-500 transition-all group-hover:opacity-75">
        
        {{-- تنبيه: زر المسح يحتاج أن يكون اسم الحقل مطابقاً (image) --}}
        <button type="button" 
                wire:click="$set('data.image', null)"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg opacity-0 group-hover:opacity-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif