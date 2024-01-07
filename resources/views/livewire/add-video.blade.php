<div class="p-10">


    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
        {{ __('Add Video') }}
    </h2>

    @if ($videoUrl)
        <div class="mt-4">
            <iframe class="w-full" height="315" src="{{ $videoUrl }}" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    @endif

    <form wire:submit="save">
        <input class="w-full" wire:model="video" wire:change="changeURL()" />

        @if ($addError)
            <div class="text-red-500 mt-2 text-sm">
                {{ $addError }}
            </div>
        @endif

        <div class="flex justify-between mt-3">
            <x-primary-button wire:click="closeModal()">
                @lang('Close')
            </x-primary-button>

            <x-primary-button type="submit">
                @lang('Add')
            </x-primary-button>
        </div>

    </form>

</div>
