<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

Route::get('files/{photo}', [ImageController::class, 'getImage'])->name('get.image');

Route::get('user_file/{usersTags}/{photo}', [ImageController::class, 'getUserImage'])->name('get.user_image');

Route::view('/', 'welcome')
    ->name('home');

Volt::route('public_profile/{public_url}', 'front.profil')
    ->name('public_profile');

Volt::route('public_blog/{public_url}', 'front.blog')
    ->name('public_blog');

Volt::route('public_album/{public_url}', 'front.album')
    ->name('public_album');

Volt::route('user_album/{user_url}', 'front.guest')
    ->name('user_album');

Volt::route('albums', 'albums')
    ->middleware(['auth', 'verified'])
    ->name('albums.list');

Volt::route('albumsArchived', 'albumsArchived')
    ->middleware(['auth', 'verified'])
    ->name('albums.archived');

Volt::route('album/{tag}', 'album')
    ->middleware(['auth', 'verified'])
    ->name('albums.album');

Volt::route('show/{photo}', 'show')
    ->middleware(['auth', 'verified'])
    ->name('photos.show');

Volt::route('photos', 'photos')
    ->middleware(['auth', 'verified'])
    ->name('photos.list');

Volt::route('photosArchived', 'photosArchived')
    ->middleware(['auth', 'verified'])
    ->name('photos.archived');

Volt::route('video', 'video.list')
    ->middleware(['auth', 'verified'])
    ->name('video.list');

Volt::route('video/add', 'video.add')
    ->middleware(['auth', 'verified'])
    ->name('video.add');

Volt::route('shared', 'shared.list')
    ->middleware(['auth', 'verified'])
    ->name('shared.list');

Volt::route('shared/{tag}', 'shared.show')
    ->middleware(['auth', 'verified'])
    ->name('shared.show');

Volt::route('emails', 'emails')
    ->middleware(['auth', 'verified'])
    ->name('blog.emails');

Volt::route('blog', 'blog')
    ->middleware(['auth', 'verified'])
    ->name('blog.list');

Volt::route('blog/create', 'blog.create')
    ->middleware(['auth', 'verified'])
    ->name('blog.create');

Volt::route('blog/{post}', 'blog.edit')
    ->middleware(['auth', 'verified'])
    ->name('blog.edit');

Volt::route('trash', 'trash')
    ->middleware(['auth', 'verified'])
    ->name('trash');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
