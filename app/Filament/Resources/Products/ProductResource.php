<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $filters = request()->input('tableFilters');

        if (isset($filters['category_id']['value'])) {
            $query->where('category_id', $filters['category_id']['value']);
        }

        return $query;
    }
    
    private function getMediaOptionHtml(Media $media): string
    {
        return '
            <div class="flex items-center gap-3">
                <img src="' . $media->getUrl() . '" class="w-12 h-12 rounded shadow-sm" />
                <div class="flex flex-col">
                    <span class="font-bold text-sm">' . $media->file_name . '</span>
                    <span class="text-xs text-gray-400">' . number_format($media->size / 1024, 2) . ' KB</span>
                </div>
            </div>
        ';
    }

}

