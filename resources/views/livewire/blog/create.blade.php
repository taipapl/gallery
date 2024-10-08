<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use function Livewire\Volt\{rules};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Post;
use Livewire\WithFileUploads;
use App\Models\Tag;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 20;
    public $posts;

    public $title;
    public $content = '';
    public $createt_at;
    public $active = true;
    public $publicAlbums = [];

    public $pa;

    public function mount()
    {
        seo()->title(__('Create Post') . ' - ' . config('app.name'));

        $this->createt_at = now()->format('Y-m-d');

        $this->publicAlbums = Tag::where('is_public', 1)->where('user_id', auth()->id())->get();
    }

    public function addPost()
    {
        $validated = $this->validate([
            'title' => 'required|min:3',
            'content' => 'nullable|min:3',
            'createt_at' => 'required|date',
        ]);

        $post = app(Post::class);
        $post->uuid = (string) Str::uuid();
        $post->timestamps = false;
        $post->title = $this->title;
        $post->post = $this->content;
        $post->created_at = $this->createt_at . ' ' . now()->format('H:i:s');
        $post->active = $this->active;
        $post->user_id = auth()->id();
        if ($this->pa) {
            $post->tag_id = $this->pa;
        }

        $post->save();

        $this->title = '';
        $this->content = '';
        $this->createt_at = '';
        $this->active = true;

        $this->redirectRoute('blog.edit', ['uuid' => $post->uuid]);
    }

    public function clickActive()
    {
        $this->active = !$this->active;
    }
};

?>

<div class="flex w-full" x-data="{ active: true }">

    <div class="flex-none order-3 ">
        <livewire:layout.navigation />
    </div>

    <div class="grow order-2">






        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-3">



            <x-panel>

                <div class="flex gap-3 items-center ">

                    <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Blog')</h2>

                    <x-sub-nav-link href="{{ route('blog.list') }}">
                        @lang('Back')
                    </x-sub-nav-link>

                </div>

            </x-panel>




            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">





                <div class="p-6 text-gray-900">





                    <form class="mb-4" wire:submit.prevent="addPost">
                        <div class="flex gap-3 flex-col">
                            <input type="text" class="form-input" placeholder="@lang('Title')" wire:model="title">
                            <div>
                                @error('title')
                                    <div class="text-red-700 "> {{ $message }}</div>
                                @enderror
                            </div>
                            <textarea class="form-input" placeholder="@lang('Content')" wire:model="content"></textarea>
                            <div>
                                @error('content')
                                    <div class="text-red-700 ">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="date" class="form-input" placeholder="@lang('date')"
                                wire:model="createt_at">
                            <div>
                                @error('createt_at')
                                    <div class="text-red-700 ">{{ $message }}</div>
                                @enderror
                            </div>

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

                            <div>
                                <select wire:model.change="pa">
                                    <option value="">{{ __('Select public album') }}</option>
                                    @foreach ($publicAlbums as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="flex justify-between mt-3">


                                <x-primary-button type="submit" class="btn btn-primary">
                                    @lang('Add post')
                                </x-primary-button>


                                <x-primary-link href="{{ route('blog.list') }}">
                                    @lang('Cancel')
                                </x-primary-link>

                            </div>


                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
</div>
