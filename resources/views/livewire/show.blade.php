<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Photo;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public Photo $photo;

    public function mount(Photo $photo)
    {
        $this->photo = $photo;

        seo()->title(__('Show') . ' - ' . $this->photo->name . ' - ' . config('app.name'));
    }

    public function download()
    {
        return response()->download(storage_path('app/photos/' . $this->photo->path));
    }

    public function delete()
    {
        $this->photo->delete();
        return redirect()->route('photos');
    }

    public function rotate()
    {
        $img = Image::make(storage_path('app/photos/' . $this->photo->path));

        $img->rotate(-90);
        $img->save();
    }
};

?>
<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">


            <div class="flex justify-between ">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Show') }}
                </h2>

                <div class="flex gap-2 justify-end">

                </div>
            </div>

        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center" x-data="{ rotation: 0 }">

                    @if ($photo->is_video)
                        <iframe class="w-full" height="615" src="{{ $photo->video_path }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    @else
                        <img :style="{ transform: 'rotate(' + rotation + 'deg)' }" class="m-auto"
                            src="{{ route('get.image', ['filename' => $photo->path]) }}" alt="">
                    @endif


                    @if (!$photo->is_video)
                        <x-secondary-button class="mt-4" wire:click="download">
                            {{ __('Download') }}
                        </x-secondary-button>

                        <x-secondary-button @click="rotation += 90" class="mt-4" wire:click="rotate">
                            {{ __('Rotate') }}
                        </x-secondary-button>
                    @endif

                    <x-secondary-button class="mt-4" wire:confirm="{{ __('Are you sure? Delete photo go to Trash') }}"
                        wire:click="delete">
                        {{ __('Delete') }}
                    </x-secondary-button>




                    @if (is_array($photo->meta))


                        @foreach ($photo->meta as $key => $meta)
                            <div class="text-left">
                                <span class="font-bold">{{ $key }}:</span>

                                @if (is_array($meta))
                                    @foreach ($meta as $key => $value)
                                        <div>{{ $key }}: {{ $value }}</div>
                                    @endforeach
                                @else
                                    <span>{{ $meta }}</span>
                                @endif

                            </div>
                        @endforeach

                    @endif


                </div>
            </div>
        </div>
    </div>


</div>
