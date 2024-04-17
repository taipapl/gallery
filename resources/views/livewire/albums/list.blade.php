<?php
use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $albums = [];
    public $perPage = 10;

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        $this->albums = Auth::user()->tags()->where('is_album', 1)->where('is_archived', 0)->get();

        seo()->title(__('Albums') . ' - ' . config('app.name'));
    }
};
?>
<div x-data="{ active: true }">

    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

        <div class="mt-8 space-y-4">

            <livewire:albums.create />

            <x-sub-nav-link href="{{ route('albums.archived') }}">
                @lang('Archived Albums')
            </x-sub-nav-link>


            {{-- <button
                class="flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">


                <div class="text-left rtl:text-right">
                    <h1 class="text-sm font-medium text-gray-700 capitalize dark:text-white">Mia John</h1>


                </div>
            </button>

            <button
                class="flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
                <img class="object-cover w-8 h-8 rounded-full"
                    src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=faceare&facepad=3&w=688&h=688&q=100"
                    alt="">

                <div class="text-left rtl:text-right">
                    <h1 class="text-sm font-medium text-gray-700 capitalize dark:text-white">Mia John</h1>

                    <p class="text-xs text-gray-500 dark:text-gray-400">11.2 Followers</p>
                </div>
            </button> --}}

        </div>

    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 ">

                    <div class="flex gap-2 flex-wrap ">
                        @foreach ($albums as $album)
                            <a href="{{ route('albums.show', ['uuid' => $album->uuid]) }}" class=" cursor-pointer"
                                wire:navigate>

                                @if ($album->cover)
                                    <div class="h-40 w-40 border-2  block overflow-hidden "
                                        @if ($loop->last) id="last_record" @endif
                                        style="background-image: url('{{ route('get.image', ['photo' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

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

                            </a>
                        @endforeach
                    </div>

                    @if (count($albums) == 0)
                        <div class="text-center text-lg text-black ">No Albums</div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
