<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn; 

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                ->label('image')
                ->circular() // لجعل الصورة دائرية (اختياري)
                ->disk('public') // تأكد من مطابقة الـ Disk المستخدم في الـ Upload
                ->width(50) // تحديد عرض الصورة في الجدول
                ->height(50),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                ToggleColumn::make('status')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                
                Action::make('view_products')
                ->label('View Products')
                ->icon('heroicon-o-shopping-bag')
                ->color('info')
                ->url(fn ($record) => ProductResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $record->id,
                        ],
                    ],
                ]))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
