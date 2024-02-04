<?php

namespace App\Http\Controllers;

use App\Models\pivot\UsersTags;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function getImage($filename)
    {
        $path = 'photos/' . $filename;

        if (!Storage::exists($path) || !Auth::check()) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }

    public function getUserImage($userTagId, $filename)
    {

        $userTag = UsersTags::find($userTagId);


        $path = 'photos/' . $filename;

        if (!Storage::exists($path) || !$userTag) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }
}
