<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;

class Albums extends Component
{
    public function render()
    {
        $albums = Tag::where('is_album', 1)->where('user_id', auth()->id())->get();



        return view('livewire.albums', compact('albums'));
    }
}
