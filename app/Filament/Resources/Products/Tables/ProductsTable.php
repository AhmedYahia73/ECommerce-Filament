<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn; 
use Filament\Tables\Columns\ImageColumn; 
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                ImageColumn::make('image')
                ->label('image')
                ->circular() // لجعل الصورة دائرية (اختياري)
                ->disk('public') // تأكد من مطابقة الـ Disk المستخدم في الـ Upload
                ->width(50) // تحديد عرض الصورة في الجدول
                ->height(50),
                TextColumn::make('offer_price'),
                TextColumn::make('start_date')
                ->label('Offer From'),
                TextColumn::make('end_date')
                ->label('Offer To'),
                TextColumn::make('price'),
            ])
            ->filters([ 
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('gallery')
                ->label('Gallery')
                ->icon('heroicon-o-photo')
                ->color('warning')
                ->mountUsing(fn ($form, $record) => $form->fill($record->toArray())) // ملء النموذج ببيانات المنتج الحالي
                ->form([
                    FileUpload::make('gallery') // استبدل 'gallery_images' باسم الحقل الموجود في قاعدة بياناتك
                        ->label('Product Gallery')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->appendFiles()
                        ->directory('products/gallery')
                        ->imageEditor()
                        ->columnSpanFull(),
                ])
                ->action(function ($record, array $data) {
                    // حفظ الصور الجديدة في نفس السجل (Record)
                    $record->update($data);
                })
                ->modalSubmitActionLabel('Save Changes')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
