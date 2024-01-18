<?php

use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public $albums;

    public function mount(): void
    {
        $this->albums = User::where('is_public', 1)->get();
    }
}; ?>

<div>


    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach ($albums as $album)
            <div class="relative">
                <div class="relative">
                    <img class="w-full h-full object-cover" src="{{ $album->profile_photo_url }}" alt="">
                </div>
                <div class="absolute bottom-0 w-full h-full bg-gradient-to-t from-gray-900 to-transparent"></div>
                <div class="absolute bottom-0 w-full h-full flex justify-center items-center">
                    <a href="{{ route('albums.album', ['tag' => $album->public_url]) }}"
                        class="px-4 py-2 bg-white text-gray-900 rounded hover:bg-gray-200 hover:text-gray-900 transition duration-300 ease-in-out">
                        {{ $album->name }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>


</div>
