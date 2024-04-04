<?php

namespace App\Livewire;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

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


            $photoModel = Photo::create([
                'path' => $image->basename,
                'user_id' => auth()->id(),
                'meta' => $meta,
                'photo_date' => (isset($this->dates[$key])) ? $this->dates[$key] : date('Y-m-d'),
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
