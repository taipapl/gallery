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

    public $tag;

    public $perPage = 50;

    public $model = '';

    public $modelId;

    public $photoIds = [];

    public $uploadPhotos = [];

    public $error = '';

    public function mount($uuid)
    {
        $this->tag = Tag::with('photos')->where('uuid', $uuid)->firstOrFail();
        $this->photoIds = $this->tag->photos()->pluck('photo_id')->toArray();
    }

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
        }

        $this->dispatch('showToast', __('Images was added'), 'info', 3);
    }

    public function loadMore()
    {
        $this->perPage += 10;
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

    public function addPhoto($id)
    {
        if (in_array($id, $this->photoIds)) {
            $photo = \App\Models\pivot\PhotoTag::where('photo_id', $id)
                ->where('tag_id', $this->tag->id)
                ->first();
            $photo->delete();
            unset($this->photoIds[array_search($id, $this->photoIds)]);
        } else {
            $photo = new PhotoTag();
            $photo->photo_id = $id;
            $photo->uuid = (string) Str::uuid();
            $photo->tag_id = $this->tag->id;
            $photo->user_id = auth()->id();
            $photo->save();

            $this->photoIds[] = $id;

            if ($this->tag->cover) {
                $cover = Photo::where('uuid', $this->tag->cover)->first();

                if (!$cover) {
                    $tag = Tag::find($this->tag->id);
                    $tag->cover = null;
                    $tag->save();
                }

                if (!Storage::disk('local')->exists('photos/' . auth()->id() . '/' . $cover->path)) {
                    $photo2 = Photo::find($id);
                    $this->tag->cover = $photo2->uuid;
                    $this->tag->save();
                }
            } else {
                $photo2 = Photo::find($id);
                $this->tag->cover = $photo2->uuid;
                $this->tag->save();
            }
        }
    }

    public function addDates($dates)
    {
        $this->dates = $dates;
    }

    public function rendering(View $view): void
    {
        $view->photos = auth()
            ->user()
            ->photos()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }
};
?>
<x-container x-data="{ active: true, uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
    x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">


    <x-panel>

        <div class="flex gap3 flex-col md:flex-row items-left">

            <div x-show="uploading">
                <x-icons.update />
            </div>

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Album')</h2>

            <label
                class="cursor-pointer flex items-center px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
                <span>@lang('Add photos') {{ $error }}</span>
                <input class="hidden" type="file" id="photos" wire:model="photos"
                    accept="image/png, image/gif, image/jpeg" multiple>
            </label>

            <x-sub-nav-link href="{{ route('albums.show', $this->tag->uuid) }}">
                @lang('Done')
            </x-sub-nav-link>

        </div>

    </x-panel>

    <x-panel>



        <div class="flex justify-between ">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Photos to :Name album', ['name' => $tag->name]) }}
            </h2>
        </div>


        <div class="flex gap-2 flex-wrap mt-3">
            @foreach ($photos ?? [] as $key => $photo)
                <div wire:click="addPhoto('{{ $photo->id }}')" class="relative h-40 w-40"
                    @if ($loop->last) id="last_record" @endif>

                    <img loading="lazy"
                        @if ($photo->is_video) src="{{ $photo->path }}"
                        @else
                            src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '160']) }}" @endif
                        class="cursor-pointer object-cover shadow-md rounded-md h-40 w-40 ">


                    @if (in_array($photo->id, $photoIds))
                        <x-icon-do-not-disturb-on
                            class=" text-green-600 w-6 h-6 fill-green-600 absolute top-0 right-0" />
                    @endif
                </div>
            @endforeach
            <div x-intersect.full="$wire.loadMore()" class="p-4">
                <div wire:loading wire:target="loadMore" class="loading-indicator">
                    @lang('Loading more photos')
                </div>
            </div>
        </div>

    </x-panel>

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


</x-container>
