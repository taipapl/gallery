<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\pivot\PhotoTag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

new class extends Component {
    public $show = false;

    public $curentImage;
    public $image;
    public $type;
    public $tag;
    public $label;

    protected $listeners = [
        'lightbox' => 'lightbox',
    ];

    public function clickSetAsCover($image)
    {
        $this->dispatch('setAsCover', $image);
    }

    public function updated($name, $value)
    {
        $this->image->update([
            $name => $value,
        ]);
    }

    public function download(): BinaryFileResponse
    {
        return response()->download(storage_path('app/photos/' . $this->image->user_id . '/' . $this->image->path));
    }

    public function lightbox($image, $type, $tag = null): void
    {
        $this->show = true;
        $this->type = $type;
        $this->tag = $tag;

        if ($type) {
            switch ($type) {
                case 'private':
                    $this->image = Photo::where('uuid', $image)->firstOrFail();
                    $this->label = $this->image->label;
                    break;

                case 'public':
                    $photoTag = PhotoTag::where('uuid', $image)->firstOrFail();
                    $this->image = Photo::where('id', $photoTag->id)->firstOrFail();
                    $this->label = $this->image->label;
                    break;
            }
        }

        $this->curentImage = $image;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function archived(): void
    {
        $this->image->archive();
    }

    public function favorite(): void
    {
        $this->image->favorite();
    }

    public function rotate(): void
    {
        $this->image->rotateLeft();
    }
};

?>
<div x-data="{ rotation: 0 }">
    @if ($show)
        <div class="fixed  left-0 top-0 bg-black/80 w-full h-screen z-50 ">
            <div class="flex items-center justify-center h-full w-full ">

                <div class="flex flex-col">
                    <div>
                        @if ($this->type == 'private')
                            <form wire:submit class="mb-5">
                                <input type="text" name="label" id="label" wire:model.live.debounce.800ms="label"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    placeholder="@lang('Label')" />
                            </form>
                        @else
                            <span class="text-white">{{ $label }}</span>
                        @endif


                    </div>
                    <div>
                        @if ($image->is_video)
                            <iframe class="md:min-w-[800px] md:min-h-[400px]" src="{{ $image->video_path }}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        @else
                            <div class=" overflow-hidden">

                                @if ($this->type == 'private')
                                    <img :style="{ transform: 'rotate(' + rotation + 'deg)' }"
                                        class="h-[90svh] object-cover "
                                        src="{{ route('get.image', ['photo' => $curentImage]) }}" alt="">
                                @else
                                    <img class="h-[90svh] object-cover "
                                        src="{{ route('get.public', ['photo' => $curentImage]) }}" alt="">
                                @endif

                            </div>
                        @endif
                    </div>


                    <div>

                        <button wire:click="close"
                            class="bg-white text-black px-3 py-1 rounded-md">@lang('Close')</button>

                        @if ($this->type == 'private')

                            <x-primary-button wire:confirm="{{ __('Are you sure?') }}" wire:click="archived">
                                {{ $image->is_archived ? __('Un Archived') : __('Archived') }}
                            </x-primary-button>

                            <x-primary-button wire:click="favorite">
                                {{ $image->is_favorite ? __('Un Favorite') : __('Favorite') }}
                            </x-primary-button>

                            @if (!$image->is_video)
                                <x-primary-button @click="rotation += 90" wire:click="rotate">
                                    {{ __('Rotate') }}
                                </x-primary-button>

                                <x-primary-button wire:click="download">
                                    {{ __('Download') }}
                                </x-primary-button>
                            @endif

                            @if ($this->tag)
                                <x-primary-button wire:click="clickSetAsCover('{{ $image->uuid }}')">
                                    {{ __('Set as cover') }}
                                </x-primary-button>
                            @endif



                        @endif

                    </div>


                </div>
            </div>
        </div>
    @endif

</div>
