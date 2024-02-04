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
        $photo = User::find(auth()->id())
            ->photos()
            ->onlyTrashed()
            ->find($photo_id);

        $photoTags = PhotoTag::where('photo_id', $photo->id)->get();
        foreach ($photoTags as $photoTag) {
            $photoTag->delete();
        }

        Storage::delete('photos/' . $photo->path);

        $photo->forceDelete();
    }

    public function deleteAll(): void
    {
        $photos = User::find(auth()->id())
            ->photos()
            ->onlyTrashed()
            ->get();

        foreach ($photos as $photo) {
            Storage::delete('photos/' . $photo->path);
            $photo->forceDelete();
        }
    }

    public function rendering(View $view): void
    {
        $view->photos = User::find(auth()->id())
            ->photos()
            ->onlyTrashed()
            ->paginate($this->perPage);
    }
};
?>

<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between ">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Trash') }}
                </h2>

                <div class="flex gap-2 justify-end">
                    <x-primary-button wire:confirm="{{ __('Are you sure you want to permanently delete the files?') }}"
                        wire:click="deleteAll">{{ __('Delete All') }}</x-primary-button>

                </div>

            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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
                            <div class="relative h-40 w-40" @if ($loop->last) id="last_record" @endif
                                style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                                <div wire:confirm="{{ __('Are you sure you want to permanently delete the file?') }}"
                                    wire:click="delete('{{ $photo->id }}')"
                                    class="cursor-pointer bg-slate-50 absolute p-2 border">@lang('Delete')</div>
                            </div>
                        @endforeach

                        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
