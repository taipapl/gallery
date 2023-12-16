<?php

use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use App\Models\Tag;

new class extends Component {
    public function createAlbum(): void
    {
        $tag = Tag::create([
            'user_id' => Auth()->user()->id,
            'is_album' => true,
        ]);

        $this->redirectRoute('album', ['tag' => $tag->id]);
    }
}; ?>

<div>
    <a wire:click="createAlbum" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Create Album
    </a>
</div>
