<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\ViewField;

use Filament\Actions\Action;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Forms\Set;

class ProductForm
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
                ->placeholder('Enter name'),

                TextInput::make('price')
                ->required()->numeric() 
                ->validationMessages([
                    'required' => 'price is required',
                    'numeric' => 'price must be number',
                ])
                ->placeholder('Enter price'),

                TextInput::make('quantity')
                ->required()->numeric() 
                ->validationMessages([
                    'required' => 'Quintity of Packets is required',
                    'numeric' => 'Quintity of Packets must be number',
                ])
                ->placeholder('Enter Quintity of Packets'),
                // ->dehydrateStateUsing(fn ($state, $get) => $state * ($get('quantity_at_packet') ?? 1)),

                TextInput::make('quantity_at_packet')
                ->required()->numeric() 
                ->validationMessages([
                    'required' => 'Quintity in Packet is required',
                    'numeric' => 'Quintity in Packet must be number',
                ])
                ->placeholder('Enter Quintity in Packet'),

                TextInput::make('price')
                ->required()->numeric() 
                ->validationMessages([
                    'required' => 'price is required',
                    'numeric' => 'price must be number',
                ])
                ->placeholder('Enter price'),

                Textarea::make('short_description')
                ->placeholder('Enter Short description')
                ->rows(2),

                Textarea::make('description')
                ->placeholder('Enter Long description')
                ->rows(7),
// حقل لرفع صورة واحدة
// هذا الحقل سيسمح لك برفع صورة جديدة أو اختيار صورة موجودة
// TextInput::make('image_url')
//     ->label('Product Image')
//     ->suffixAction(
//         Action::make('selectImage')
//             ->icon('heroicon-m-photo')
//             ->label('Select from Media')
//             ->modalHeading('Select Media Item')
//             ->modalWidth('4xl')
//             ->modalSubmitActionLabel('Select Image')
//             ->modalCancelActionLabel('Cancel')
//             ->form([
//                 ViewField::make('media_picker')
//                     ->view('filament.forms.components.media-picker')
//             ])
//             ->action(function (array $data, Action $action) {
//                 if (isset($data['media_picker'])) {
//                     $mediaItem = \App\Models\MediaItem::find($data['media_picker']);
//                     if ($mediaItem) {
//                         $action->fillForm([
//                             'image_url' => $mediaItem->getUrl(),
//                         ]);
//                     }
//                 }
//             })
//     ),
            TextInput::make('image') 
            ->id('media-path-input') 
            ->label('File Path') 
            ->live() // مهم جداً لجعل المعاينة تتحدث فوراً
            ->afterStateUpdated(fn ($state) => $state)// معرف للوصول إليه بسهولة
            ->hintAction(
                Action::make('open_picker')
                    ->label('Select Media')
                    ->icon('heroicon-m-folder-open')
                    ->modalHeading('Media Library Picker')
                    ->modalWidth('4xl')
                    ->modalContent(fn () => view('filament.components.media-picker-logic', [
                        'folders' => \Spatie\MediaLibrary\MediaCollections\Models\Media::query()
                            ->select('collection_name')
                            ->distinct()
                            ->pluck('collection_name'),
                        'allFiles' => \Spatie\MediaLibrary\MediaCollections\Models\Media::all()->map(fn($f) => [
                            'folder' => $f->collection_name,
                            'name' => $f->file_name,
                            'url' => url('storage/' . $f->collection_name . '/' . $f->file_name ),
                            'path' => $f->collection_name . '/' . $f->file_name 
                        ]),
                    ]))
                    ->modalSubmitAction(false)
                    ->registerModalActions([
                Action::make('uploadToFolder')
                    ->label('رفع ملف جديد')
                    ->form([
                        \Filament\Forms\Components\FileUpload::make('new_file')
                            ->label('اختر الملف')
                            ->required()
                            ->disk('public')
                            ->directory('temp') 
                    ])
                    ->action(function (array $data, array $arguments, $livewire) {
                        $folder = $arguments['folder'];
                        $file = $data['new_file'];
                        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($file);
                        $mediaModel = new Media();

                        // لوجيك الحفظ (نفس الكود السابق الخاص بسجل الميديا)
                        $mediaModel = new \Spatie\MediaLibrary\MediaCollections\Models\Media();
                        $mediaModel->model_type = 'App\Models\Media';
                        $mediaModel->model_id = 0;
                        $mediaModel->collection_name = $folder;
                        $mediaModel->name = pathinfo($file, PATHINFO_FILENAME);
                        $mediaModel->file_name = basename($file);
                        $mediaModel->mime_type = \Illuminate\Support\Facades\File::mimeType($fullPath);
                        $mediaModel->disk = 'public';
                        $mediaModel->size = \Illuminate\Support\Facades\Storage::disk('public')->size($file);
                        $mediaModel->uuid = \Illuminate\Support\Str::uuid();
                        $mediaModel->manipulations = [];
                        $mediaModel->custom_properties = [];
                        $mediaModel->generated_conversions = [];
                        $mediaModel->responsive_images = [];
                        $mediaModel->order_column = 1;
                        $mediaModel->save();
                        
                        // لوجيك الحفظ (نفس الكود الس  

                        // نقل الملف
                        \Illuminate\Support\Facades\Storage::disk('public')->move($file, $folder . '/' . basename($file));

                        \Filament\Notifications\Notification::make()->title('Uploaded!')->success()->send();
                        $livewire->dispatch('refreshMediaPicker');
                    })
            ]),
            )
            ->helperText(fn ($state) => view('filament.components.image-preview-helper', [
                'state' => $state
            ])),

                Select::make('category_id')
                ->label('Category')
                ->required()
                ->relationship('category', 'name', function ($query) {
                    $query->where('status', 1);
                })
                ->placeholder('Select a category'),

                Select::make('unit_id')
                ->label('Unit')
                ->required()
                ->relationship('unit', 'name', function ($query) {
                    $query->where('status', 1);
                })
                ->placeholder('Select a category'),


                TextInput::make('offer_price')
                ->numeric() 
                ->validationMessages([
                    'numeric' => 'offer price must be number',
                ])
                ->placeholder('Enter offer price'),

                DatePicker::make('start_date')
                ->label('Start Date') 
                ->placeholder('Select start offer date')
                //->default(today()) 
                //->maxDate(now()->addYear())
                ,
                DatePicker::make('end_date')
                ->label('End Date') 
                ->placeholder('Select end offer date'),
                //->default(today()) 
                //->maxDate(now()->addYear())

                Section::make('Product Gallery')
                ->description('Upload multiple images for this product')
                ->schema([
                    
                    // حقل لرفع عدة صور (Gallery)
                    SpatieMediaLibraryFileUpload::make('gallery')
                        ->collection('gallery')
                        ->label('Gallery Images')
                        ->multiple() // يسمح برفع أكثر من صورة
                        ->image() // يقبل الصور فقط
                        ->reorderable() // يسمح بترتيب الصور بالسحب والإفلات (اختياري)
                        ->appendFiles() // يسمح بإضافة صور جديدة للصور القديمة
                        ->directory('product-gallery') // المجلد الذي ستخزن فيه الصور
                        ->imageEditor() // يضيف زر لتعديل الصور (اختياري)
                        ->downloadable() // يضيف زر تحميل (اختياري)
                        ->columnSpanFull() // ليأخذ عرض النموذج بالكامل
                        ->reorderable() // السماح بترتيب الصور بالسحب والإفلات
                        ->panelLayout('grid'), 
                
                ])
                ->collapsible()
            ]);
    }
}

