<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create album') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <input type="text" name="name" id="name" wire:model="name"
                        class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Album name')" />





                </div>
            </div>
        </div>
    </div>
</x-app-layout>
