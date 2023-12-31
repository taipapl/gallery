<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Photo;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 10;
    public $photos;

    public $activePhoto = 'sadasd';

    public function openPhoto($path)
    {
        $this->activePhoto = $path;
        // dd($path);
    }

    protected $listeners = [
        'appendPhoto2' => 'appendPhoto2',
    ];

    public function appendPhoto2($photo)
    {
        //  dd($photo);
        $photoModel = Photo::find($photo['id']);

        $this->photos[] = $photoModel;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function rendering(View $view): void
    {
        $view->photos = auth()
            ->user()
            ->photos()
            ->paginate($this->perPage);
    }
};

?>
<div>
    <div x-data="{ open: false }">




        <x-slot name="header">

            <div class="flex justify-between ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Photos') }}
                </h2>

                <div class="flex gap-2 justify-end">

                    <x-primary-button onclick="Livewire.dispatch('openModal', { component: 'file-uploads' })">
                        @lang('Files Uploads')
                    </x-primary-button>

                </div>
            </div>
        </x-slot>

        <div class="py-12">



            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        @if (count($photos) == 0)
                            <div class="text-center text-lg text-black ">@lang('No photos')</div>
                        @endif

                        <div class="flex gap-2 flex-wrap">

                            @foreach ($photos ?? [] as $key => $photo)
                                <a href="{{ route('show', $photo->id) }}" class=" cursor-pointer h-40 w-40"
                                    @if ($loop->last) id="last_record" @endif
                                    style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                                </a>
                            @endforeach

                            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        @teleport('body')
            <div x-show="open" class="flex absolute justify-center items-center w-full h-full  bg-black/70 left-0 top-0">



                <div>
                    <img src="{{ route('get.image', ['filename' => $activePhoto]) }}" alt="">
                    <div>
                        <button @click="open = ! open"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            @lang('Close')
                        </button>
                    </div>
                </div>

            </div>
        @endteleport
    </div>
</div>
