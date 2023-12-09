<x-app-layout>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Albums') }}
            </h2>

            <div class="flex justify-end">
                <a href="{{ route('albums.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Album
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <livewire:file-uploads />

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
