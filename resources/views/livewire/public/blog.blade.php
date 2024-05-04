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
        $this->profil = User::where('blog_url', $public_url)->where('is_blog', 1)->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->posts = $this->profil
            ->posts()
            ->with('photos')
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }
};

?>

<x-container class="max-w-3xl">


    <div class="flex flex-col gap-3" x-data="lightbox(), { open: false }">


        <x-card class="max-w-3xl">
            @lang('Blog'): {{ $profil->name }}
        </x-card>



        @if ($posts->count() == 0)
            <x-card class="max-w-3xl">
                <div class="text-center text-lg text-black ">@lang('No posts')</div>
            </x-card>
        @endif



        @foreach ($posts as $post)
            <x-card class="max-w-3xl">
                <div class="mb-4">
                    <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                    <div class="text-sm">{{ $post->created_at->diffForHumans() }}</div>



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
                        <img class="lightbox cursor-pointer h-32 object-cover w-full rounded-lg shadow-lg"
                            @click="openLightbox({{ $index }})" alt=""
                            @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                            src="{{ $photo->is_video ? $photo->path : route('get.blog', ['photo' => $photo->pivot->uuid]) }}" />
                        @php $index++ @endphp
                    @endforeach
                </div>
            </x-card>
        @endforeach






        <!-- Lightbox -->
        <div x-show="isOpen" @keydown.window.escape="closeLightbox" @keydown.window.arrow-left="prevImage"
            @keydown.window.arrow-right="nextImage"
            class="flex justify-center items-center z-[999] fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 cursor-pointer ">
            <template x-if="currentPhoto.type === 'image'">
                <img :src="currentPhoto.url" style="max-width: 90%; max-height: 90%;" />
            </template>
            <template x-if="currentPhoto.type === 'youtube'">
                <iframe class="youtube-iframe" width="560" height="315" :src="currentPhoto.url" frameborder="0"
                    allowfullscreen></iframe>
            </template>
            <button @click="prevImage" style="position: absolute; top: 50%; left: 20px;">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                    width="24">
                    <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z" />
                </svg>
            </button>
            <button @click="nextImage" style="position: absolute; top: 50%; right: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" height="24" viewBox="0 -960 960 960"
                    width="24">
                    <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z" />
                </svg>
            </button>
            <button @click="closeLightbox" style="position: absolute; top: 20px; right: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path fill="#fff"
                        d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                </svg>
            </button>
        </div>



        <script>
            function lightbox() {
                const images = document.querySelectorAll('.lightbox'); // Znajdź wszystkie obrazy na stronie

                const photos = Array.from(images).map(img => {

                    if (img.src.includes('youtube.com')) {
                        return {
                            type: 'youtube',
                            url: img.dataset.src,
                        };
                    } else {
                        return {
                            type: 'image',
                            url: img.src,
                        };
                    }

                });
                return {
                    photos: photos,
                    currentIndex: 0,
                    isOpen: false,
                    openLightbox(index) {
                        this.currentIndex = index;
                        this.isOpen = true;
                    },
                    closeLightbox() {
                        this.isOpen = false;
                        const currentPhoto = this.photos[this.currentIndex];
                        if (currentPhoto.type === 'youtube') {
                            const iframe = document.querySelector('.youtube-iframe');
                            const temp = iframe.src;
                            iframe.src = '';
                            iframe.src = temp;
                        }
                    },
                    nextImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                    },
                    prevImage() {
                        this.currentIndex = (this.currentIndex + this.photos.length - 1) % this.photos.length;
                    },
                    get currentPhoto() {
                        return this.photos[this.currentIndex];
                    }
                }
            }
        </script>

    </div>
</x-container>
