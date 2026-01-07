<x-filament-panels::page>
    <div class="space-y-6">

        @if(empty($files))
            <div class="text-center py-12">
                <x-filament::icon icon="heroicon-o-photo" class="w-16 h-16 text-gray-400 mx-auto"/>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                    No files in this folder
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Upload your first file to get started
                </p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($files as $file)
                    <div class="relative group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-shadow">
                        
                        <!-- Preview -->
                        <div class="aspect-square bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                            @if(str_starts_with($file['mime_type'], 'image/'))
                                <img src="{{ Storage::disk($file['disk'])->url($file['collection_name'].'/'.$file['file_name']) }}" 
                                     alt="{{ $file['name'] }}" class="w-full h-full object-cover"/>
                            @elseif(str_starts_with($file['mime_type'], 'video/'))
                                <x-filament::icon icon="heroicon-o-film" class="w-16 h-16 text-gray-400"/>
                            @elseif($file['mime_type'] === 'application/pdf')
                                <x-filament::icon icon="heroicon-o-document-text" class="w-16 h-16 text-red-500"/>
                            @else
                                <x-filament::icon icon="heroicon-o-document" class="w-16 h-16 text-gray-400"/>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="p-2 space-y-1">
                            <p class="text-sm font-medium truncate" title="{{ $file['name'] }}">
                                {{ $file['name'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ number_format($file['size']/1024,2) }} KB
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition">
                            <a href="{{ Storage::disk($file['disk'])->url($file['collection_name'].'/'.$file['file_name']) }}"
                               target="_blank"
                               class="p-2 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                               <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-5 h-5"/>
                            </a>

                            <button wire:click="deleteFile({{ $file['id'] }})"
                                    wire:confirm="Are you sure?"
                                    class="p-2 bg-white dark:bg-gray-800 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5 text-red-600"/>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
