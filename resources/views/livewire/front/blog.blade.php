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

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount($public_url)
    {
        $this->profil = User::where('public_url', $public_url)->where('is_blog', 1)->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->posts = $this->profil
            ->posts()
            ->with('photos')
            ->where('active', 1)
            ->paginate($this->perPage);
    }
};

?>


<div>

    <div x-data="{ open: false }">

        <x-slot name="header">

            <div class="flex justify-between ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Blog') }}
                </h2>
                <div class="flex gap-3 justify-end">



                </div>
            </div>

        </x-slot>

        <div class="py-12">



            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">



                        @foreach ($posts as $post)
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                                <div>{{ $post->created_at->format('d.m.Y') }}</div>

                                <p>{{ $post->post }}</p>

                            </div>
                        @endforeach

                        <div x-data="lightbox()">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($post->photos as $key => $photo)
                                    <img class="lightbox cursor-pointer" @click="openLightbox({{ $key }})"
                                        alt=""
                                        @if ($photo->is_video) data-src="{{ $photo->video_path }}" @endif
                                        src="{{ $photo->is_video ? $photo->path : route('get.image', ['photo' => $photo->id]) }}" />
                                @endforeach
                            </div>



                            <!-- Lightbox -->
                            <div x-show="isOpen" @keydown.window.escape="closeLightbox"
                                @keydown.window.arrow-left="prevImage" @keydown.window.arrow-right="nextImage"
                                class="flex justify-center items-center z-[999] fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 cursor-pointer ">
                                <template x-if="currentPhoto.type === 'image'">
                                    <img :src="currentPhoto.url" style="max-width: 90%; max-height: 90%;" />
                                </template>
                                <template x-if="currentPhoto.type === 'youtube'">
                                    <iframe class="youtube-iframe" width="560" height="315" :src="currentPhoto.url"
                                        frameborder="0" allowfullscreen></iframe>
                                </template>
                                <button @click="prevImage" style="position: absolute; top: 50%; left: 20px;">
                                    <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24"
                                        viewBox="0 -960 960 960" width="24">
                                        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z" />
                                    </svg>
                                </button>
                                <button @click="nextImage" style="position: absolute; top: 50%; right: 20px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" height="24"
                                        viewBox="0 -960 960 960" width="24">
                                        <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z" />
                                    </svg>
                                </button>
                                <button @click="closeLightbox" style="position: absolute; top: 20px; right: 20px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                                        width="24">
                                        <path fill="#fff"
                                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                                    </svg>
                                </button>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
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
