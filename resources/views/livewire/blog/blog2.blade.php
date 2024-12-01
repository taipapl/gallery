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
        $view->posts = Photo::where('user_id', auth()->id())
            ->where('is_blog', 1)
            ->orderBy('photo_date', 'desc')
            ->paginate($this->perPage);
    }

    public function clickLightbox($image, $type): void
    {
        $this->dispatch('lightbox', $image, $type);
    }
};
?>
<x-container class="max-w-3xl">

    <x-panel>

        <div class="flex gap-3 items-center ">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Blog')</h2>

        </div>

    </x-panel>

    @if ($posts->count() == 0)
        <x-panel>
            @lang('No posts2')
        </x-panel>
    @endif

    @foreach ($posts as $photo)
        <x-panel>

            <div class="flex flex-col gap-3">


                <h2 class="text-xl font-semibold">{{ $photo->label }}</h2>
                <span class="text-sm">{{ $photo->created_at->diffForHumans() }}</span>


                <div wire:click="clickLightbox('{{ $photo->uuid }}', 'private')" class="cursor-pointer ">
                    <div class="w-full  md:w-auto relative " @if ($loop->last) id="last_record" @endif>

                        <img loading="lazy"
                            @if ($photo->is_video) src="{{ $photo->path }}"
                        @else
                            src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '600']) }}" @endif
                            class="object-cover shadow-md rounded-md w-full ">
                        @if (now()->diffInDays(Carbon\Carbon::parse($photo->created_at)) < 3)
                            <x-icons.new class="fill-blue-500 absolute top-1 left-1" />
                        @endif
                    </div>
                </div>



            </div>

        </x-panel>
    @endforeach

</x-container>
