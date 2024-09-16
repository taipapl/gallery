<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\pivot\UsersEmails;
use Illuminate\Support\Facades\Log;
use App\Models\Email;
use App\Mail\RemindProfil;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 10;

    public $emails;

    public $share_blog;

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
        $email->share_blog = $email->share_blog == 0 ? 1 : 0;
        $email->save();
    }

    public function remind($uuid)
    {
        $UsersEmails = UsersEmails::where('uuid', $uuid)->first();

        $email = Email::where('id', $UsersEmails->email_id)->first();

        if (config('gallery.email_queues') === false) {
            Mail::to($email->email)->send(new RemindProfil($UsersEmails));
        } else {
            Mail::to($email->email)->queue(new RemindProfil($UsersEmails));
        }
    }

    public function rendering(View $view): void
    {
        $view->emails = Auth::user()
            ->emails()
            ->paginate($this->perPage)
            ->unique('email');
    }
};
?>

<x-container>

    <x-panel>

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Emails') }}</h2>

    </x-panel>

    <x-panel>

        @if (count($emails) == 0)
            <div class="text-center text-lg text-black ">@lang('No emails')</div>
        @endif

        <div class="flex gap-2 flex-wrap">
            @foreach ($emails ?? [] as $key => $email)
                <div class="h-40 w-40" @if ($loop->last) id="last_record" @endif>

                    <div> {{ $email->email }}</div>

                    <label class="relative inline-flex items-center cursor-pointer">


                        <input wire:click="sendPublic('{{ $email->pivot->id }}')" type="checkbox" wire:model="is_public"
                            @if ($email->pivot->share_blog == 1) checked @endif class="sr-only peer" value="1">
                        <div
                            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Shere profil')
                        </span>




                    </label>

                    <div>

                        @if ($email->pivot->share_blog == 1)
                            <x-primary-link target="_blank" href="{{ route('user.profil', $email->pivot->uuid) }}"
                                class="mt-2">
                                {{ __('View') }}
                            </x-primary-link>

                            <x-primary-button wire:click="remind('{{ $email->pivot->uuid }}')" class="mt-2">
                                {{ __('Remind') }}
                            </x-primary-button>
                        @endif
                    </div>
                </div>
            @endforeach
            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
        </div>
    </x-panel>

</x-container>
