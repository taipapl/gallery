<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public $lang;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->lang = Auth::user()->lang;
    }

    public function change(): void
    {
        Auth::user()->update([
            'lang' => $this->lang,
        ]);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Language') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('You can change your language') }}
        </p>
    </header>


    <label class="relative inline-flex items-center cursor-pointer">

        <select wire:model="lang" wire:change="change"
            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:focus:border-blue-600 dark:focus:ring-blue-800 dark:focus:ring-opacity-50">
            <option value="en">English</option>
            <option value="pl">Polish</option>

        </select>
    </label>




</section>
