<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action; 

use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput; 
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Filament\Resources\Media\MediaResource;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table 
        ->query(
            Media::query()
                ->selectRaw('MIN(id) as id, collection_name, COUNT(*) as files_count, MAX(created_at) as latest_file')
                ->groupBy('collection_name')
        )
        ->columns([
            IconColumn::make('icon')
                ->icon('heroicon-o-folder')
                ->size('lg')
                ->color('warning'),
            
            TextColumn::make('collection_name')
                ->label('Folder Name')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->size('lg'),
            
            TextColumn::make('files_count')
                ->label('Files')
                ->badge()
                ->color('info')
                ->suffix(' files'),
            
            TextColumn::make('latest_file')
                ->label('Last Updated')
                ->dateTime()
                ->sortable(),
        ])
        ->actions([
            Action::make('view_files')
                ->label('View Files')
                ->icon('heroicon-o-eye')
                ->modalContent(fn ($record) => view('filament.resources.media-folder.pages.manage-folder-files', [
                    'files' => Media::where('collection_name', $record->collection_name)
                                ->where('file_name', '!=', '.placeholder')
                                ->get(),
                    'folder' => $record->collection_name
                ]))
                ->modalSubmitAction(false) // لإخفاء زر الحفظ في المودال
                ->modalWidth('7xl'), // جعل النافذة واسعة لعرض الصور
            ]);
    }
     
}
