<?php

namespace App\Livewire;

use App\Models\Photo;
use LivewireUI\Modal\ModalComponent;

class AddVideo extends ModalComponent
{

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

        $photoModel =  Photo::create([
            'path' => $this->videoUrl,
            'is_video' => true,
            'user_id' => auth()->id(),
            'meta' => serialize(['video_id' => $this->videoId, 'video_image' => $this->videoImage]),
        ]);

        return redirect()->to('/video');
    }


    public function youTubeValid()
    {
        $pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';
        return preg_match($pattern, $this->video);
    }

    public function render()
    {
        return view('livewire.add-video');
    }
}
