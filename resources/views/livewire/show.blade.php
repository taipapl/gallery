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

        session()->flash('toast', __('Photo was deleted'));

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

    public function favorite()
    {
        $this->photo->update([
            'is_favorite' => !$this->photo->is_favorite,
        ]);

        if ($this->photo->is_favorite) {
            $this->dispatch('showToast', __('Image was add to favorite'), 'info', 3);
        } else {
            $this->dispatch('showToast', __('Image was remove from favorite'), 'info', 3);
        }
    }
};

?>

<x-container x-data="{ active: true, rotation: 0 }">

    <x-panel>

        <div class="flex gap3 flex-col md:flex-row items-left ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Photos') }}</h2>

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

            <x-sub-nav-link wire:click="favorite">
                {{ $photo->is_favorite ? __('Un Favorite') : __('Favorite') }}
            </x-sub-nav-link>

        </div>

    </x-panel>


    <x-panel>


        <form wire:submit class="mb-5">
            <input type="text" name="label" id="label" wire:model.live.debounce.800ms="label"
                class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Label')" />
        </form>

        @if ($photo->is_video)
            <iframe class="w-full md:h-[650px]" src="{{ $photo->video_path }}" title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        @else
            <div class=" overflow-hidden">
                <img :style="{ transform: 'rotate(' + rotation + 'deg)' }" class="m-auto rounded-lg shadow-lg"
                    src="{{ route('get.image', ['photo' => $photo->uuid]) }}" alt="">
            </div>
        @endif


        <div class="flex justify-between">


            <div>
                <div class="text-sm px-5">@lang('Belong to Albums')</div>

                <div class="px-5">
                    @foreach ($tags ?? [] as $tag)
                        <a href="{{ route('albums.show', $tag->uuid) }}"
                            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                            @if ($tag->name == '')
                                {{ __('No name') }}
                            @else
                                {{ Str::limit($tag->name, 10) }}
                            @endif

                        </a>
                    @endforeach
                </div>

            </div>

            <div class="break-all">
                @if (is_array($photo->meta))
                    {{-- @foreach ($photo->meta as $key => $meta)
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
                    @endforeach --}}
                @endif
            </div>

        </div>





    </x-panel>


</x-container>
