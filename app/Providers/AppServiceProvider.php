<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(request()->is('admin/my-profile')){
            if (Schema::hasTable('categories')) {
                
                // جلب البيانات من الموديل
                $categories = Category::pluck('name', 'id')->toArray();

                // حقن الحقل داخل الـ Plugin برمجياً
                config()->set('filament-edit-profile.custom_fields', [
                    'category_id' => [
                        'type' => 'select',
                        'label' => 'Category',
                        'placeholder' => 'Select Category',
                        'options' => $categories, // هنا ستظهر البيانات لأننا في الـ boot
                        'required' => true,
                        'searchable' => true,
                        'preload' => true,
                        'column_span' => 'full',
                    ],
                ]);
            }
        }
    }
}
