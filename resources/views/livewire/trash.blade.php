<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use App\Models\pivot\PhotoTag;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $perPage = 10;

    public $photos = [];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(): void
    {
        seo()->title(__('Trash') . ' - ' . config('app.name'));
    }

    public function delete($photo_id): void
    {
        $photo = User::find(auth()->id())->photos()->onlyTrashed()->find($photo_id);

        Storage::delete('photos/' . $photo->path);

        $photo->forceDelete();
    }

    public function recorvery($photo_id): void
    {
        $photo = User::find(auth()->id())->photos()->onlyTrashed()->find($photo_id);

        $photo->restore();
    }

    public function deleteAll(): void
    {
        $photos = User::find(auth()->id())->photos()->onlyTrashed()->get();

        foreach ($photos as $photo) {
            Storage::delete('photos/' . $photo->user_id . '/' . $photo->path);
            $photo->forceDelete();
        }
    }

    public function rendering(View $view): void
    {
        $view->photos = auth()
            ->user()
            ->photos()
            ->onlyTrashed()
            ->paginate($this->perPage);
    }
};
?>

<x-container>



    <x-card>

        <div class="flex items-center">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Video')</h2>

            <div>

                <x-sub-nav-link wire:confirm="{{ __('Are you sure you want to permanently delete the files?') }}"
                    wire:click="deleteAll">{{ __('Delete All') }}
                </x-sub-nav-link>

            </div>

        </div>

    </x-card>

    <x-card>
        @if (count($photos) == 0)
            <div class="text-center text-lg text-black ">@lang('Trash is empty')</div>
        @endif

        <div class="flex gap-2 flex-wrap">
            @php($dataLabel = null)
            @foreach ($photos ?? [] as $key => $photo)
                @if ($dataLabel != $photo->photo_date->format('F Y'))
                    @php($dataLabel = $photo->photo_date->format('F Y'))
                    <div class="w-full text-center text-lg text-black ">{{ $dataLabel }}</div>
                @endif
                <div class="relative h-40 w-40" @if ($loop->last) id="last_record" @endif>
                    <img src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '160']) }}"
                        alt="{{ $photo->name }}" class="object-cover mx-auto w-full rounded-lg shadow-lg">
                    <div wire:confirm="{{ __('Are you sure you want to permanently delete the file?') }}"
                        wire:click="delete('{{ $photo->id }}')"
                        class="cursor-pointer bg-slate-50 absolute top-0 p-2 border">@lang('Delete')</div>

                    <div wire:confirm="{{ __('Are you sure you want recorvery the file?') }}"
                        wire:click="recorvery('{{ $photo->id }}')"
                        class="cursor-pointer bg-slate-50 absolute right-0 top-0 p-2 border">@lang('Recorvery')
                    </div>
                </div>
            @endforeach

            <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>

        </div>

    </x-card>



</x-container>
