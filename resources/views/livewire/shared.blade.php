<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Tag;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $albums;
    public $perPage = 10;

    public $photos = [];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        seo()->title(__('Shared') . ' - ' . config('app.name'));
    }

    public function rendering(View $view): void
    {
        $view->albums = Tag::with(['emails'], function ($query) {
            $query->where('email', Auth::user()->email);
        })->paginate($this->perPage);

        //   dd(Auth::user()->email, $view->albums);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between ">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shared') }}
            </h2>

            <div class="flex gap-2 justify-end">

                <x-secondary-link href="{{ route('emails') }}">
                    @lang('Emails')
                </x-secondary-link>
            </div>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (count($albums) == 0)
                        <div class="text-center text-lg text-black ">@lang('No shared albums')</div>
                    @endif

                    <div class="flex gap-2 flex-wrap">
                        @foreach ($albums ?? [] as $key => $album)
                            <div class="h-40 w-40" @if ($loop->last) id="last_record" @endif
                                style="background-image: url('{{ route('get.image', ['photo' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                            </div>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
