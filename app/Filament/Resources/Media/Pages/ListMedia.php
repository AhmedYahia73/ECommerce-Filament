<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action; 
use Filament\Forms\Components\TextInput; 
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_folder')
                ->label('Create New Folder')
                ->icon('heroicon-o-folder-plus')
                ->color('success')
                ->form([
                    TextInput::make('folder_name')
                        ->label('Folder Name')
                        ->required()
                        ->unique(Media::class, 'collection_name')
                        ->placeholder('e.g., products, documents, avatars')
                        ->helperText('Enter a unique name for your folder'),
                ])
                ->action(function (array $data) {
                    // إنشاء ملف placeholder لإنشاء المجلد
                    $media = new Media();
                    $media->model_type = 'App\\Models\\Media';
                    $media->model_id = 0;
                    $media->collection_name = $data['folder_name'];
                    $media->name = '.placeholder';
                    $media->file_name = '.placeholder';
                    $media->mime_type = 'text/plain';
                    $media->disk = 'public';
                    $media->size = 0;
                    $media->manipulations = [];
                    $media->custom_properties = [];
                    $media->generated_conversions = [];
                    $media->responsive_images = [];
                    $media->order_column = 1;
                    $media->uuid = \Illuminate\Support\Str::uuid();
                    $media->save();
                })
                ->successNotificationTitle('Folder created successfully')
                ->successRedirectUrl(fn () => MediaResource::getUrl('index'))
                ->modalWidth('md'),
        ];
    }
}
