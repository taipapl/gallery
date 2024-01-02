<?php

namespace App\Livewire;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Albums extends Component
{
    public function render()
    {
        $albums = Auth::user()->tags()->where('is_album', 1)->get();

        return view('livewire.albums', compact('albums'));
    }
}
