<?php

namespace App\Livewire;

use App\Models\Photo;
use Livewire\Component;

use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Intervention\Image\Facades\Image;

class FileUploads extends Component
{

    use WithFileUploads;

    #[Validate(['photos.*' => 'image|max:1024'])]
    public $photos = [];

    public function save()
    {

        foreach ($this->photos as $photo) {
            $photo->store('photos');



            Photo::create([
                'label' => $photo->getClientOriginalName(),
                'path' => $photo->hashName(),
                'user_id' => auth()->id(),
                'meta' => serialize(Image::make($photo->getRealPath())->exif())
            ]);
        }

        $this->photos = [];
    }

    public function render()
    {
        return view('livewire.file-uploads');
    }
}