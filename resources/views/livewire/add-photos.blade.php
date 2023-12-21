<div class="p-10">

    <div class="flex justify-between ">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Photos') }}
        </h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="closeModal()">
            @lang('Close')
        </button>
    </div>

    <div class="flex gap-2 flex-wrap">
        @foreach ($photos ?? [] as $key => $photo)
            <div wire:click="addPhoto('{{ $photo->id }}')" class="h-40 w-40"
                @if ($loop->last) id="last_record" @endif
                style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                @if (in_array($photo->id, $photoIds))
                    +
                @endif
            </div>
        @endforeach
        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
    </div>

</div>
