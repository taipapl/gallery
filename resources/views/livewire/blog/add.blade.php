<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use function Livewire\Volt\{rules};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Post;
use Livewire\WithFileUploads;
use App\Models\Photo;
use Illuminate\Support\Str;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public $perPage = 50;
    public $photoIds = [];
    public $modelId;
    public $post;

    public $uploadPhotos = [];
    public $error = '';
    public $photos = [];

    public function mount($uuid)
    {
        $this->post = Post::where('uuid', $uuid)->firstOrFail();
        $this->photoIds = $this->post->photos()->pluck('photo_id')->toArray();
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

    public function addPhoto($id)
    {
        $photo = \App\Models\Photo::find($id);

        if (in_array($id, $this->photoIds)) {
            $this->post->photos()->detach($id);
            unset($this->photoIds[array_search($id, $this->photoIds)]);
        } else {
            $this->post->photos()->attach($photo, ['uuid' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
            $this->photoIds[] = $id;
        }
    }

    public function loadMore()
    {
        $this->perPage += 10;
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

        <div class="flex items-center">

            <div x-show="uploading">
                <x-icons.update />
            </div>

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-whit whitespace-nowrap">
                {{ __('Add Photos') }}
            </h2>

            <label
                class="cursor-pointer flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
                <span>@lang('Add photos') {{ $error }}</span>
                <input class="hidden" type="file" id="photos" wire:model="photos"
                    accept="image/png, image/gif, image/jpeg" multiple>
            </label>

            <x-sub-nav-link href="{{ route('blog.edit', $post->uuid) }}">
                @lang('Cancel')
            </x-sub-nav-link>

        </div>

    </x-panel>


    <x-panel>

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
