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
};
?>

<div>


    <div
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

        <div class="mt-8 space-y-4">

            <x-sub-nav-link href="{{ route('video.add') }}">
                @lang('Add Video')
            </x-sub-nav-link>

        </div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    @if (count($videos) == 0)
                        <div class="text-center text-lg text-black ">@lang('No videos')</div>
                    @endif

                    <div class="flex gap-2 flex-wrap ">
                        @foreach ($videos ?? [] as $key => $video)
                            <a href="{{ route('photos.show', $video->uuid) }}" class="h-40 w-40"
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
