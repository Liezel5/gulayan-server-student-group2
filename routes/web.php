<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;

Route::get('/', function () {
    return view('welcome');
});

// Plants management routes
Route::get('/plants', function () {
    return view('plants.index');
})->name('plants.index');
