<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\View\View;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $profil;

    public $posts;

    public $perPage = 50;

    public $index = 0;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount($public_url)
    {
        $firstPost = User::where('blog_url', $public_url)->where('is_blog', 1)->first();

        $this->profil = User::where('blog_url', $public_url)->where('is_blog', 1)->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->posts = $this->profil
            ->posts()
            ->with('photos', 'gallery.photos')
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function clickLightbox($uuid, $type)
    {
        $this->dispatch('lightbox', $uuid, $type);
    }
};

?>

<x-container class="max-w-3xl">


    <div class="flex flex-col gap-3" x-data="lightbox(), { open: false }">


        <x-panel>

            <div class="flex gap-3">

                @auth
                    <a href="{{ route('home') }}"><x-icons.back /></a>
                @endauth



                @lang('Blog'): {{ $profil->name }}
            </div>

        </x-panel>



        @if ($posts->count() == 0)
            <x-panel>
                <div class="text-center text-lg text-black ">@lang('No posts')</div>
            </x-panel>
        @endif



        @foreach ($posts as $post)
            <x-panel>
                <div class="mb-4">

                    @if (!$post->tag_id)
                        <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                        <div class="text-sm">{{ $post->created_at->diffForHumans() }}</div>
                    @else
                        <h2 class="text-xl font-semibold">{{ $post->gallery->name }}</h2>
                        <div class="text-sm">{{ $post->gallery->created_at->diffForHumans() }}</div>

                        @if ($post->gallery->cover)
                            <a href="{{ route('public.album', $post->gallery->public_url) }}">
                                <img src="{{ route('get.cover', ['photo' => $post->gallery->cover]) }}"
                                    alt="{{ $post->gallery->name }}"
                                    class=" object-cover mx-auto w-full rounded-lg shadow-lg">
                            </a>
                        @endif
                    @endif


                    @if ($post->photos->first())
                        <div>
                            <img src="{{ route('get.blog', ['photo' => $post->photos->first()->pivot->uuid]) }}"
                                alt="{{ $post->photos->first()->name }}"
                                class=" object-cover mx-auto w-full rounded-lg shadow-lg">
                        </div>
                    @endif



                    <p>{{ $post->post }}</p>

                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($post->photos as $photo)
                        @if ($loop->first)
                            @continue
                        @endif
                        <img class=" cursor-pointer h-32 object-cover w-full rounded-lg shadow-lg"
                            wire:click="clickLightbox('{{ $photo->pivot->uuid }}', 'blog')" alt=""
                            @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                            src="{{ $photo->is_video ? $photo->path : route('get.blog', ['photo' => $photo->pivot->uuid]) }}" />
                        @php $index++ @endphp
                    @endforeach
                </div>
            </x-panel>
        @endforeach


    </div>
</x-container>
