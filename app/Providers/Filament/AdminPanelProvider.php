<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Filament\Actions\Action;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use App\Models\Setting;
use App\Settings\FooterSettings;
use App\Filament\Resources\Media\Pages\MediaFolderPage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $color = app(FooterSettings::class)->primary_color;
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->registration()
            ->login() 
            ->colors([
                'primary' => $color ?? Color::Amber,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
                'yellow' => "#FFFF33",
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                FilamentEditProfilePlugin::make()
                    // الرابط (URL) الخاص بصفحة البروفايل، مثلاً: admin/my-profile
                    ->slug('my-profile')
                    
                    // العنوان الرئيسي الذي يظهر داخل الصفحة في الأعلى
                    ->setTitle('My Profile')
                    
                    // الاسم الذي يظهر في قائمة التنقل الجانبية (Sidebar)
                    ->setNavigationLabel('My Profile')
                    
                    // اسم المجموعة التي ستظهر تحتها الصفحة في القائمة الجانبية (لتنظيم الروابط)
                    ->setNavigationGroup('Group Profile')
                    
                    // الأيقونة التي تظهر بجانب الاسم في القائمة الجانبية (من مكتبة Heroicons)
                    ->setIcon('heroicon-o-user')
                    
                    // ترتيب ظهور الصفحة في القائمة (الأرقام الأصغر تظهر في الأعلى)
                    ->setSort(10)
                    
                    // شرط الوصول: هنا لا يسمح بدخول الصفحة إلا للمستخدم الذي يحمل ID رقم 1
                    ->canAccess(fn () => auth()->user()->id === 1)
                    
                    // إذا جعلته false، فسيتم إخفاء رابط الصفحة من القائمة الجانبية تماماً
                    ->shouldRegisterNavigation(false)
                    
                    // تفعيل إظهار قسم تعديل البريد الإلكتروني (Email)
                    ->shouldShowEmailForm()
                    
           
                   
                    // إخفاء أو إظهار خيار "حذف الحساب" (هنا تم إخفاؤه)
                    ->shouldShowDeleteAccountForm(false)
                    
                    // إظهار قسم إدارة توكنات Laravel Sanctum (إذا كنت تستخدمه لعمل API)
                    ->shouldShowSanctumTokens()
            
                    // إظهار قسم "جلسات المتصفح" لعرض الأجهزة المسجلة والخروج منها عن بعد
                    ->shouldShowBrowserSessionsForm()
                    
                    // إظهار حقل رفع الصورة الشخصية (Avatar)
                    ->shouldShowAvatarForm()
                    
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class, 
                MediaFolderPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label(fn() => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
                'settings' => \Filament\Navigation\MenuItem::make()
                    ->label('Store Settings')
                    ->url('/admin/manage-footer') // ضع رابط صفحة الإعدادات الخاصة بك هنا
                    ->icon('heroicon-m-cog-6-tooth'),
            ]);
    }
}
