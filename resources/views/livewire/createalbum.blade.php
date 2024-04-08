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
        $this->redirectRoute('albums.album', ['tag' => $tag->id]);
    }
}; ?>

<div>
    <x-sub-nav-link wire:click="createAlbum">
        @lang('Create Album')
    </x-sub-nav-link>
</div>
