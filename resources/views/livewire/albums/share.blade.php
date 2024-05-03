<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Tag;
use App\Models\pivot\UsersTags;
use App\Rules\OneEmail;
use App\Rules\Me;
use App\Mail\AlbumShared;
use App\Models\Email;
use App\Models\pivot\UsersEmails;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $email = '';
    public $shared;
    public $tag;
    public $checkbox_public;
    public $tag_uuid;

    public function add()
    {
        $validated = $this->validate([
            'email' => ['required', 'email', new OneEmail($this->tag->id), new Me()],
        ]);

        $email = Email::firstOrCreate(['email' => $validated['email']]);

        $usersEmails = UsersEmails::where('email_id', $email->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$usersEmails) {
            $email->users()->attach(Auth::user(), ['uuid' => Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
        }

        $usersTags = new UsersTags();
        $usersTags->uuid = Str::uuid();
        $usersTags->user_id = Auth::id();
        $usersTags->email_id = $email->id;
        $usersTags->tag_id = $this->tag->id;

        $usersTags->save();

        $this->shared = UsersTags::where('tag_id', $this->tag->id)->get();
        $this->email = '';

        Mail::to($validated['email'])->send(new AlbumShared($this->tag, $usersTags));
    }

    public function close()
    {
        $this->closeModal();
    }

    public function delete(UsersTags $usersTags)
    {
        $usersTags->delete();
        $this->shared = UsersTags::where('tag_id', $this->tag->id)->get();
    }

    public function publicAlbum()
    {
        if (empty($this->tag->public_url)) {
            $this->tag->public_url = Str::uuid();
        }

        $this->tag->is_public = $this->checkbox_public;
        $this->tag->save();
    }

    public function changePublicUrl()
    {
        $this->tag->public_url = Str::uuid();
        $this->tag->save();
    }

    public function mount($uuid)
    {
        $this->tag_uuid = $uuid;
        $this->tag = Tag::where('uuid', $uuid)->firstOrFail();
        $this->shared = UsersTags::where('tag_id', $this->tag->id)->get();
        $this->checkbox_public = $this->tag->is_public;
    }
};
?>
<div class="flex w-full" x-data="{ active: false }">

    <div class="flex-none order-3" x-show="active">
        <livewire:layout.navigation />
    </div>


    <div class="flex-none order-2" x-show="!active">
        <div
            class="fixed right-0 top-0 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Album')</h2>

            <div class="mt-8 space-y-4">

                <x-sub-nav-link href="{{ route('albums.show', $this->tag->uuid) }}">
                    @lang('Cancel')
                </x-sub-nav-link>

            </div>

        </div>
    </div>


    <div class="grow order-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="p-10 flex flex-col gap-3 ">

                        <div class="flex justify-between ">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Shared') }} {{ $tag->name }}
                            </h2>
                        </div>

                        <form wire:submit="add" class="flex flex-col">

                            <div class="flex gap-3  w-full">

                                <input type="text" wire:model="email"
                                    class="border-2 border-gray-300 w-full rounded">

                                <div class="flex justify-end mt-2">
                                    <x-primary-button>{{ __('Add') }}</x-primary-button>
                                </div>

                            </div>

                            <div>
                                @error('email')
                                    <span class="text-red-700 "> {{ $message }} </span>
                                @enderror
                            </div>
                        </form>

                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Shared list') }}
                            </h2>
                            @foreach ($shared as $key => $share)
                                <div class="flex justify-between">
                                    <div>{{ $share->email->email }} ({{ __('view') . ': ' . $share->count }})</div>
                                    <div>
                                        <div class="cursor-pointer" wire:confirm="{{ __('Are you sure?') }}"
                                            wire:click="delete('{{ $share->id }}')">{{ __('Delete') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Public album') }}
                            </h2>
                            <label wire:click="publicAlbum()" class="relative inline-flex items-center cursor-pointer">
                                <input wire:model="checkbox_public" type="checkbox"
                                    @if ($tag->is_public == 1) checked @endif class="sr-only peer"
                                    value="1">
                                <div
                                    class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Public album')</span>
                            </label>


                            @if ($tag->is_public)
                                <div>
                                    {{ $tag->public_url }}
                                    <x-primary-link target="_blank"
                                        href="{{ route('public.album', $tag->public_url) }}">{{ __('Open') }}</x-primary-link>
                                    ({{ __('view') . ' ' . $tag->count }})

                                </div>

                                <div class="flex mt-2">
                                    <x-primary-button
                                        wire:confirm="{{ __('If you change the link, you will have to resend it to the people you want to share the album?') }}"
                                        wire:click="changePublicUrl()">{{ __('Change album url') }}</x-primary-button>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
