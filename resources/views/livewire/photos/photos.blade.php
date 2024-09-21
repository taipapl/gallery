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


    <x-panel>

        <div class="flex gap3 flex-col md:flex-row items-left ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Photos') }}</h2>

            <x-check-box :model="'is_archived'" :checked="$is_archived" :action="'archived()'">
                @lang('Archived')
            </x-check-box>

            <x-check-box :model="'is_favorite'" :checked="$is_favorite" :action="'favorite()'">
                @lang('Favorite')
            </x-check-box>

            <livewire:uploads />

        </div>

    </x-panel>


    <x-panel>

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
    </x-panel>




</x-container>
