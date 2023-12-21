<div class="p-10">

    <div class="flex justify-between ">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Video') }}
        </h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="closeModal()">
            @lang('Close')
        </button>
    </div>

    <input wire:model="video" wire:change="changeURL()" />



</div>
