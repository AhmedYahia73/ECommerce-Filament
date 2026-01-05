<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FooterSettings extends Settings
{
    public ?string $logo;
    public ?string $fav_icon;
    public ?string $primary_color;

    public static function group(): string
    {
        return 'main_settings';
    }
}