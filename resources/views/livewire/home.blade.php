<?php

use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use App\Models\User;

new #[Layout('layouts.guest')] class extends Component {
    public string $search = '';
    public $profil;

    public $numberUsers;

    public function mount()
    {
        seo()->title(__('Gallery') . ' - ' . config('app.name'));
        $this->numberUsers = User::count();
    }

    public function searchProfil()
    {
        $this->profil = User::where('name', $this->search)
            ->where('is_public', 1)
            ->first();
    }
};

?>

<div>



    <form wire:submit="searchProfil">
        <label for="search"
            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">@lang('Search')</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model="search" id="search" type="search" name="search"
                class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search in {{ $numberUsers }} profil" />
            <button type="submit"
                class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
        </div>

    </form>


    @if ($profil)
        <div class="mt-4">
            <div class="flex items-center justify-between gap-4">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $profil->name }}</h2>


                <div>
                    <a target="_blank" href="{{ route('public.profile', $profil->public_url) }}"
                        class="text-blue-700 hover:underline dark:text-blue-500">
                        @lang('View Profil')
                    </a>
                </div>

            </div>
        </div>
    @elseif ($search)
        <div class="mt-4">
            <div class="text-lg font-semibold text-gray-900 dark:text-white">No profil found</div>
        </div>
    @endif

</div>
