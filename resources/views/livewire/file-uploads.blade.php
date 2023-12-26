<div class="p-10">

    <div class="flex justify-between ">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Photos') }}
        </h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="closeModal()">
            @lang('Close')
        </button>
    </div>



    <form wire:submit="save">
        <!-- File Input -->
        <input type="file" wire:model="photos" multiple>

        @error('photos.*')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="mt-5 text-center">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Save
                photo</button>
        </div>


    </form>


</div>
