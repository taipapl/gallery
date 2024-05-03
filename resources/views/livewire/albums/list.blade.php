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

    public $is_archived = 0;

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function archived()
    {
        $this->is_archived = (int) !$this->is_archived;
        $this->resetPage();
    }

    public function mount(): void
    {
        seo()->title(__('Albums') . ' - ' . config('app.name'));
    }

    public function rendering(View $view): void
    {
        $query = Auth::user()->tags()->where('is_album', 1)->getQuery();

        if ($this->is_archived) {
            $query->where('is_archived', $this->is_archived);
        } else {
            $query->where('is_archived', 0);
        }

        $view->albums = $query->paginate($this->perPage);
    }
};
?>

<div class="flex w-full" x-data="{ active: false }">

    <div class="flex-none order-3  " x-show="active">
        <livewire:layout.navigation />
    </div>

    <div class="flex-none order-2" x-show="!active">

        <div
            class="fixed right-0 top-0  h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

            <div class="mt-8 space-y-4">


                <div class="px-4">
                    <label wire:click="archived()" class="relative inline-flex items-center cursor-pointer">
                        <input wire:model="is_archived" type="checkbox" @if ($is_archived) checked @endif
                            class="sr-only peer" value="1">
                        <div
                            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Archived')
                        </span>
                    </label>
                </div>



                <livewire:albums.create />


                <x-sub-nav-link x-on:click="active = ! active">
                    @lang('Main menu')
                </x-sub-nav-link>


            </div>

        </div>
    </div>



    <div class="grow order-1 ">

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
