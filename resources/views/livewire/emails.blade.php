<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\pivot\UsersEmails;
use Illuminate\Support\Facades\Log;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 10;

    public $emails;

    public $send_public;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        seo()->title(__('Emails') . ' - ' . config('app.name'));
    }

    public function sendPublic($id)
    {
        $email = UsersEmails::find($id);
        $email->send_public = $email->send_public == 0 ? 1 : 0;
        $email->save();
    }

    public function rendering(View $view): void
    {
        $view->emails = Auth::user()->emails()->get();
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between ">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Emails') }}
            </h2>

            <div class="flex gap-2 justify-end">

                <x-secondary-link href="{{ route('shared') }}">
                    @lang('Shared')
                </x-secondary-link>
            </div>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (count($emails) == 0)
                        <div class="text-center text-lg text-black ">@lang('No emails')</div>
                    @endif

                    <div class="flex gap-2 flex-wrap">
                        @foreach ($emails ?? [] as $key => $email)
                            <div class="h-40 w-40" @if ($loop->last) id="last_record" @endif>

                                <div> {{ $email->email }}</div>

                                <label class="relative inline-flex items-center cursor-pointer">

                                    <input wire:click="sendPublic('{{ $email->pivot->id }}')" type="checkbox"
                                        wire:model="is_public" @if ($email->pivot->send_public == 1) checked @endif
                                        class="sr-only peer" value="1">
                                    <div
                                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span
                                        class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Public')

                                    </span>
                                </label>
                            </div>
                        @endforeach
                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
