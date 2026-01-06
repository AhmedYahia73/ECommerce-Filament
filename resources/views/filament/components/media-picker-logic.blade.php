 

<div x-data="{ 
    view: 'folders', 
    selectedFolder: '', 
    allFiles: {{ $allFiles->toJson() }}
}" 
x-effect="allFiles = @js($allFiles)" 
class="p-4 min-h-[350px]">

    {{-- واجهة المجلدات --}}
    <div x-show="view === 'folders'" class="grid grid-cols-3 sm:grid-cols-4 gap-4">
        @foreach($folders as $folderName)
            <div @click="selectedFolder = '{{ $folderName }}'; view = 'files'" 
                 class="flex flex-col items-center p-4 border rounded-xl cursor-pointer hover:bg-primary-50 transition group shadow-sm">
                <x-filament::icon icon="heroicon-s-folder" class="w-14 h-14 text-yellow-500" />
                <span class="mt-2 text-xs font-bold truncate w-full text-center">{{ $folderName }}</span>
            </div>
        @endforeach
    </div>

    {{-- واجهة الملفات --}}
    <div x-show="view === 'files'" x-cloak>
        <div class="flex items-center justify-between mb-6 pb-2 border-b gap-2">
            <button @click="view = 'folders'" class="text-sm font-medium text-primary-600 flex items-center">
                <x-filament::icon icon="heroicon-m-arrow-left" class="w-4 h-4 mr-1" />
                المجلدات
            </button>

            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400" x-text="selectedFolder"></span>
                
                {{-- زر الرفع الجديد --}}
<x-filament::button 
    size="xs" 
    color="success" 
    icon="heroicon-m-arrow-up-tray"
    {{-- التعديل هنا: نستخدم mountAction مباشرة --}}
    @click="$wire.mountAction('uploadToFolder', { folder: selectedFolder })"
>
    Upload File
</x-filament::button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <template x-for="file in allFiles.filter(f => f.folder === selectedFolder)" :key="file.path">
                <div @click="$wire.set('data.image', file.path); close();" 
                     class="group border rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-primary-500 transition shadow-sm bg-white dark:bg-gray-900">
                    
                    <div class="aspect-square w-full overflow-hidden bg-gray-50 flex items-center justify-center">
                        <template x-if="file.url.match(/\.(jpeg|jpg|gif|png|webp)$/i)">
                            <img :src="file.url" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!file.url.match(/\.(jpeg|jpg|gif|png|webp)$/i)">
                            <x-filament::icon icon="heroicon-o-document" class="w-10 h-10 text-gray-400" />
                        </template>
                    </div>

                    <div class="p-2 text-[10px] truncate text-center font-medium" x-text="file.name"></div>
                </div>
            </template>
        </div>
    </div>
</div>