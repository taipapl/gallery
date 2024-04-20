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

    public $perPage = 10;
    public $photoIds = [];
    public $modelId;
    public $post;

    public function mount($uuid)
    {
        $this->post = Post::where('uuid', $uuid)->firstOrFail();
        $this->photoIds = $this->post->photos()->pluck('photo_id')->toArray();
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

<div x-data="{ active: true }">

    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

        <div class="mt-8 space-y-4">

            <x-sub-nav-link href="{{ route('blog.edit', $post->uuid) }}">
                @lang('Cancel')
            </x-sub-nav-link>

        </div>
    </div>



    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">




                <div class="flex justify-between ">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Add Photos') }}
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
</div>
