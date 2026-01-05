<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput; 
use Filament\Schemas\Components\Section; 
use Filament\Forms\Components\Toggle;


use Filament\Schemas\Schema;

class UnitForm
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
                    
                    Toggle::make('status')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true),
                ])
                ->collapsible(),
            ]);
    }
}
