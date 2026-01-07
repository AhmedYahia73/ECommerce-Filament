<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\Media\Pages\MediaFolderPage;

Route::get('/', function () {
    return view('welcome');
});
