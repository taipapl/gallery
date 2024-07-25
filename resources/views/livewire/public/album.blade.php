<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tag;
use Illuminate\View\View;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $album;
    public $photos = [];

    public $perPage = 50;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount($public_url)
    {
        $this->album = Tag::where('public_url', $public_url)->where('is_public', 1)->firstOrFail();

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

    public function showImges($image): void
    {
        $this->dispatch('showImage', image: $image);
    }
};

?>

<div>

    <h1 class="text-2xl mb-3 font-semibold text-gray-900 dark:text-white">{{ $album->name }}</h1>

    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($photos as $key => $photo)
                <img @if ($loop->last) id="last_record" @endif
                    class=" cursor-pointer object-cover shadow-md rounded-md" alt=""
                    wire:click="showImges('{{ $photo->pivot->uuid }}')"
                    @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                    src="{{ $photo->is_video ? $photo->path : route('get.public', ['photo' => $photo->pivot->uuid, 'size' => '600']) }}" />
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>

    </div>
    <livewire:public.lightbox />
</div>
