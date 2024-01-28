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

    public function mount($public_url)
    {
        $profil = User::where('public_url', $public_url)
            ->where('is_public', 1)
            ->firstOrFail();

        $this->profil = $profil;

        $this->albums = $profil
            ->tags()
            ->where('is_public', 1)
            ->get();
    }
};

?>

<div>

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $profil->name }}</h1>

    @foreach ($albums as $album)
        <a href="{{ route('public_album', $album->public_url) }}">
            {{ Str::limit($album->name, 10) }}
        </a>
    @endforeach
</div>
