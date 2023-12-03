<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Photos extends Component
{

    use WithPagination;

    public $perPage = 10;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function render()
    {
        return view(
            'livewire.photos',
            [
                'photos' => auth()->user()->photos()->paginate($this->perPage)
            ]
        );
    }
}