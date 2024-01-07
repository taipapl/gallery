<div class="p-10">


    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
        {{ __('Add Photos') }}
    </h2>




    <form wire:submit="save">
        <!-- File Input -->
        <input class="w-full" type="file" wire:model="photos" multiple>

        @error('photos.*')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="flex justify-between mt-3">
            <x-primary-button wire:click="closeModal()">
                @lang('Close')
            </x-primary-button>

            <x-primary-button type="submit">
                @lang('Save')
            </x-primary-button>
        </div>


    </form>


</div>
