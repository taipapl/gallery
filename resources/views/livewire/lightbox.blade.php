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
use App\Models\pivot\PostPhoto;
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
                    $this->image = Photo::where('id', $photoTag->photo_id)->firstOrFail();

                    $this->label = $this->image->label;
                    break;

                case 'blog':
                    $postPhoto = PostPhoto::where('uuid', $image)->firstOrFail();
                    $this->image = Photo::where('id', $postPhoto->photo_id)->firstOrFail();
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
        <div class="fixed  left-0 top-0 bg-black w-full h-screen z-50 animate-fadein  p-2">
            <div class="flex items-center justify-center h-full w-full ">

                <div class="flex flex-col h-[90%]">
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
                    <div class="flex gap-2">
                        <div>
                            @if ($image->is_video)
                                <iframe class="md:min-w-[800px] md:min-h-[400px] rounded-md"
                                    src="{{ $image->video_path }}" title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            @else
                                <div class=" overflow-hidden">

                                    @if ($this->type == 'private')
                                        <img :style="{ transform: 'rotate(' + rotation + 'deg)' }"
                                            class=" object-cover rounded-md"
                                            src="{{ route('get.image', ['photo' => $curentImage]) }}" alt="">
                                    @elseif ($this->type == 'blog')
                                        <img class=" object-cover rounded-md"
                                            src="{{ route('get.blog', ['photo' => $curentImage]) }}" alt="">
                                    @else
                                        <img class=" object-cover rounded-md"
                                            src="{{ route('get.public', ['photo' => $curentImage]) }}" alt="">
                                    @endif

                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 ">

                            <x-primary-button wire:click="close" title="@lang('Close')">
                                <x-icons.close />
                                <span class="hidden md:block"> @lang('Close')</span>
                            </x-primary-button>

                            @if ($this->type == 'private')

                                <x-primary-button wire:confirm="{{ __('Are you sure?') }}"
                                    title="  {{ $image->is_archived ? __('Un Archived') : __('Archived') }}"
                                    wire:click="archived">
                                    @if ($image->is_archived)
                                        <x-icons.unarchive class="fill-white " />
                                    @else
                                        <x-icons.archive class="fill-white " />
                                    @endif
                                    <span class="hidden md:block">
                                        {{ $image->is_archived ? __('Un Archived') : __('Archived') }}</span>
                                </x-primary-button>

                                <x-primary-button wire:click="favorite">
                                    <x-icons.favorite class="fill-white " />
                                    <span class="hidden md:block">
                                        {{ $image->is_favorite ? __('Un Favorite') : __('Favorite') }}</span>

                                </x-primary-button>

                                @if (!$image->is_video)
                                    <x-primary-button @click="rotation += 90" wire:click="rotate">

                                        <x-icons.rotate-right class="fill-white " />

                                        <span class="hidden md:block"> {{ __('Rotate') }}</span>
                                    </x-primary-button>

                                    <x-primary-button wire:click="download">
                                        <x-icons.download class="fill-white " />
                                        <span class="hidden md:block"> {{ __('Download') }}</span>
                                    </x-primary-button>
                                @endif

                                @if ($this->tag)
                                    <x-primary-button wire:click="clickSetAsCover('{{ $image->uuid }}')">

                                        <span class="hidden md:block"> {{ __('Set as cover') }}</span>
                                    </x-primary-button>
                                @endif



                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
