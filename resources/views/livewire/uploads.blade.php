<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Tag;
use App\Models\pivot\UsersTags;
use App\Models\Photo;
use Illuminate\Support\Str;
use App\Models\pivot\PhotoTag;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public $photos = [];

    public $dates = [];

    public $photoIds = [];

    public $uploadPhotos = [];

    public $error = '';

    public function updatedPhotos()
    {
        $this->limit = auth()->user()->photo_limit;
        $this->count = auth()->user()->photos()->count();

        if ($this->count >= $this->limit) {
            $this->error = __('You have reached your photo limit');
            return;
        }

        foreach ($this->photos as $key => $photo) {
            $image = Image::make($photo->path())->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $meta = Image::make($photo->getRealPath())->exif();

            //check is the directory exists
            if (!file_exists(storage_path('app/photos/' . auth()->id()))) {
                mkdir(storage_path('app/photos/' . auth()->id()), 0777, true);
            }

            $storagePath = storage_path('app/photos/' . auth()->id() . '/' . $image->basename);

            $image->save($storagePath);

            $date = date('Y-m-d');
            if (isset($meta['DateTimeOriginal']) && $meta['DateTimeOriginal'] != '0000:00:00 00:00:00') {
                $date = date('Y-m-d', strtotime($meta['DateTimeOriginal']));
            } elseif (isset($meta['DateTimeDigitized']) && $meta['DateTimeDigitized'] != '0000:00:00 00:00:00') {
                $date = date('Y-m-d', strtotime($meta['DateTimeDigitized']));
            } elseif (isset($this->dates[$key])) {
                $date = $this->dates[$key];
            }

            $photoModel = Photo::create([
                'uuid' => (string) Str::uuid(),
                'path' => $image->basename,
                'user_id' => auth()->id(),
                'meta' => $meta,
                'photo_date' => $date,
            ]);

            $photo->delete();

            $this->photos = [];

            $this->dispatch('appendPhoto2', $photoModel);
        }

        $this->dispatch('showToast', __('Images was added'), 'info', 3);
    }

    public function validDates($dates)
    {
        try {
            Carbon::parse($date);
        } catch (\Exception $e) {
            return false;
        } finally {
            return true;
        }
    }

    public function addDates($dates)
    {
        $this->dates = $dates;
    }
};
?>
<div x-data="{ active: true, uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">




    <div x-show="uploading">
        <x-icons.update />
    </div>

    <label
        class="cursor-pointer flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
        <span>@lang('Add photos') {{ $error }}</span>
        <input class="hidden" type="file" id="photos" wire:model="photos" accept="image/png, image/gif, image/jpeg"
            multiple>
    </label>


    @script
        <script>
            document.addEventListener('livewire:init', () => {
                document.getElementById('uploadPhotos').addEventListener('change', function(event) {

                    let files = event.target.files;

                    let dates = [];

                    for (let i = 0; i < files.length; i++) {
                        dates.push(files[i].lastModifiedDate);
                    }

                    $wire.addDates(dates);
                });

            });
        </script>
    @endscript


</div>
