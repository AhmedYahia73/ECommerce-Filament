<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn; 
use Filament\Tables\Columns\ImageColumn; 
use Filament\Forms\Components\FileUpload;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
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
            
            ->headerActions([ 
                ExportAction::make('export')
                ->exports([
                    ExcelExport::make()
                        ->withColumns([ 
                            Column::make('name')->heading('Product Name'),
                            Column::make('description')->heading('Description'),
                            Column::make('price')->heading('Price'),
                            Column::make('category_id')->heading('Category ID'),
                            Column::make('unit_id')->heading('Unit ID'),
                            Column::make('short_description')->heading('Short Description'),
                            Column::make('offer_price')->heading('Offer Price'),
                            Column::make('start_date')->heading('Start Date'),
                            Column::make('end_date')->heading('End Date'),
                            Column::make('quantity')->heading('Quantity'),
                            Column::make('quantity_at_packet')->heading('Quantity AT Packet'),
                        ])
                        ])
                    ->label('Export Data')
                    ,
                    ExportAction::make('NEW_export')
                    ->exports([
                        ExcelExport::make()
                        ->withColumns([ 
                            Column::make('name')->heading('Product Name'),
                            Column::make('description')->heading('Description'),
                            Column::make('price')->heading('Price'),
                            Column::make('category_id')->heading('Category ID'),
                            Column::make('unit_id')->heading('Unit ID'),
                            Column::make('short_description')->heading('Short Description'),
                            Column::make('offer_price')->heading('Offer Price'),
                            Column::make('start_date')->heading('Start Date'),
                            Column::make('end_date')->heading('End Date'),
                            Column::make('quantity')->heading('Quantity'),
                            Column::make('quantity_at_packet')->heading('Quantity AT Packet'),
                        ])
                        ->modifyQueryUsing(fn ($query) => $query->where('id', 0))
                ])
                ->label('Export Empty File')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
