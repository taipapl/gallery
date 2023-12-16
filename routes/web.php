<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('files/{filename}', [ImageController::class, 'getImage'])->name('get.image');

Route::view('/', 'welcome');

Route::view('albums', 'albums')
    ->middleware(['auth', 'verified'])
    ->name('albums');

Volt::route('album/{tag}', 'album')
    ->middleware(['auth', 'verified'])
    ->name('album');

Route::view('photos', 'photos')
    ->middleware(['auth', 'verified'])
    ->name('photos');

Route::view('video', 'video')
    ->middleware(['auth', 'verified'])
    ->name('video');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
