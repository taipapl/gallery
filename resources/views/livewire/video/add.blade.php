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

<div x-data="{ active: true }">




    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

        <div class="mt-8 space-y-4">



            <x-sub-nav-link href="{{ route('video.list') }}">
                @lang('Cancel')
            </x-sub-nav-link>

        </div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="p-10">


                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                            {{ __('Add Video') }}
                        </h2>

                        @if ($videoUrl)
                            <div class="mt-4">
                                <iframe class="w-full" height="315" src="{{ $videoUrl }}"
                                    title="YouTube video player" frameborder="0"
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

                    </div>


                </div>

            </div>
        </div>

    </div>
