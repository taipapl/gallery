<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Photo;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination; // 10MB Max

    public $perPage = 20;
    public $photos;

    public function mount()
    {
        $this->photos = collect([]);
    }

    protected $listeners = [
        'appendPhoto2' => 'appendPhoto2',
    ];

    public function appendPhoto2($photo)
    {
        $photoModel = Photo::find($photo['id']);

        $this->photos->prepend($photoModel);
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
            ->orderBy('created_at', 'desc')
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

                    {{-- <x-primary-button onclick="Livewire.dispatch('openModal', { component: 'file-uploads' })">
                        @lang('Files Uploads')
                    </x-primary-button> --}}
                    {{-- <form wire:submit="save">
                        <label x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                            <span>@lang('Add photos')</span>
                            <input type="file" class="hidden" wire:model="uploads" multiple>
                        </label>
                    </form> --}}

                    <livewire:file-uploads />

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
                            @php($dataLabel = null)
                            @foreach ($photos ?? [] as $key => $photo)
                                @if ($dataLabel != $photo->photo_date->format('F Y'))
                                    @php($dataLabel = $photo->photo_date->format('F Y'))
                                    <div class="w-full text-center text-lg text-black ">{{ $dataLabel }}</div>
                                @endif
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

    </div>


</div>
