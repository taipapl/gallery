<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\View\View;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $profil;

    public $posts;

    public $perPage = 50;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount($public_url)
    {
        $this->profil = User::where('public_url', $public_url)->where('is_blog', 1)->firstOrFail();
    }

    public function rendering(View $view): void
    {
        $view->posts = $this->profil
            ->posts()
            ->where('active', 1)
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



                </div>
            </div>

        </x-slot>

        <div class="py-12">



            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">



                        @foreach ($posts as $post)
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                                <div>{{ $post->created_at->format('d.m.Y') }}</div>

                                <p>{{ $post->post }}</p>

                            </div>
                        @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
