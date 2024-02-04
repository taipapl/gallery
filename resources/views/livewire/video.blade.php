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

        // dd($view->albums);
    }
};
?>

<div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Video') }}
            </h2>

            <div class="flex justify-end">
                <x-primary-button onclick="Livewire.dispatch('openModal', { component: 'addVideo' })">
                    @lang('Add Video')
                </x-primary-button>
            </div>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    @if (count($videos) == 0)
                        <div class="text-center text-lg text-black ">@lang('No videos')</div>
                    @endif

                    <div class="flex gap-2 flex-wrap ">
                        @foreach ($videos ?? [] as $key => $video)
                            <a href="{{ route('show', $video->id) }}" class="h-40 w-40"
                                @if ($loop->last) id="last_record" @endif
                                style="background-image: url('{{ $video->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                            </a>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
