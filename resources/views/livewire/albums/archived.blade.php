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
        $this->albums = Auth::user()->tags()->where('is_album', 1)->where('is_archived', 1)->get();

        seo()->title(__('Albums') . ' - ' . config('app.name'));
    }
};
?>

<div>

    <div
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r sm:w-40 w-60 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Archived Albums') }}</h2>

        <div class="mt-8 space-y-4">

            <x-sub-nav-link href="{{ route('albums.list') }}">
                @lang('Albums')
            </x-sub-nav-link>

        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 ">

                    <div class="flex gap-2 flex-wrap ">
                        @foreach ($albums as $album)
                            <div href="{{ route('album.show', $album->id) }}" class=" cursor-pointer" wire:navigate>

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

                            </div>
                        @endforeach
                    </div>

                    @if (count($albums) == 0)
                        <div class="text-center text-lg text-black ">@lang('No archived albums')</div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>