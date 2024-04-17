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
    public $perPage = 10;
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
        $this->perPage += 10;
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

<div x-data="{ active: true }">


    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

        <div class="mt-8 space-y-4">

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
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <form wire:submit>
                        <input type="text" name="name" id="name" wire:model.live.debounce.800ms="name"
                            class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Album name')" />
                    </form>

                    @if (count($photos) == 0)
                        <div class="text-center text-lg text-black ">@lang('No photos in album')</div>
                    @endif


                    <div class="flex gap-2 flex-wrap mt-5">
                        @foreach ($photos ?? [] as $key => $photo)
                            <a href="{{ route('photos.show', ['uuid' => $photo->uuid]) }}" class="h-40 w-40"
                                @if ($loop->last) id="last_record" @endif
                                @if ($photo->is_video) style="background-image: url('{{ $photo->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                @else
                style="background-image: url('{{ route('get.image', ['photo' => $photo->uuid]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;"> @endif
                                </a>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>




                </div>
            </div>
        </div>
    </div>


</div>
