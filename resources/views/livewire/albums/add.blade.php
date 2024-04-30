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
            if (isset($meta['DateTimeOriginal'])) {
                $date = date('Y-m-d', strtotime($meta['DateTimeOriginal']));
            } elseif (isset($meta['DateTimeDigitized'])) {
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
    }

    public function loadMore()
    {
        $this->perPage += 10;
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
<div x-data="{ active: true, uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div x-show="uploading">
        <progress class="bg-white w-full" max="100" x-bind:value="progress"></progress>
    </div>

    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Album')</h2>

        <div class="mt-8 space-y-4">

            <label
                class="cursor-pointer flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
                <span>@lang('Add photos') {{ $error }}</span>
                <input class="hidden" type="file" id="photos" wire:model="photos"
                    accept="image/png, image/gif, image/jpeg" multiple>
            </label>

            <x-sub-nav-link href="{{ route('albums.show', $this->tag->uuid) }}">
                @lang('Cancel')
            </x-sub-nav-link>

        </div>

    </div>




    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex justify-between ">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Add Photos to :Name album', ['name' => $tag->name]) }}
                    </h2>

                </div>


                <div class="flex gap-2 flex-wrap mt-3">
                    @foreach ($photos ?? [] as $key => $photo)
                        <div wire:click="addPhoto('{{ $photo->id }}')" class="h-40 w-40"
                            @if ($loop->last) id="last_record" @endif
                            @if ($photo->is_video) style="background-image: url('{{ $photo->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                @else
                style="background-image: url('{{ route('get.image', ['photo' => $photo->uuid]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;"> @endif
                            @if (in_array($photo->id, $photoIds)) <x-icon-do-not-disturb-on class=" text-green-600 w-6 h-6 fill-green-600 relative top-0 right-0" /> @endif
                            </div>
                    @endforeach
                    <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                </div>

            </div>

        </div>
    </div>

    @script
        <script>
            document.getElementById('uploadPhotos').addEventListener('change', function(event) {

                let files = event.target.files;

                let dates = [];

                for (let i = 0; i < files.length; i++) {
                    dates.push(files[i].lastModifiedDate);
                }

                $wire.addDates(dates);
            });
        </script>
    @endscript


</div>
