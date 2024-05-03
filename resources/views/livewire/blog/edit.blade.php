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
use App\Models\pivot\PostPhoto;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 20;
    public $post;

    public $title;
    public $content;
    public $created_at;
    public $active = true;

    public $photos = [];

    protected $listeners = [
        'appendPhoto' => 'appendPhoto',
    ];

    public function appendPhoto($photo)
    {
        $photoModel = Photo::find($photo['id']);

        $this->photos[] = $photoModel;
    }

    public function mount(string $uuid)
    {
        $this->post = Post::where('uuid', $uuid)->firstOrFail();
        $this->title = $this->post->title;
        $this->content = $this->post->post;
        $this->created_at = $this->post->created_at->format('Y-m-d');

        $this->active = $this->post->active;
        seo()->title(__('Create Post') . ' - ' . config('app.name'));
    }

    public function addPost()
    {
        $validated = $this->validate([
            'title' => 'required|min:3',
            'content' => 'nullable|min:3',
            'created_at' => 'required|date',
        ]);

        $this->post->title = $this->title;
        $this->post->post = $this->content;
        $this->post->created_at = $this->created_at;
        $this->post->active = $this->active;
        $this->post->user_id = auth()->id();
        $this->post->save();
    }

    public function clickActive()
    {
        $this->active = !$this->active;
    }

    public function rendering(View $view): void
    {
        $view->photos = $this->post->photos()->get();
    }

    public function changePhoto($uuid)
    {
        PostPhoto::where('post_id', $this->post->id)->update(['first' => 0]);

        PostPhoto::where('uuid', $uuid)->update(['first' => 1]);
    }
};

?>

<div class="flex w-full" x-data="{ active: false }">

    <div class="flex-none order-3" x-show="active">
        <livewire:layout.navigation />
    </div>


    <div class="flex-none order-2" x-show="!active">
        <div
            class=" right-0 top-0 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

            <div class="mt-8 space-y-4">

                <x-sub-nav-link href="{{ route('blog.list') }}">
                    @lang('Cancel')
                </x-sub-nav-link>

            </div>
        </div>
    </div>

    <div class="grow order-1 ">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($post->photos->first())
                        <img src="{{ route('get.image', ['photo' => $post->photos->first()->uuid]) }}"
                            alt="{{ $post->photos->first()->name }}" class=" object-cover">
                    @endif

                    <form class="mb-4" wire:submit.prevent="addPost">
                        <div class="flex gap-3 flex-col">
                            <input type="text" class="form-input" placeholder="@lang('title')" wire:model="title">
                            <div>
                                @error('title')
                                    <div class="text-red-700 "> {{ $message }}</div>
                                @enderror
                            </div>
                            <textarea class="form-input" placeholder="@lang('content')" wire:model="content"></textarea>
                            <div>
                                @error('content')
                                    <div class="text-red-700 ">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="date" class="form-input" placeholder="@lang('date')"
                                wire:model="created_at">
                            <div>
                                @error('created_at')
                                    <div class="text-red-700 ">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="flex justify-between">

                                <div>
                                    <label wire:click="clickActive()"
                                        class="relative inline-flex items-center cursor-pointer">
                                        <input wire:model="active" type="checkbox" checked class="sr-only peer"
                                            value="1">
                                        <div
                                            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                        <span
                                            class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Public')</span>
                                    </label>
                                </div>


                            </div>

                            <div class="flex flex-col  md:flex-row gap-2">

                                <x-primary-link href="{{ route('blog.add', ['uuid' => $post->uuid]) }}">

                                    @lang('Add Photo')
                                </x-primary-link>


                                <x-primary-button type="submit" class="btn btn-primary">
                                    @lang('Save post')
                                </x-primary-button>
                            </div>


                        </div>
                    </form>

                    <div class="flex gap-2 flex-wrap mt-5">
                        @foreach ($photos ?? [] as $key => $photo)
                            @if ($post->photos[$key] != $post->photos->first())
                                <img wire:click="changePhoto('{{ $photo->pivot->uuid }}')"
                                    class="h-40 w-40 object-cover cursor-pointer " loading="lazy"
                                    @if ($photo->is_video) src="{{ $photo->path }}"
                            @else
                                src="{{ route('get.image', ['photo' => $photo->uuid]) }}" @endif />
                            @endif
                        @endforeach

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
