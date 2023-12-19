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
        <div class="flex justify-between ">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Album') }}
            </h2>


            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                onclick="Livewire.dispatch('openModal', { component: 'add-photos' })">
                @lang('Add Photo')
            </button>
        </div>


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
