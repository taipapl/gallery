<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $profil;
    public $albums;
    public $lastPost;

    public function mount($public_url)
    {
        $this->profil = User::where('public_url', $public_url)->where('is_public', 1)->firstOrFail();

        if ($this->profil->is_blog) {
            $this->lastPost = $this->profil->posts()->latest()->first();
        }

        $this->albums = $this->profil->tags()->where('is_public', 1)->get();
    }
};

?>

<div>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $profil->name }}</h1>

    <div class="flex gap-2 flex-wrap ">

        @if ($profil->is_blog)
            <a href="{{ route('public.blog', $profil->blog_url) }}">

                <div class="text-sm">
                    <a href="{{ route('public.blog', $profil->blog_url) }}">
                        @if (!$lastPost->tag_id)

                            @if ($lastPost->photos->first())
                                <img wire:click="clickLightbox('{{ $lastPost->photos->first()->uuid }}', 'private')"
                                    src="{{ route('get.image', ['photo' => $lastPost->photos->first()->uuid]) }}"
                                    alt="{{ $lastPost->photos->first()->name }}"
                                    class="h-40 w-40 cursor-pointer object-cover mx-auto rounded-lg shadow-lg">
                            @else
                                <div class="h-40 w-40 cursor-pointer object-cover mx-auto rounded-lg shadow-lg">
                                    <div class="text-center text-lg text-gray-500">
                                        @lang('No photos')
                                    </div>
                                </div>
                            @endif

                            <div> {{ Str::limit($lastPost->title, 18) }}</div>
                        @else
                            @if ($lastPost->gallery->cover)
                                <img src="{{ route('get.cover', ['photo' => $lastPost->gallery->cover, 'size' => '160']) }}"
                                    alt="{{ $lastPost->gallery->name }}"
                                    class="h-40 w-40 cursor-pointer object-cover mx-auto rounded-lg shadow-l">
                            @else
                                <div class="h-40 w-40 cursor-pointer object-cover mx-auto rounded-lg shadow-lg">
                                    <div class="text-center text-lg text-gray-500">
                                        @lang('Blog')
                                    </div>
                                </div>
                            @endif

                            <div> {{ Str::limit($lastPost->gallery->name, 18) }}</div>
                        @endif
                    </a>
                    <div> {{ $lastPost->created_at->diffForHumans() }}</div>

                </div>
            </a>
        @endif

        @foreach ($albums as $album)
            <a href="{{ route('public.album', $album->public_url) }}">

                <div href="{{ route('public.album', $album->public_url) }}" class=" cursor-pointer" wire:navigate>

                    @if ($album->cover)
                        <img loading="lazy" src="{{ route('get.image', ['photo' => $album->cover, 'size' => '160']) }}"
                            alt="{{ $album->name }}"
                            class="h-40 w-full md:w-40 object-cover object-top  rounded-lg shadow-lg">
                    @else
                        <div
                            class="h-40 w-40 bg-gray-200 flex items-center justify-center cursor-pointer   rounded-lg shadow-lg">
                            <div class="text-center text-lg text-gray-500">@lang('No photos')
                            </div>
                        </div>
                    @endif


                    <div class="text-sm">
                        <div> {{ Str::limit($album->name, 18) }}</div>
                        <div> {{ $album->photos->count() }} @lang('elements')</div>
                    </div>

                </div>

            </a>
        @endforeach
    </div>
</div>
