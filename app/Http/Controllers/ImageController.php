<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\pivot\PhotoTag;
use App\Models\pivot\PostPhoto;
use App\Models\pivot\UsersTags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getImage($uuid)
    {

        $photo = Photo::withTrashed()->where('uuid', $uuid)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path) || !Auth::check()) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }

    public function getPublicCover($uuid)
    {
        $photo = Photo::withTrashed()->where('uuid', $uuid)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }


    public function publicBlog($uuid)
    {

        $postPhoto = PostPhoto::where('uuid', $uuid)->firstOrFail();

        $photo = Photo::where('id', $postPhoto->photo_id)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type]);
    }

    public function getPublicImage($uuid)
    {
        $photoTag = PhotoTag::where('uuid', $uuid)->firstOrFail();

        $photo = Photo::where('id', $photoTag->photo_id)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path)) {
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

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('images'), $imageName);

        return response()->json(['success' => $imageName]);
    }
}
