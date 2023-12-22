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

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
        $this->name = $tag->name;
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->tag->photos()->paginate($this->perPage);
    }
};

?>

<div>
    <x-slot name="header">
        <div class="flex justify-between ">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Album') }}
            </h2>

            <div class="flex gap-2 justify-end">

                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onclick="Livewire.dispatch('openModal', { component: 'shared-tag' , arguments: { tag_id: '{{ $tag->id }}' } })">
                    @lang('Sheard Album')
                </a>

                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onclick="Livewire.dispatch('openModal', { component: 'add-photos' , arguments: { tag_id: '{{ $tag->id }}' } })">
                    @lang('Add Photo')
                </a>

            </div>
        </div>


    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit>
                        <input type="text" name="name" id="name" wire:model.live.debounce.800ms="name"
                            class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Album name')" />
                    </form>

                    @if ($photos->count() == 0)
                        <div class="text-center mt-5">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                onclick="Livewire.dispatch('openModal', { component: 'add-photos', arguments: { tag_id: '{{ $tag->id }}' } })">
                                @lang('Add Photo')
                            </button>
                        </div>
                    @endif


                    <div class="flex gap-2 flex-wrap mt-5">
                        @foreach ($photos ?? [] as $key => $photo)
                            <div class="h-40 w-40" @if ($loop->last) id="last_record" @endif
                                style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                            </div>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
