<?php
namespace App\Filament\Resources\Media\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MediaFolderPage extends Page
{
    protected string $view = 'filament.pages.media-folder-page';

    protected static bool $shouldRegisterNavigation = true; 


    public string $folder; 
    public array $files = [];

    public function mount(): void
    {
        $this->folder = $_GET['folder'];
        $this->loadFiles();
    }

    public function loadFiles(): void
    {
        $this->files = Media::where('collection_name', $this->folder)->get()->toArray();
    }
 
    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload_files')
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
                        ->acceptedFileTypes(['image/*','application/pdf','video/*']),
                ])
                ->action(function (array $data) {
                    foreach ($data['files'] as $filePath) {
                        $disk = 'public';
                        $fileName = basename($filePath);

                        $media = new Media();
                        $media->model_type = 'App\\Models\\Media';
                        $media->model_id = 0;
                        $media->collection_name = $this->folder;
                        $media->name = pathinfo($fileName, PATHINFO_FILENAME);
                        $media->file_name = $fileName;
                        $media->mime_type = File::mimeType(Storage::disk($disk)->path($filePath));
                        $media->disk = $disk;
                        $media->manipulations = [];
                        $media->custom_properties = [];
                        $media->generated_conversions = [];
                        $media->responsive_images = [];
                        $media->size = Storage::disk($disk)->size($filePath);
                        $media->uuid = Str::uuid();
                        $media->save();

                        Storage::disk($disk)->move($filePath, $this->folder.'/'.$fileName);
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

    public function deleteFile($id){

        Media::
        where('id', $id)->delete();
    }
}
