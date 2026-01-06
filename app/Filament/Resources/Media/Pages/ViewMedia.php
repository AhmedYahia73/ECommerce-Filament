<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;


use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ViewMedia extends ViewRecord implements HasForms
{ 

    use InteractsWithForms;
    
    protected static string $resource = MediaResource::class;

    public string $view = 'filament.resources.media-folder.pages.manage-folder-files';
    
    public string $folder;
    
    public $files = [];

    // public function mount(string $record): void
    // {
    //     $this->folder = $record;
    //     $this->loadFiles();
    // }
    
    public function loadFiles(): void
    {
        $this->files = Media::where('collection_name', $this->folder)
            ->where('file_name', '!=', '.placeholder')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Folders')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(MediaResource::getUrl('index')),
                
            Actions\Action::make('upload_files')
                ->label('Upload Files')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('files')
                        ->label('Select Files')
                        ->multiple()
                        ->required()
                        ->maxFiles(20)
                        ->disk('public')
                        ->directory('media-temp')
                        ->preserveFilenames()
                        ->maxSize(10240)
                        ->acceptedFileTypes(['image/*', 'application/pdf', 'video/*'])
                        ->helperText('You can upload up to 20 files at once (Max 10MB each)'),
                ])
                ->action(function (array $data) {
                    foreach ($data['files'] as $file) {
                        $filePath = $file;
                        $disk = 'public';
                        
                        // الحصول على معلومات الملف
                        $fileName = basename($filePath);
                        $mimeType = File::mimeType(Storage::disk($disk)->path($filePath));
                        $fileSize = Storage::disk($disk)->size($filePath);
                        
                        // إنشاء سجل Media
                        $media = new Media();
                        $media->model_type = 'App\\Models\\Media';
                        $media->model_id = 0;
                        $media->collection_name = $this->folder;
                        $media->name = pathinfo($fileName, PATHINFO_FILENAME);
                        $media->file_name = $fileName;
                        $media->mime_type = $mimeType;
                        $media->disk = $disk;
                        $media->size = $fileSize;
                        $media->manipulations = [];
                        $media->custom_properties = [];
                        $media->generated_conversions = [];
                        $media->responsive_images = [];
                        $media->order_column = Media::where('collection_name', $this->folder)->max('order_column') + 1;
                        $media->uuid = Str::uuid();
                        $media->save();
                        
                        // نقل الملف إلى المكان النهائي
                        $finalPath = $this->folder . '/' . $fileName;
                        Storage::disk($disk)->move($filePath, $finalPath);
                    }
                    
                    $this->loadFiles();
                    
                    Notification::make()
                        ->title('Files uploaded successfully')
                        ->success()
                        ->send();
                })
                ->modalWidth('lg'),
        ];
    }
    
    public function deleteFile(int $mediaId): void
    {
        $media = Media::find($mediaId);
        
        if ($media && $media->collection_name === $this->folder) {
            // حذف الملف من الـ storage
            Storage::disk($media->disk)->deleteDirectory((string) $media->id);
            
            // حذف السجل من قاعدة البيانات
            $media->delete();
            
            $this->loadFiles();
            
            Notification::make()
                ->title('File deleted successfully')
                ->success()
                ->send();
        }
    }
    
    public function getTitle(): string
    {
        return "Files in '{$this->folder}' folder";
    }
    
    public function deleteFileAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('deleteFile')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $mediaId = $arguments['id'];
                $media = Media::find($mediaId);
                if ($media) {
                    $media->delete();
                    $this->loadFiles();
                    Notification::make()->title('Deleted')->success()->send();
                }
            });
    }
}
