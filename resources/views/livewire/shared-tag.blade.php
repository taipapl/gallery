<div class="p-10">

    <h1>@lang('Shared')</h1>
    <form wire:submit="add">
        <input type="text" name="email" id="email" wire:model.live.debounce.800ms="email"
            class="border-2 border-gray-300 p-2 w-full rounded">
        <div>
            @error('email')
                {{ $message }}
            @enderror
        </div>

        <div class="flex justify-end mt-2">
            <x-primary-button>{{ __('Add') }}</x-primary-button>
        </div>
    </form>

    @foreach ($shared as $key => $share)
        <div class="flex justify-between">
            <div>{{ $share->email->email }}</div>
            <div>
                <x-primary-button wire:click="delete({{ $share->id }})">{{ __('Delete') }}</x-primary-button>
            </div>
        </div>
    @endforeach


    <x-primary-button wire:click="close">{{ __('Close') }}</x-primary-button>


</div>
