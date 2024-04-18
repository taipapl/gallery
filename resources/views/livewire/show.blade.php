<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Photo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

new #[Layout('layouts.app')] class extends Component {
    public Photo $photo;

    public $label;

    public $tags;

    public function mount(string $uuid)
    {
        $this->photo = Photo::where('uuid', $uuid)->firstOrFail();

        if ($this->photo->user_id != auth()->id()) {
            abort(403);
        }

        $this->label = $this->photo->label;

        $this->tags = $this->photo->tags;

        seo()->title(__('Show') . ' - ' . $this->photo->label . ' - ' . config('app.name'));
    }

    public function download(): BinaryFileResponse
    {
        return response()->download(storage_path('app/photos/' . $this->photo->user_id . '/' . $this->photo->path));
    }

    public function delete()
    {
        $this->photo->delete();
        return redirect()->route('photos.list');
    }

    public function updated($name, $value)
    {
        $this->photo->update([
            $name => $value,
        ]);
    }

    public function archived()
    {
        $this->photo->update([
            'is_archived' => !$this->photo->is_archived,
        ]);
    }

    public function rotate()
    {
        $img = Image::make(storage_path('app/photos/' . $this->photo->user_id . '/' . $this->photo->path));

        $img->rotate(-90);
        $img->save();
    }
};

?>
<div x-data="{ rotation: 0, active: true }">


    <div x-show="active" @click.away="active = false"
        class="fixed z-50 right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">



        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Photos') }}</h2>

        <div class="mt-8 space-y-4">

            @if (!$photo->is_video)
                <x-sub-nav-link wire:click="download">
                    {{ __('Download') }}
                </x-sub-nav-link>

                <x-sub-nav-link @click="rotation += 90" wire:click="rotate">
                    {{ __('Rotate') }}
                </x-sub-nav-link>
            @endif

            <x-sub-nav-link wire:confirm="{{ __('Are you sure?') }}" wire:click="archived">
                {{ $photo->is_archived ? __('Un Archived') : __('Archived') }}
            </x-sub-nav-link>

            <x-sub-nav-link wire:confirm="{{ __('Are you sure? Delete photo go to Trash') }}" wire:click="delete">
                {{ __('Delete') }}
            </x-sub-nav-link>


            <div class="text-sm px-5">@lang('Belong to Albums')</div>

            <div class="px-5">
                @foreach ($tags ?? [] as $tag)
                    <a href="{{ route('albums.show', $tag->uuid) }}"
                        class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                        {{ Str::limit($tag->name, 10) }}
                    </a>
                @endforeach
            </div>


        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">


                    <form wire:submit class="mb-5">
                        <input type="text" name="label" id="label" wire:model.live.debounce.800ms="label"
                            class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Label')" />
                    </form>

                    @if ($photo->is_video)
                        <iframe class="w-full" height="615" src="{{ $photo->video_path }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    @else
                        <img :style="{ transform: 'rotate(' + rotation + 'deg)' }" class="m-auto"
                            src="{{ route('get.image', ['photo' => $photo->uuid]) }}" alt="">
                    @endif

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
