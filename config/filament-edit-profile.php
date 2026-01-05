<?php

// نجلب البيانات هنا للتأكد من أنها مصفوفة بسيطة (Array) وليس دالة
// سنستخدم try/catch لتجنب انهيار الموقع إذا لم تكن قاعدة البيانات جاهزة
try {
    $categories = \Illuminate\Support\Facades\Schema::hasTable('categories') 
        ? \App\Models\Category::pluck('name', 'id')->toArray() 
        : [];
} catch (\Exception $e) {
    $categories = [];
}

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'category_id' => [ // يجب أن يكون هذا اسم العمود في جدول الـ users
            'type' => 'select',
            'label' => 'Category',
            'placeholder' => 'Select Category',
            'required' => true,
            'options' => $categories, // نمرر المصفوفة الجاهزة هنا
            'searchable' => true,
            'native' => false,
            'column_span' => 'full',
            'rules' => ['required'],
        ],
    ],
];