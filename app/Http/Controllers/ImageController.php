<?php

namespace App\Http\Controllers;

use App\Models\pivot\UsersTags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;

class ImageController extends Controller
{
    public function getImage(Photo $photo)
    {
        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path) || !Auth::check()) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }

    public function getUserImage(UsersTags $usersTags, Photo $photo)
    {

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path) || !$usersTags) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }
}