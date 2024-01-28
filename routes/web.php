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

Volt::route('public_profile/{public_url}', 'front.profil')
    ->name('public_profile');

Volt::route('public_album/{public_url}', 'front.album')
    ->name('public_album');

Volt::route('albums', 'albums')
    ->middleware(['auth', 'verified'])
    ->name('albums.list');

Volt::route('album/{tag}', 'album')
    ->middleware(['auth', 'verified'])
    ->name('albums.album');

Volt::route('show/{photo}', 'show')
    ->middleware(['auth', 'verified'])
    ->name('show');

Volt::route('photos', 'photos')
    ->middleware(['auth', 'verified'])
    ->name('photos');

Volt::route('video', 'video')
    ->middleware(['auth', 'verified'])
    ->name('video');

Volt::route('shared', 'shared')
    ->middleware(['auth', 'verified'])
    ->name('shared');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




require __DIR__ . '/auth.php';
