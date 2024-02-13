<div class="p-10 flex flex-col gap-3 ">

    <div class="flex justify-between ">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shared') }}
        </h2>
    </div>

    <form wire:submit="add" class="flex flex-col">

        <div class="flex gap-3  w-full">
            <input type="text" wire:model="email" class="border-2 border-gray-300 w-full rounded">


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
                <div>{{ $share->email->email }}</div>
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
            <input wire:model="checkbox_public" type="checkbox" @if ($tag->is_public == 1) checked @endif
                class="sr-only peer" value="1">
            <div
                class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Public album')</span>
        </label>


        @if ($tag->is_public)
            <div>
                {{ $tag->public_url }}

                <x-primary-link target="_blank"
                    href="{{ route('public_album', $tag->public_url) }}">{{ __('Open') }}</x-primary-link>
            </div>

            <div class="flex mt-2">
                <x-primary-button
                    wire:confirm="{{ __('If you change the link, you will have to resend it to the people you want to share the album?') }}"
                    wire:click="changePublicUrl()">{{ __('Change album url') }}</x-primary-button>
            </div>
        @endif
    </div>

    <div class="mt-3">
        <x-primary-button wire:click="close">{{ __('Close') }}</x-primary-button>
    </div>


</div>
