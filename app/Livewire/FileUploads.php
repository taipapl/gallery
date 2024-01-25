<?php

namespace App\Livewire;

use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

use Livewire\Attributes\Validate;
use Intervention\Image\ImageManager;
use LivewireUI\Modal\ModalComponent;
use Intervention\Image\Facades\Image;


class FileUploads extends ModalComponent
{

    use WithFileUploads;

    #[Validate('image|max:7000')] // 10MB Max
    public $photos = [];

    public $dates = [];

    public function updatedPhotos()
    {
        foreach ($this->photos as $key => $photo) {


            //dd($this->photos, $this->dates);


            //  stream_get_meta_data
            $originalDate =  exif_read_data($photo->path());

            //dd(date('d.m.Y H:i', $originalDate['FileDateTime']));

            $image = Image::make($photo->path())
                ->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $meta = Image::make($photo->getRealPath())->exif();

            $image = Image::make($photo->path())
                ->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $storagePath = storage_path('app/photos/' . $image->basename);

            $image->save($storagePath);


            //dd($meta, date('Y-m-d', $meta['FileDateTime']));

            $photoModel =  Photo::create([
                'label' => $photo->getClientOriginalName(),
                'path' => $image->basename,
                'user_id' => auth()->id(),
                'meta' => $meta,
                'photo_date' => (isset($this->dates[$key])) ? $this->dates[$key] :  date('Y-m-d'),
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