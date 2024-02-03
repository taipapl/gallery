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

    <div class="flex gap-2 flex-wrap ">
        @foreach ($albums as $album)
            <a href="{{ route('public_album', $album->public_url) }}">

                <div href="{{ route('public_album', $album->public_url) }}" class=" cursor-pointer" wire:navigate>

                    @if ($album->cover)
                        <div class="h-40 w-40 border-2  block overflow-hidden "
                            @if ($loop->last) id="last_record" @endif
                            style="background-image: url('{{ route('get.image', ['filename' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                        </div>
                    @else
                        <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
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
