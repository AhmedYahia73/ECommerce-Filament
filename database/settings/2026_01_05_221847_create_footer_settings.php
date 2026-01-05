<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // الطريقة الرسمية في الإصدارات الحديثة هي استخدام add مباشرة 
        // ولكن إذا كنت قد قمت بتشغيله سابقاً، يفضل مسح الكاش أو عمل Rollback
        $this->migrator->add('main_settings.logo', null);
        $this->migrator->add('main_settings.fav_icon', null);
        $this->migrator->add('main_settings.primary_color', '#ffffff');
    }

    public function down(): void
    {
        $this->migrator->delete('main_settings.logo');
        $this->migrator->delete('main_settings.fav_icon');
        $this->migrator->delete('main_settings.primary_color');
    }
};
