<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class AddVideo extends ModalComponent
{

    public $video;

    public function render()
    {
        return view('livewire.add-video');
    }
}
