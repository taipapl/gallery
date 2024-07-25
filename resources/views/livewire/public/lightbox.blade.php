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

    public $album;
    public $image;
    public $images;

    protected $listeners = [
        'showImage' => 'showImage',
    ];

    public function showImage($image, $album = null): void
    {
        $this->show = true;

        if ($album) {
            $photoTag = PhotoTag::where('uuid', $image)->firstOrFail();

            $this->image = Photo::where('id', $photoTag->id)->firstOrFail();

            $this->album = Tag::where('id', $album['id'])
                ->where('is_public', 1)
                ->firstOrFail();
        }

        $this->curentImage = $image;
    }

    public function close(): void
    {
        //$this->show = false;
    }

    public function next(): void
    {
        $this->images = $this->album->photos()->get();

        foreach ($this->images as $key => $image) {
            if ($image->pivot->uuid == $this->curentImage) {
                $nextImage = $this->images[$key + 1];
                if ($nextImage) {
                    $this->curentImage = $nextImage->pivot->uuid;
                }
            }
        }
    }

    public function back(): void
    {
        $this->images = $this->album->photos()->get();
        foreach ($this->images as $key => $image) {
            if ($image->pivot->uuid == $this->curentImage) {
                $backImage = $this->images[$key - 1];
                if ($backImage) {
                    $this->curentImage = $backImage->pivot->uuid;
                }
            }
        }
    }
};

?>
<div>
    @if ($show)
        <div class="fixed items-center left-0 top-0  bg-black/80  w-full h-full" wire:click="close">

            <div class="flex justify-center items-center ">

                @if ($album)
                    <div class=" cursor-pointer" wire:click="back()">
                        <svg xmlns="https://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#5f6368">
                            <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z" />
                        </svg>
                    </div>
                @endif


                <div class="flex flex-col justify-center items-center">

                    <img class="object-contain" src="{{ route('get.public', ['photo' => $curentImage]) }}"
                        alt="">
                </div>

                @if ($album)
                    <div class="cursor-pointer" wire:click="next()">
                        <svg xmlns="https://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#5f6368">
                            <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z" />
                        </svg>

                    </div>
                @endif

            </div>
        </div>
</div>
@endif
</div>
