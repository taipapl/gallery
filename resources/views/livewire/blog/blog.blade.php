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

    public function clickLightbox($image, $type): void
    {
        $this->dispatch('lightbox', $image, $type);
    }
};
?>
<x-container class="max-w-3xl">

    <x-panel>

        <div class="flex gap-3 items-center ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Blog')</h2>

            <x-sub-nav-link href="{{ route('blog.create') }}">
                @lang('Create post')
            </x-sub-nav-link>

        </div>

    </x-panel>

    @if ($posts->count() == 0)
        <x-panel>
            @lang('No posts')
        </x-panel>
    @endif

    @foreach ($posts as $post)
        <x-panel>

            <div class="flex flex-col gap-3">

                @if ($post->active)
                    <span class="text-green-500">@lang('Active')</span>
                @else
                    <span class="text-red-500">@lang('No Active')</span>
                @endif

                @if (!$post->tag_id)
                    <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                    <div class="text-sm">{{ $post->created_at->diffForHumans() }}</div>
                @else
                    <h2 class="text-xl font-semibold">{{ $post->gallery->name }}</h2>
                    <span class="text-sm">{{ $post->gallery->created_at->diffForHumans() }}</span>

                    @if ($post->gallery->cover)
                        <a href="{{ route('public.album', $post->gallery->public_url) }}">
                            <img src="{{ route('get.cover', ['photo' => $post->gallery->cover]) }}"
                                alt="{{ $post->gallery->name }}"
                                class=" object-cover mx-auto w-full rounded-lg shadow-lg">
                        </a>
                    @endif
                @endif



                @if ($post->photos->first())
                    <img wire:click="clickLightbox('{{ $post->photos->first()->uuid }}', 'private')"
                        src="{{ route('get.image', ['photo' => $post->photos->first()->uuid]) }}"
                        alt="{{ $post->photos->first()->name }}"
                        class="cursor-pointer object-cover mx-auto w-full rounded-lg shadow-lg">
                @endif


                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($post->photos ?? [] as $key => $photo)
                        @if ($post->photos[$key] != $post->photos->first())
                            <img wire:click="clickLightbox('{{ $photo->uuid }}', 'private')"
                                class="h-40
                                w-40 object-cover rounded-lg shadow-lg cursor-pointer"
                                @if ($photo->is_video) src="{{ $photo->path }}"

                                @else

                            src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '160']) }}" @endif
                                alt="{{ $photo->name }}" />
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

        </x-panel>
    @endforeach

</x-container>
