<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $videos;
    public $perPage = 10;

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        seo()->title(__('Video') . ' - ' . config('app.name'));
    }

    public function rendering(View $view): void
    {
        $view->videos = auth()
            ->user()
            ->videos()
            ->paginate($this->perPage);
    }

    public function clickLightbox($uuid, $type)
    {
        $this->dispatch('lightbox', $uuid, $type);
    }
};
?>

<x-container>

    <x-panel>

        <div class="flex gap-3 items-center">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

            <x-sub-nav-link href="{{ route('video.add') }}">
                @lang('Add Video')
            </x-sub-nav-link>

        </div>

    </x-panel>

    <x-panel>


        @if (count($videos) == 0)
            <div class="text-center text-lg text-black ">@lang('No videos')</div>
        @endif

        <div class="flex gap-2 flex-wrap ">
            @foreach ($videos ?? [] as $key => $video)
                <div class="w-full relative block md:w-auto" @if ($loop->last) id="last_record" @endif>
                    <div wire:click="clickLightbox('{{ $video->uuid }}', 'private')" class="h-40 w-40">
                        <img class="w-full md:h-44 md:w-44 object-cover object-top rounded-lg shadow-lg"
                            src="{{ $video->path }}" alt="{{ $video->name }}" />
                    </div>
                </div>
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>
    </x-panel>
</x-container>
