<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required()
                ->validationMessages([
                    'required' => 'Name is required',
                ])
                ->placeholder('Enter email'),
                Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
                TextInput::make('email')
               ->email()
                ->required()
                ->placeholder('Enter E-mail')
                ->unique(ignoreRecord: true) 
                ->validationMessages([
                    'required' => 'E-mail is required',
                    'unique' => 'This email is already registered',
                    'email' => 'Please enter a valid email address',
                ]),
                TextInput::make('password')
                ->password() // لإخفاء الأحرف أثناء الكتابة
                ->revealable() // يضيف أيقونة العين لإظهار الباسورد (اختياري)
                ->required(fn (string $operation): bool => $operation === 'create') // إجبارية في الإضافة فقط
                ->dehydrated(fn (?string $state) => filled($state)) // لا ترسل البيانات لقاعدة البيانات إذا كان الحقل فارغاً
                ->validationMessages([
                    'required' => 'Password is required',
                ])
                ->placeholder('Enter Password'),
                FileUpload::make('avatar_url')
                ->image()
                ->directory('avatars')
                ->required()
                ->validationMessages([
                    'required' => 'Image is required',
                    'image' => 'You must upload image',
                ])
                ->columnSpanFull(),
            ]);
    }
}
