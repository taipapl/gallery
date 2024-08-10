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

Route::get('image/{photo}/{size?}', [ImageController::class, 'getImage'])->name('get.image');

Route::get('public/cover/{photo}', [ImageController::class, 'getPublicCover'])->name('get.cover');

Route::get('public/post/{photo}', [ImageController::class, 'publicBlog'])->name('get.blog');

Route::get('user_file/{usersTags}/{photo}', [ImageController::class, 'getUserImage'])->name('get.user_image');

Route::view('/', 'welcome')
    ->name('home');

Volt::route('public/profile/{public_url}', 'public.profil')
    ->name('public.profile');

Volt::route('public/blog/{public_url}', 'public.blog')
    ->name('public.blog');

Volt::route('public/album/{public_url}', 'public.album')
    ->name('public.album');

Route::get('public/{photo}/{size?}', [ImageController::class, 'getPublicImage'])->name('get.public');

Volt::route('user/album/{user_url}', 'user.album')
    ->name('user.album');

Volt::route('user/profil/{user_url}', 'user.profil')
    ->name('user.profil');


Volt::route('albums', 'albums.list')
    ->middleware(['auth', 'verified'])
    ->name('albums.list');

Volt::route('album/{uuid}', 'albums.show')
    ->middleware(['auth', 'verified'])
    ->name('albums.show');

Volt::route('album/add/{uuid}', 'albums.add')
    ->middleware(['auth', 'verified'])
    ->name('albums.add');

Volt::route('album/share/{uuid}', 'albums.share')
    ->middleware(['auth', 'verified'])
    ->name('albums.share');

Volt::route('show/{uuid}', 'show')
    ->middleware(['auth', 'verified'])
    ->name('photos.show');

Volt::route('photos', 'photos.photos')
    ->middleware(['auth', 'verified'])
    ->name('photos.list');

Volt::route('video', 'video.list')
    ->middleware(['auth', 'verified'])
    ->name('video.list');

Volt::route('video/add', 'video.add')
    ->middleware(['auth', 'verified'])
    ->name('video.add');

Volt::route('shared', 'shared.list')
    ->middleware(['auth', 'verified'])
    ->name('shared.list');

Volt::route('shared/{uuid}', 'shared.show')
    ->middleware(['auth', 'verified'])
    ->name('shared.show');



Volt::route('emails', 'emails')
    ->middleware(['auth', 'verified'])
    ->name('blog.emails');

Volt::route('blog', 'blog.blog')
    ->middleware(['auth', 'verified'])
    ->name('blog.list');

Volt::route('blog/create', 'blog.create')
    ->middleware(['auth', 'verified'])
    ->name('blog.create');

Volt::route('blog/{uuid}', 'blog.edit')
    ->middleware(['auth', 'verified'])
    ->name('blog.edit');

Volt::route('blog/add/{uuid}', 'blog.add')
    ->middleware(['auth', 'verified'])
    ->name('blog.add');

Volt::route('trash', 'trash')
    ->middleware(['auth', 'verified'])
    ->name('trash');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';