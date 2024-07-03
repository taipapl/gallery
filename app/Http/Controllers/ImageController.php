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

        if ($photo->is_video) {
            return redirect()->away($photo->path);
        }



        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path) || !Auth::check()) {
            abort(404);
        }

        if ($size && !$photo->is_video) {

            $sizeArray = $this->getSize($size);

            $storagePath = storage_path('app/' . $path);

            $file =  Image::make($storagePath)->fit($sizeArray[0], $sizeArray[1]);

            $type = Storage::mimeType($path);

            return $file->response($type, 60);
        } else {

            $file = Storage::get($path);

            $type = Storage::mimeType($path);

            return Response::make($file, 200, ['Content-Type' => $type]);
        }
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

    public function getPublicImage($uuid, string $size = null)
    {

        $photoTag = PhotoTag::where('uuid', $uuid)->firstOrFail();

        $photo = Photo::where('id', $photoTag->photo_id)->firstOrFail();

        $path = 'photos/' . $photo->user_id . '/' . $photo->path;

        if (!Storage::exists($path)) {
            abort(404);
        }

        if ($size) {

            $sizeArray = $this->getSize($size);

            $storagePath = storage_path('app/' . $path);

            $file =  Image::make($storagePath)->fit($sizeArray[0], $sizeArray[1]);

            $type = Storage::mimeType($path);

            return $file->response($type, 60);
        } else {

            $file = Storage::get($path);

            $type = Storage::mimeType($path);

            return Response::make($file, 200, ['Content-Type' => $type, 'Cache-Control' => 'max-age=31536000']);
        }
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
            '160' => '160x160',
            '600' => '600x600',
            default => '160x160',
        };

        return explode('x', $s);
    }
}
