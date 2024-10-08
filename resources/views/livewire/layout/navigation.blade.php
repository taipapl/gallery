<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="flex  w-[56px]">

    <div
        class="flex  fixed z-50 right-0 top-0 flex-col items-center w-16 h-screen py-8 space-y-8 bg-white dark:bg-gray-900 dark:border-gray-700">

        <a href="{{ route('albums.list') }}">
            <x-icons.empty-logo class="w-9 h-6" />
        </a>

        <x-nav-link :href="route('albums.list')" :active="request()->routeIs('albums.*')" wire:navigate>
            <x-icons.albums class="w-6 h-6" />
        </x-nav-link>


        <x-nav-link :href="route('photos.list')" :active="request()->routeIs('photos.*')" wire:navigate>
            <x-icons.photos class="w-6 h-6" />
        </x-nav-link>


        <x-nav-link :href="route('video.list')" :active="request()->routeIs('video.*')" wire:navigate>
            <x-icons.video class="w-6 h-6" />
        </x-nav-link>

        <x-nav-link :href="route('blog.list')" :active="request()->routeIs('blog.*')" wire:navigate>
            <x-icons.blog class="w-6 h-6" />
        </x-nav-link>


        <x-nav-link :href="route('shared.list')" :active="request()->routeIs('shared.*')" wire:navigate>
            <x-icons.shared class="w-6 h-6" />
        </x-nav-link>



        <x-nav-link :href="route('emails.list')" :active="request()->routeIs('emails.*')" wire:navigate>
            <x-icons.emails class="w-6 h-6" />
        </x-nav-link>


        <x-nav-link :href="route('trash')" :active="request()->routeIs('trash')" wire:navigate>
            <x-icons.trash class="w-6 h-6" />
        </x-nav-link>

        <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate>
            <x-icons.settings class="w-6 h-6" />
        </x-nav-link>
    </div>

</aside>
