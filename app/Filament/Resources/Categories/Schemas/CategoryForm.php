<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Info.')
                ->description('Enter Data of Category')
                //->aside() // يعرض العنوان والوصف على اليسار والحقول على اليمين
                ->schema([
                    TextInput::make('name')
                    ->required()
                    ->validationMessages([
                        'required' => 'Name is required',
                    ])
                    ->placeholder('Enter name'),

                    FileUpload::make('image')
                    ->label('Category Image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('categories')
                    ->placeholder('Upload Category image'),
                    
                    Toggle::make('status')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true),
                ])
                ->collapsible(), // يسمح للمستخدم بطي القسم
            ]);
    }
}
