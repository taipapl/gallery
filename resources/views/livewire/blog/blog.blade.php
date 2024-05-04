<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use function Livewire\Volt\{rules};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Post;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 20;
    public $posts;

    public $title;
    public $content;
    public $createt_at;
    public $active = true;

    public function mount()
    {
        seo()->title(__('Blog') . ' - ' . config('app.name'));
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function rendering(View $view): void
    {
        $view->posts = Auth::user()
            ->posts()
            ->with('photos')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }
};
?>
<x-container class="max-w-3xl">

    <x-card>

        <div class="flex gap-3 items-center ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Blog')</h2>

            <x-sub-nav-link href="{{ route('blog.create') }}">
                @lang('Create post')
            </x-sub-nav-link>

            <x-sub-nav-link href="{{ route('blog.emails') }}">
                @lang('Emails')
            </x-sub-nav-link>

        </div>

    </x-card>

    @if ($posts->count() == 0)
        <x-card>
            @lang('No posts')
        </x-card>
    @endif

    @foreach ($posts as $post)
        <x-card>

            <div class="flex flex-col gap-3">

                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>

                <div class="text-sm">
                    {{ $post->created_at->diffForHumans() }}

                    @if ($post->active)
                        <span class="text-green-500">@lang('Active')</span>
                    @else
                        <span class="text-red-500">@lang('No Active')</span>
                    @endif

                </div>

                @if ($post->photos->first())
                    <img src="{{ route('get.image', ['photo' => $post->photos->first()->uuid]) }}"
                        alt="{{ $post->photos->first()->name }}"
                        class="object-cover mx-auto w-full rounded-lg shadow-lg">
                @endif


                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($post->photos ?? [] as $key => $photo)
                        @if ($post->photos[$key] != $post->photos->first())
                            <a href="{{ route('photos.show', $photo->uuid) }}">
                                <img class="h-40 w-40 object-cover rounded-lg shadow-lg"
                                    @if ($photo->is_video) src="{{ $photo->path }}"

                            @else

                            src="{{ route('get.image', ['photo' => $photo->uuid]) }}" @endif
                                    alt="{{ $photo->name }}" />
                            </a>
                        @endif
                    @endforeach
                </div>

                <div>{{ $post->post }}</div>

                <div>

                    <x-secondary-link href="{{ route('blog.edit', $post->uuid) }}">
                        @lang('Edit post')
                    </x-secondary-link>

                </div>

            </div>

        </x-card>
    @endforeach

</x-container>
