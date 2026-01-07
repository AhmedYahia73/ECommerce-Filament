<x-filament-panels::page>
 <a href="{{ \App\Filament\Resources\Media\Pages\MediaFolderPage::getUrl(['folder' => $folder]) }}">
    Open Media Folder
</a>
        @if(empty($files))
            <div class="text-center py-12">
                <div class="flex justify-center mb-4">
                    <x-filament::icon 
                        icon="heroicon-o-photo" 
                        class="w-16 h-16 text-gray-400"
                    />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
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
                                <img 
                                    src="{{ Storage::disk($file['disk'])->url($file['collection_name'] . '/' . $file['file_name']) }}" 
                                    alt="{{ $file['name'] }}"
                                    class="w-full h-full object-cover"
                                />
                            @elseif(str_starts_with($file['mime_type'], 'video/'))
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <x-filament::icon icon="heroicon-o-film" class="w-16 h-16" />
                                    <span class="text-xs mt-2">Video</span>
                                </div>
                            @elseif($file['mime_type'] === 'application/pdf')
                                <div class="flex flex-col items-center justify-center text-red-400">
                                    <x-filament::icon icon="heroicon-o-document-text" class="w-16 h-16" />
                                    <span class="text-xs mt-2">PDF</span>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <x-filament::icon icon="heroicon-o-document" class="w-16 h-16" />
                                    <span class="text-xs mt-2">File</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- File Info -->
                        <div class="p-3 space-y-2">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $file['name'] }}">
                                {{ $file['name'] }}
                            </h4>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ number_format($file['size'] / 1024, 2) }} KB</span>
                                <span>{{ \Carbon\Carbon::parse($file['created_at'])->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <!-- View/Download -->
                            <a 
                                href="{{ Storage::disk($file['disk'])->url($file['collection_name'] . '/' . $file['file_name']) }}" 
                                target="_blank"
                                class="p-2 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                title="View/Download"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-5 h-5 text-gray-700 dark:text-gray-300" />
                            </a>
                            
                            <!-- Delete -->
                            <button 
                                wire:click="deleteFile({{ $file['id'] }})"
                                wire:confirm="Are you sure you want to delete this file?"
                                class="p-2 bg-white dark:bg-gray-800 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition"
                                title="Delete"
                            >
                                <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5 text-red-600 dark:text-red-400" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>