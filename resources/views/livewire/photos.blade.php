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

        seo()->title(__('Photos') . ' - ' . config('app.name'));
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
            ->where('is_archived', 0)
            ->orderBy('photo_date', 'desc')
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

                <div class="flex gap-3 justify-end">

                    <x-secondary-link href="{{ route('photos.archived') }}">
                        @lang('Archived Photos')
                    </x-secondary-link>

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
                                <x-photo :photo="$photo" :loop="$loop" />
                            @endforeach

                            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>

                        </div>

                    </div>
                </div>
            </div>


        </div>

    </div>


</div>
