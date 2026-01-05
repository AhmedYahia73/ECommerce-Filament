<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use App\Settings\FooterSettings;

class ManageFooter extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = FooterSettings::class;
 
    // الأيقونة الافتراضية في v4
    // $navigationIcon 

    // العنوان الذي يظهر في القائمة الجانبية
    protected static ?string $navigationLabel = 'Settings Section';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding & Identity')
                ->description('Main Settings')
                ->schema([
                    FileUpload::make('logo')
                        ->label('Website Logo')
                        ->image()
                        ->avatar() // لجعل عرض الصورة دائري في لوحة التحكم
                        ->directory('site-settings')
                        ->visibility('public'),

                    FileUpload::make('fav_icon')
                        ->label('Favicon (ICO/PNG)')
                        ->image()
                        ->avatar()
                        ->directory('site-settings')
                        ->visibility('public'),

                    ColorPicker::make('primary_color')
                        ->label('Brand Color')
                        ->hex() // لضمان حفظ القيمة بصيغة HEX
                        ->default('#3b82f6'),
                ])
                ->columns(3), // عرض الثلاثة حقول بجانب بعضهم في الشاشات الكبيرة
            ]);
    }
}
