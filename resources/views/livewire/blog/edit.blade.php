<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use function Livewire\Volt\{rules};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Post;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 20;
    public $post;

    public $title;
    public $content;
    public $created_at;
    public $active = true;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->post;
        $this->created_at = $post->created_at->format('Y-m-d');

        $this->active = $post->active;
        seo()->title(__('Create Post') . ' - ' . config('app.name'));
    }

    public function addPost()
    {
        $validated = $this->validate([
            'title' => 'required|min:3',
            'content' => 'required|min:3',
            'created_at' => 'required|date',
        ]);

        $post = app(Post::class);
        $post->timestamps = false;
        $post->title = $this->title;
        $post->post = $this->content;
        $post->created_at = $this->created_at;
        $post->active = $this->active;
        $post->user_id = auth()->id();
        $post->save();

        $this->title = '';
        $this->content = '';
        $this->created_at = '';
        $this->active = true;
    }

    public function clickActive()
    {
        $this->active = !$this->active;
    }
};

?>

<div>

    <div x-data="{ open: false }">

        <x-slot name="header">

            <div class="flex justify-between ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Create post') }}
                </h2>
                <div class="flex gap-3 justify-end">



                </div>
            </div>

        </x-slot>

        <div class="py-12">



            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <form class="mb-4" wire:submit.prevent="addPost">
                            <div class="flex gap-3 flex-col">
                                <input type="text" class="form-input" placeholder="@lang('title')"
                                    wire:model="title">
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



                                <x-primary-button type="submit"
                                    class="btn btn-primary">@lang('Add post')</x-primary-button>
                            </div>
                        </form>


                        <x-primary-button
                            onclick="Livewire.dispatch('openModal', { component: 'add-photos' , arguments: { tagId: '{{ $post->id }}' } })">
                            @lang('Add Photo')
                        </x-primary-button>



                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
