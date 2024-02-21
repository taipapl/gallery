<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Post;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 20;
    public $posts;

    public $title;
    public $content;

    public function mount()
    {
        seo()->title(__('Blog') . ' - ' . config('app.name'));
    }

    public function addPost()
    {
        Post::create([
            'title' => $this->title,
            'post' => $this->content,
        ]);

        $this->title = '';
        $this->content = '';
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function rendering(View $view): void
    {
        $view->posts = Post::latest()->paginate($this->perPage);
    }
};

?>

<div>

    <div x-data="{ open: false }">




        <x-slot name="header">

            <div class="flex justify-between ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Blog') }}
                </h2>
                <div class="flex gap-3 justify-end">



                </div>
            </div>
        </x-slot>

        <div class="py-12">



            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <form class="mb-4" wire:submit.prevent="addPost">
                            <div class="flex gap-3">
                                <input type="text" class="form-input" placeholder="@lang('title')"
                                    wire:model="title">
                                <textarea class="form-input" placeholder="@lang('content')" wire:model="content"></textarea>

                                <button type="submit" class="btn btn-primary">@lang('Add post')</button>
                            </div>
                        </form>


                        @foreach ($posts as $post)
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                                <p>{{ $post->post }}</p>
                            </div>
                        @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
