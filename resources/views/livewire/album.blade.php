<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use App\Models\Tag;

new #[Layout('layouts.app')] class extends Component {
    public Tag $tag;

    public $name = '';

    public function updated($name, $value)
    {
        $this->tag->update([
            $name => $value,
        ]);
    }

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
        $this->name = $tag->name;
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Album') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit>
                        <input type="text" name="name" id="name" wire:model.live.debounce.800ms="name"
                            class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="@lang('Album name')" />
                    </form>





                </div>
            </div>
        </div>
    </div>
</div>
