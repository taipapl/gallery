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

    #[Validate('image|max:10000')] // 10MB Max
    public $photos = [];

    public function save()
    {
        $this->validate([
            'photos.*' => 'required|image|max:10000'
        ]);

        foreach ($this->photos as $photo) {

            //dd($photo);

            //exif_read_data($photo->path());
            $meta = Image::make($photo->getRealPath())->exif();

            //dd($meta);

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
                'photo_date' => isset($meta['FileDateTime']) ? date('Y-m-d', $meta['FileDateTime']) :  date('Y-m-d'),
            ]);


            $photo->delete();

            $this->dispatch('appendPhoto2', $photoModel);
        }

        $this->photos = [];

        $this->reset();
    }

    public function render()
    {
        return view('livewire.file-uploads');
    }
}