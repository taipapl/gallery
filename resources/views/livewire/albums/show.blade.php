<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use App\Models\Tag;
use App\Models\Photo;
use Livewire\WithPagination;
use Illuminate\View\View;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public Tag $tag;
    public $name = '';
    public $perPage = 46;
    public $photos;

    protected $listeners = [
        'appendPhoto' => 'appendPhoto',
    ];

    public function appendPhoto($photo)
    {
        $photoModel = Photo::find($photo['photo_id']);
        $this->photos[] = $photoModel;
    }

    public function loadMore()
    {
        $this->perPage += 14;
    }

    public function updated($name, $value)
    {
        $this->tag->update([
            $name => $value,
        ]);
    }

    public function deleteAlbum($id)
    {
        $tag = Tag::find($id);

        $tag->delete();
        $this->redirectRoute('albums.list');
    }

    public function mount($uuid)
    {
        $this->tag = Tag::where('uuid', $uuid)->firstOrFail();

        if ($this->tag->user_id != auth()->id()) {
            abort(403);
        }
        $this->name = $this->tag->name;

        seo()->title(__('Album') . ' - ' . $this->tag->name . ' - ' . config('app.name'));
    }

    public function archived()
    {
        $this->tag->update([
            'is_archived' => !$this->tag->is_archived,
        ]);
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->tag->photos()->paginate($this->perPage);
    }
};

?>

<x-container>

    <x-card>

        <div class="flex justify-center ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

            <x-sub-nav-link href="{{ route('albums.add', $tag->uuid) }}">
                @lang('Add Photo')
            </x-sub-nav-link>

            <x-sub-nav-link href="{{ route('albums.share', $tag->uuid) }}">
                @lang('Share Album')
            </x-sub-nav-link>

            <x-sub-nav-link wire:key="archive" wire:confirm="{{ __('Are You sure?') }}" wire:click="archived()">
                {{ $tag->is_archived ? __('Un Archived') : __('Archived') }}
            </x-sub-nav-link>

            <x-sub-nav-link wire:confirm="{{ __('Are You sure?') }}" wire:click="deleteAlbum('{{ $tag->id }}')">
                @lang('Delete Album')
            </x-sub-nav-link>

        </div>

    </x-card>

    <x-card>


        <form wire:submit>
            <input type="text" name="name" id="name" wire:model.live.debounce.800ms="name"
                class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Album name')" />
        </form>

        @if (count($photos) == 0)
            <div class="text-center text-lg text-black ">@lang('No photos in album')</div>
        @endif


        <div class="flex gap-2 flex-wrap mt-5">
            @foreach ($photos ?? [] as $key => $photo)
                <a class="w-full md:w-auto cursor-pointer" href="{{ route('photos.show', ['uuid' => $photo->uuid]) }}"
                    @if ($loop->last) id="last_record" @endif>
                    @if ($photo->is_video)
                        <img class="w-full md:h-44 md:w-44 object-cover object-top rounded-lg shadow-lg"
                            src="{{ $photo->path }}" alt="{{ $photo->name }}" />
                    @else
                        <img class=" w-full md:h-44 md:w-44 object-cover object-top  rounded-lg shadow-lg"
                            src="{{ route('get.image', ['photo' => $photo->uuid]) }}" alt="{{ $photo->name }}" />
                    @endif
                </a>
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>

    </x-card>

</x-container>
