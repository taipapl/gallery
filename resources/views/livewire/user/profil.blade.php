<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tag;
use Illuminate\View\View;
use App\Models\pivot\UsersTags;
use App\Models\pivot\UsersEmails;
use App\Models\Photo;
use App\Models\Email;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $userEmail;

    public $tagsIds;

    public $albums;

    public $profil;

    public $email;

    public $perPage = 50;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(string $user_url)
    {
        $this->userEmail = UsersEmails::where('uuid', $user_url)->firstOrFail();

        $this->email = Email::where('id', $this->userEmail->email_id)->firstOrFail();

        $this->profil = User::with(['firstPost.photos', 'firstPost.gallery'])
            ->where('id', $this->userEmail->user_id)
            ->firstOrFail();

        if ($this->profil->is_blog) {
            $this->lastPost = $this->profil->posts()->latest()->first();
        }

        $this->tagsIds = UsersTags::where('user_id', $this->userEmail->user_id)
            ->get()
            ->pluck('tag_id')
            ->toArray();
    }

    public function rendering(View $view): void
    {
        $view->albums = $this->email->tags()->paginate($this->perPage);
    }
};

?>

<div>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $profil->name }}</h1>

    <div class="flex gap-2 flex-wrap ">

        @if ($profil->is_blog)
            <a href="{{ route('public.blog', $profil->blog_url) }}">
                <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
                    <div class="text-center text-lg text-gray-500">
                        @lang('Blog')
                    </div>
                </div>
            </a>
        @endif

        @foreach ($albums as $album)
            <a href="{{ route('user.album', $album->pivot->uuid) }}" class=" cursor-pointer" wire:navigate>

                <div>

                    @if ($album->cover)
                        <div class="h-40 w-40 border-2  block overflow-hidden "
                            @if ($loop->last) id="last_record" @endif
                            style="background-image: url('{{ route('get.cover', ['photo' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                        </div>
                    @else
                        <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
                            <div class="text-center text-lg text-gray-500">@lang('No photos')
                            </div>
                        </div>
                    @endif


                    <div class="text-sm">
                        <div> {{ Str::limit($album->name, 18) }}</div>
                        <div> {{ $album->photos->count() }} @lang('elements')</div>
                    </div>

                </div>

            </a>
        @endforeach
    </div>
</div>
