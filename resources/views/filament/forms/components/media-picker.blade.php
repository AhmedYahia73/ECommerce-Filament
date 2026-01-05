<div x-data="{ selected: @entangle($getStatePath()) }" class="p-4 bg-gray-50/50 rounded-lg">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 overflow-y-auto max-h-[60vh] p-1">
        @foreach(\App\Models\MediaItem::latest()->get() as $item)
            <div 
                @click="selected = '{{ $item->id }}'"
                class="relative group cursor-pointer transition-all duration-200 ease-in-out"
            >
                <div 
                    class="aspect-square w-full overflow-hidden rounded-lg bg-white border-2 transition-all duration-200"
                    :class="selected == '{{ $item->id }}' ? 'border-primary-500 ring-2 ring-primary-500/20 shadow-lg' : 'border-gray-200 hover:border-primary-300 hover:shadow-md shadow-sm'"
                >
                    <img 
                        src="{{ $item->getUrl() }}" 
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                        alt="{{ $item->file_name }}"
                        loading="lazy"
                    >
                </div>

                <!-- Checkmark Badge -->
                <div 
                    x-show="selected == '{{ $item->id }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-50"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute -top-2 -right-2 z-10"
                >
                    <div class="bg-primary-500 text-white rounded-full p-1.5 shadow-lg border-2 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Hover Overlay -->
                <div class="absolute inset-0 rounded-lg bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"></div>
                
                <!-- Selected Overlay -->
                <div 
                    x-show="selected == '{{ $item->id }}'"
                    class="absolute inset-0 rounded-lg bg-primary-500/10 pointer-events-none"
                ></div>
            </div>
        @endforeach
    </div>

    @if(\App\Models\MediaItem::count() === 0)
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm font-medium">مكتبة الصور فارغة</p>
        </div>
    @endif
</div>