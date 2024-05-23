<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\pivot\PhotoTag;
use App\Models\pivot\PostPhoto;
use App\Models\pivot\UsersTags;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function getImage(string $uuid, string $size = null)
    {

        $photo = Photo::withTrashed()->where('uuid', $uuid)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if ($size) {
            $size = $this->getSize($size);

            $cache = 'photos/' . $photo->user_id . '/cache/' . $photo->path;

            if (!Storage::exists($cache)) {

                $storagePath = storage_path('app/' . $path);

                $image = Image::make($storagePath)->fit($size[0], $size[1]);

                $storagePath = storage_path('app/' . $cache);

                $image->save($storagePath);
            }

            $path = 'photos/' . $photo->user_id . '/cache/' . $photo->path;
        } else {
            $path = 'photos/' . $photo->user_id . '/' . $photo->path;
        }

        if (!Storage::exists($path) || !Auth::check()) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, ['Content-Type' => $type, 'Cache-Control' => 'max-age=31536000']);
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

    private function getSize($size): array
    {
        $s =   match ($size) {
            160 => '160x160',
            default => '160x160',
        };

        return explode('x', $s);
    }
}
