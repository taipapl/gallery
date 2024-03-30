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
    public $posts;

    public $title;
    public $content;
    public $createt_at;
    public $active = true;

    public function mount()
    {
        seo()->title(__('Blog') . ' - ' . config('app.name'));
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function rendering(View $view): void
    {
        $view->posts = Auth::user()
            ->posts()
            ->with('photos')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
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

                    <x-secondary-link href="{{ route('blog.emails') }}">
                        @lang('Emails')
                    </x-secondary-link>

                    <x-secondary-link href="{{ route('blog.create') }}">
                        @lang('Create post')
                    </x-secondary-link>

                </div>
            </div>

        </x-slot>

        <div class="py-12">



            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        @if ($posts->count() == 0)
                            <div class="text-center text-lg text-black ">@lang('No posts')</div>
                        @endif

                        @foreach ($posts as $post)
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                                <div>{{ $post->created_at->format('d.m.Y') }}</div>
                                <div>
                                    @if ($post->active)
                                        <span class="text-green-500">Active</span>
                                    @else
                                        <span class="text-red-500">Not active</span>
                                    @endif
                                </div>
                                <p>{{ $post->post }}</p>

                                <x-secondary-link href="{{ route('blog.edit', ['post' => $post->id]) }}">
                                    @lang('Edit post')
                                </x-secondary-link>


                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
