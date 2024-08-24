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

    public $video;

    public $videoUrl;

    public $addError;

    public $videoId;

    public $videoImage;

    protected $rules = [
        'video' => 'required|url',
    ];

    public function changeURL()
    {
        if ($this->youTubeValid()) {
            $this->videoUrl = $this->convertToEmbedUrl($this->video);
        }
    }

    public function convertToEmbedUrl($url)
    {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['v'])) {
                $this->videoId = $queryParams['v'];
                $this->videoImage = "https://img.youtube.com/vi/{$queryParams['v']}/hqdefault.jpg";
                $embedUrl = "https://www.youtube.com/embed/{$queryParams['v']}";

                return $embedUrl;
            }
        }

        return null;
    }

    public function save()
    {
        if (!$this->youTubeValid()) {
            $this->addError('video', 'The URL must be a valid YouTube URL.');

            return;
        }

        $photoModel = Photo::create([
            'uuid' => (string) Str::uuid(),
            'path' => $this->videoImage,
            'video_path' => $this->videoUrl,
            'is_video' => true,
            'user_id' => auth()->id(),
            'meta' => [['video_id' => $this->videoId, 'video_image' => $this->videoImage]],
            'photo_date' => date('Y-m-d'),
        ]);

        return redirect()->to('/video');
    }

    public function youTubeValid()
    {
        $pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';

        return preg_match($pattern, $this->video);
    }
};
?>
<x-container>

    <x-panel>

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

    </x-panel>

    <x-panel>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
            {{ __('Add Video') }}
        </h2>

        @if ($videoUrl)
            <div class="mt-4">
                <iframe class="w-full" height="315" src="{{ $videoUrl }}" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            </div>
        @endif

        <form wire:submit="save">
            <input class="w-full" wire:model="video" wire:past="changeURL()" wire:change="changeURL()"
                wire:paste="changeURL()" />

            @if ($addError)
                <div class="text-red-500 mt-2 text-sm">
                    {{ $addError }}
                </div>
            @endif

            <div class="flex justify-between mt-3">
                <x-primary-button type="submit">
                    @lang('Add')
                </x-primary-button>


            </div>

        </form>

    </x-panel>

</x-container>
