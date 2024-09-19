<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Tag;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $album;
    public $photos = [];

    public $perPage = 50;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(string $uuid)
    {
        $this->album = Tag::where('uuid', $uuid)->firstOrFail();

        if ($this->album) {
            $count = $this->album->count;
            $this->album->count = $count + 1;
            $this->album->save();
        }
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->album->photos()->paginate($this->perPage);
    }

    public function clickLightbox($uuid, $type, $tag)
    {
        $this->dispatch('lightbox', $uuid, $type, $tag);
    }
};
?>

<x-container>

    <x-panel>
        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Shared album')</h2>
    </x-panel>


    <x-panel>


        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $album->name }}</h1>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($photos as $key => $photo)
                <img class=" cursor-pointer h-40 w-full md:w-40 object-cover object-top  rounded-lg shadow-lg"
                    wire:click="clickLightbox('{{ $photo->pivot->uuid }}', 'public', {{ $this->album }})" alt=""
                    @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                    src="{{ $photo->is_video ? $photo->path : route('get.image', ['photo' => $photo->uuid, 'size' => '160']) }}" />
            @endforeach
        </div>


    </x-panel>

</x-container>
