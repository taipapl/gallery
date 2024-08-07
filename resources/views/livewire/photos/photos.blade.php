<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Photo;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination; // 10MB Max

    public $perPage = 50;
    public $photos;

    public $is_favorite = 0;
    public $is_archived = 0;

    public function mount()
    {
        $this->photos = collect([]);

        seo()->title(__('Photos') . ' - ' . config('app.name'));
    }

    protected $listeners = [
        'appendPhoto2' => 'appendPhoto2',
    ];

    public function favorite()
    {
        $this->is_favorite = (int) !$this->is_favorite;
        $this->resetPage();
    }

    public function archived()
    {
        $this->is_archived = (int) !$this->is_archived;
        $this->resetPage();
    }

    public function appendPhoto2($photo)
    {
        $photoModel = Photo::find($photo['id']);

        $this->photos->prepend($photoModel);
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function rendering(View $view): void
    {
        $query = auth()->user()->photos()->getQuery();

        if ($this->is_archived) {
            $query->where('is_archived', $this->is_archived);
        } else {
            $query->where('is_archived', 0);
        }

        if ($this->is_favorite) {
            $query->where('is_favorite', $this->is_favorite);
        }

        $view->photos = $query->orderBy('photo_date', 'desc')->paginate($this->perPage);
    }

    public function clickLightbox($uuid, $type)
    {
        $this->dispatch('lightbox', $uuid, $type);
    }
};

?>

<x-container>


    <x-card>

        <div class="flex gap3 flex-col md:flex-row items-left ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Photos') }}</h2>


            <div>
                <label wire:click="archived()" class="relative inline-flex items-center cursor-pointer">
                    <input wire:model="is_archived" type="checkbox" @if ($is_archived) checked @endif
                        class="sr-only peer" value="1">
                    <div
                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Archived')
                    </span>
                </label>
            </div>

            <div>
                <label wire:click="favorite()" class="relative inline-flex items-center cursor-pointer">
                    <input wire:model="is_favorite" type="checkbox" @if ($is_favorite) checked @endif
                        class="sr-only peer" value="1">
                    <div
                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Favorites')
                    </span>
                </label>
            </div>



            <livewire:file-uploads />



        </div>
    </x-card>


    <x-card>

        @if (count($photos) == 0)
            <div class="text-center text-lg text-black ">@lang('No photos')</div>
        @endif

        <div class="flex gap-2 flex-wrap">
            @php($dataLabel = null)
            @foreach ($photos ?? [] as $key => $photo)
                @if ($dataLabel != $photo->photo_date->format('F Y'))
                    @php($dataLabel = $photo->photo_date->format('F Y'))
                    <div class="w-full text-center text-lg text-black ">{{ $dataLabel }}</div>
                @endif
                <div wire:click="clickLightbox('{{ $photo->uuid }}', 'private')" class="cursor-pointer ">
                    <x-photo :photo="$photo" :loop="$loop" />
                </div>
            @endforeach

            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>

        </div>
    </x-card>




</x-container>
