<?php

namespace App\Livewire;

use App\Models\Photo;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;
use Intervention\Image\Facades\Image;

class FileUploads extends ModalComponent
{
    use WithFileUploads;

    #[Validate('image|max:7000')] // 10MB Max
    public $photos = [];
    public $dates = [];

    public $limit = 0;
    public $count = 0;
    public $error = '';


    public function updatedPhotos()
    {

        $this->limit = auth()->user()->photo_limit;
        $this->count = auth()->user()->photos()->count();

        if ($this->count >= $this->limit) {
            $this->error = __('You have reached your photo limit');
            return;
        }


        foreach ($this->photos as $key => $photo) {


            $image = Image::make($photo->path())
                ->resize(1280, 1280, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $meta = Image::make($photo->getRealPath())->exif();


            //check is the directory exists
            if (!file_exists(storage_path('app/photos/' . auth()->id()))) {
                mkdir(storage_path('app/photos/' . auth()->id()), 0777, true);
            }


            $storagePath = storage_path('app/photos/' . auth()->id() . '/' . $image->basename);

            $image->save($storagePath);


            $date = date('Y-m-d');
            if (isset($meta['DateTimeOriginal'])) {
                $date = date('Y-m-d', strtotime($meta['DateTimeOriginal']));
            } elseif (isset($meta['DateTimeDigitized'])) {
                $date = date('Y-m-d', strtotime($meta['DateTimeDigitized']));
            } elseif (isset($this->dates[$key])) {
                $date = $this->dates[$key];
            }

            $photoModel = Photo::create([
                'uuid' => (string) Str::uuid(),
                'path' => $image->basename,
                'user_id' => auth()->id(),
                'meta' => $meta,
                'photo_date' => $date,
            ]);

            $photo->delete();

            $this->photos = [];

            $this->reset();

            $this->dispatch('appendPhoto2', $photoModel);
        }
    }

    public function addDates($dates)
    {

        $this->dates = $dates;
    }

    public function render()
    {
        return view('livewire.file-uploads');
    }
}
