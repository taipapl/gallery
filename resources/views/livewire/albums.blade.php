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
        $this->albums = Auth::user()
            ->tags()
            ->where('is_album', 1)
            ->get();

        seo()->title(__('Albums') . ' - ' . config('app.name'));
    }
};
?>

<div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Albums') }}
            </h2>

            <div class="flex justify-end">
                <livewire:createalbum />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 ">

                    <div class="flex gap-2 flex-wrap ">
                        @foreach ($albums as $album)
                            <div href="{{ route('albums.album', $album->id) }}" class=" cursor-pointer" wire:navigate>

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
