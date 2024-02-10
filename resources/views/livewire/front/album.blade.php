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
        $this->album = Tag::where('public_url', $public_url)
            ->where('is_public', 1)
            ->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->album->photos()->paginate($this->perPage);
    }
};

?>

<div>

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $album->name }}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">



        <div x-data="lightbox()">
            <!-- Miniatury zdjęć -->
            <div>
                @foreach ($photos as $key => $photo)
                    <img class="lightbox" @click="openLightbox({{ $key }})" alt=""
                        src="{{ route('get.image', ['filename' => $photo->path]) }}" />
                @endforeach
            </div>

            <!-- Lightbox -->
            <div x-show="isOpen" @keydown.window.escape="closeLightbox" @keydown.window.arrow-left="prevImage"
                @keydown.window.arrow-right="nextImage"
                style="background-color: rgba(0, 0, 0, 0.8); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
                <img :src="currentPhoto" style="max-width: 90%; max-height: 90%;" />
                <button @click="prevImage" style="position: absolute; top: 50%; left: 20px;">Poprzednie</button>
                <button @click="nextImage" style="position: absolute; top: 50%; right: 20px;">Następne</button>
                <button @click="closeLightbox" style="position: absolute; top: 20px; right: 20px;">Zamknij</button>
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
</div>
