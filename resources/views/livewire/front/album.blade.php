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
        @foreach ($photos as $photo)
            <div class="relative">
                <a href="{{ route('get.image', ['filename' => $photo->path]) }}">
                    <div class="h-60 w-full border-2  block overflow-hidden"
                        style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                    </div>
                </a>
            </div>
        @endforeach




    </div>
