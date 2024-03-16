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

        $tag->photos()->detach();
        $tag->shared()->delete();

        $tag->delete();
        $this->redirectRoute('albums.list');
    }

    public function mount(Tag $tag)
    {
        if ($tag->user_id != auth()->id()) {
            abort(403);
        }

        $this->tag = $tag;
        $this->name = $tag->name;

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

<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">


            <div class="flex justify-between ">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Album') }}
                </h2>

                <div class="flex gap-2 justify-end">

                    <x-primary-button wire:confirm="{{ __('Are You sure?') }}" wire:click="archived()">
                        {{ $tag->is_archived ? __('Un Archived') : __('Archived') }}
                    </x-primary-button>

                    <x-primary-button wire:confirm="{{ __('Are You sure?') }}"
                        wire:click="deleteAlbum('{{ $tag->id }}')">
                        @lang('Delete Album')
                    </x-primary-button>

                    <x-primary-button
                        onclick="Livewire.dispatch('openModal', { component: 'shared-tag' , arguments: {tagId: '{{ $tag->id }}' } })">
                        @lang('Sheard Album')
                    </x-primary-button>

                    <x-primary-button
                        onclick="Livewire.dispatch('openModal', { component: 'add-photos' , arguments: {modelId: '{{ $tag->id }}' } })">
                        @lang('Add Photo')
                    </x-primary-button>



                </div>
            </div>

        </div>
    </header>

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
                            <a href="{{ route('show', $photo->id) }}" class="h-40 w-40"
                                @if ($loop->last) id="last_record" @endif
                                @if ($photo->is_video) style="background-image: url('{{ $photo->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                @else
                style="background-image: url('{{ route('get.image', ['photo' => $photo->id]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;"> @endif
                                </a>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>




                </div>
            </div>
        </div>
    </div>


</div>
