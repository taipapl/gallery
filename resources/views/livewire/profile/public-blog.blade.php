<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public $checkbox_public;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->checkbox_public = Auth::user()->is_blog;
    }

    public function publicBlog(): void
    {
        Auth::user()->update([
            'is_blog' => (int) $this->checkbox_public,
        ]);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Public User Blog') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('You can start make your blog') }}
        </p>
    </header>


    <label wire:click="publicBlog()" class="relative inline-flex items-center cursor-pointer">
        <input wire:model="checkbox_public" type="checkbox" @if (Auth::user()->is_blog) checked @endif
            class="sr-only peer" value="1">
        <div
            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
        </div>
        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Public')
        </span>
    </label>




</section>
