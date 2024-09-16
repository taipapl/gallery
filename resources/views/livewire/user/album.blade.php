<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tag;
use Illuminate\View\View;
use App\Models\pivot\UsersTags;
use App\Models\Photo;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $album;

    public $userTag;

    public Photo $photos;

    public $perPage = 50;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(string $user_url)
    {
        $this->userTag = UsersTags::where('uuid', $user_url)->firstOrFail();
        $this->album = Tag::find($this->userTag->tag_id);

        if ($this->userTag) {
            $count = $this->userTag->count;
            $this->userTag->count = $count + 1;
            $this->userTag->save();
        }
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->album->photos()->paginate($this->perPage);
    }

    public function clickLightbox($image, $type): void
    {
        $this->dispatch('lightbox', $image, $type);
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
                    wire:click="clickLightbox('{{ $photo->pivot->uuid }}', 'public')"
                    @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                    src="{{ $photo->is_video ? $photo->path : route('get.public', ['photo' => $photo->pivot->uuid, 'size' => '600']) }}" />
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>

    </div>
</div>
