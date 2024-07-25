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

    public $albums;
    public $perPage = 10;

    public $photos = [];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        seo()->title(__('Shared') . ' - ' . config('app.name'));
    }

    public function rendering(View $view): void
    {
        $view->albums = Tag::whereHas('emails', function ($query) {
            $query->where('email', Auth::user()->email);
        })->paginate($this->perPage);
    }
};
?>

<x-container>

    <x-card>
        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Shared')</h2>
    </x-card>

    <x-card>

        @if (count($albums) == 0)
            <div class="text-center text-lg text-black ">@lang('No shared albums')</div>
        @endif

        <div class="flex gap-2 flex-wrap">
            @foreach ($albums ?? [] as $key => $album)
                <a @if ($loop->last) id="last_record" @endif
                    href="{{ route('shared.show', ['uuid' => $album->uuid]) }}" class="w-full md:w-auto cursor-pointer"
                    wire:navigate>

                    @if ($album->cover)
                        <img loading="lazy" src="{{ route('get.image', ['photo' => $album->cover, 'size' => '160']) }}"
                            alt="{{ $album->name }}"
                            class="h-40 w-full md:w-40 object-cover object-top  rounded-lg shadow-lg">
                    @else
                        <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
                            <div class="text-center text-lg text-gray-500">@lang('No photos')
                            </div>
                        </div>
                    @endif

                    <div class="text-sm">
                        <div> {{ Str::limit($album->name, 18) }}</div>
                        <div> {{ $album->photos->count() }} @lang('elements')</div>
                    </div>

                </a>
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>


    </x-card>

</x-container>
