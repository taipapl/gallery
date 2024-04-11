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

        <div
            class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r sm:w-40 w-60 dark:bg-gray-900 dark:border-gray-700">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

            <div class="mt-8 space-y-4">

                <x-sub-nav-link href="{{ route('blog.create') }}">
                    @lang('Create post')
                </x-sub-nav-link>

                <x-sub-nav-link href="{{ route('blog.emails') }}">
                    @lang('Emails')
                </x-sub-nav-link>

            </div>
        </div>

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
                                <div class="text-sm">{{ $post->created_at->format('d.m.Y') }}</div>


                                @if ($post->photos->first())
                                    <img src="{{ route('get.image', ['photo' => $post->photos->first()->uuid]) }}"
                                        alt="{{ $post->photos->first()->name }}" class=" h-[550px] object-cover">
                                @endif


                                <div>
                                    @if ($post->active)
                                        <span class="text-green-500">@lang('Active')</span>
                                    @else
                                        <span class="text-red-500">@lang('No Active')</span>
                                    @endif
                                </div>

                                <p>{{ $post->post }}</p>

                                <x-secondary-link href="{{ route('blog.edit', $post->uuid) }}">
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
