<div class="flex gap-3">
    @foreach ($albums as $album)
        <div href="{{ route('albums.album', $album->id) }}" class=" cursor-pointer" wire:navigate>

            @if ($album->cover)
                <div class="h-40 w-40 border-2  block overflow-hidden "
                    @if ($loop->last) id="last_record" @endif
                    style="background-image: url('{{ route('get.image', ['filename' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

                </div>
            @else
                <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
                    <div class="text-center text-lg text-gray-500">@lang('No photos')</div>
                </div>
            @endif


            <div class="text-sm">
                <div> {{ $album->name }}</div>
                <div> {{ $album->photos->count() }} @lang('elements')</div>
            </div>

        </div>
    @endforeach

    @if (count($albums) == 0)
        <div class="text-center text-lg text-black ">No Albums</div>
    @endif

</div>
