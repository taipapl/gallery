<?php

namespace App\Livewire;

use App\Models\Photo;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class FileUploads extends Component
{

    use WithFileUploads;

    #[Validate(['photos.*' => 'image|max:1024'])]
    public $photos = [];

    public function save()
    {
        foreach ($this->photos as $photo) {
            $photo->store('photos', 'public');

            Photo::create([
                'label' => $photo->getClientOriginalName(),
                'path' => $photo->hashName(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.file-uploads');
    }
}
