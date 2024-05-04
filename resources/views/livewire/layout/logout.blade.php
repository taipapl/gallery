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

<div>
    <x-sub-nav-link wire:click="logout">
        <span class="whitespace-nowrap "> {{ __('Log Out') }} </span>
    </x-sub-nav-link>
</div>
