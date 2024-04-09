<?php
use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use App\Models\Tag;

new class extends Component {
    public function createAlbum(): void
    {
        $tag = Tag::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => Auth()->user()->id,
            'is_album' => true,
        ]);
        $this->redirectRoute('albums.album', ['uuid' => $tag->uuid]);
    }
}; ?>

<div>
    <x-sub-nav-link wire:click="createAlbum">
        @lang('Create Album')
    </x-sub-nav-link>
</div>
