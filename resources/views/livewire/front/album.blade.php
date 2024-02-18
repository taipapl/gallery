<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tag;
use Illuminate\View\View;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $album;
    public $photos = [];

    public $perPage = 10;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount($public_url)
    {
        $this->album = Tag::where('public_url', $public_url)->where('is_public', 1)->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->album->photos()->paginate($this->perPage);
    }
};

?>

<div>

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $album->name }}</h1>





    <div x-data="lightbox()">
        <!-- Miniatury zdjęć -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($photos as $key => $photo)
                <img class="lightbox cursor-pointer" @click="openLightbox({{ $key }})" alt=""
                    src="{{ route('get.image', ['photo' => $photo->id]) }}" />
            @endforeach
        </div>

        <!-- Lightbox -->
        <div x-show="isOpen" @keydown.window.escape="closeLightbox" @keydown.window.arrow-left="prevImage"
            @keydown.window.arrow-right="nextImage"
            class="flex justify-center items-center z-[999] fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 cursor-pointer ">
            <img @click="closeLightbox" :src="currentPhoto"
                style="object-fit: contain; width: 90%; height: 90%;cursor-pointer" />
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

                console.log(images);

                const photos = Array.from(images).map(img => ({
                    url: img.src,
                })); // Stwórz tablicę obiektów zdjęć
                console.log(photos);
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
                    },
                    nextImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                    },
                    prevImage() {
                        this.currentIndex = (this.currentIndex + this.photos.length - 1) % this.photos.length;
                    },
                    get currentPhoto() {
                        console.log(this.photos[this.currentIndex]);
                        return this.photos[this.currentIndex].url;
                    }
                }
            }
        </script>


    </div>
</div>
