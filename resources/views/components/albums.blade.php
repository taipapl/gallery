<div class="flex gap-2 flex-wrap ">
    @foreach ($albums as $album)
        <a href="{{ route('albums.show', ['uuid' => $album->uuid]) }}" class=" cursor-pointer" wire:navigate>

            @if ($album->cover)
                <div class="h-40 w-40 border-2  block overflow-hidden "
                    @if ($loop->last) id="last_record" @endif
                    style="background-image: url('{{ route('get.image', ['photo' => $album->cover]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
                </div>
            @else
                <div class="h-40 w-40 bg-gray-200 flex items-center justify-center">
                    <div class="text-center text-lg text-gray-500">@lang('No photos')
                    </div>
                </div>
            @endif


            <div class="text-sm">
                <div> {{ Str::limit($album->name, 18) }}</div>
                <div> {{ $album->photos->count() }} @lang('elements')</div>
            </div>

        </a>
    @endforeach
</div>

@if (count($albums) == 0)
    <div class="text-center text-lg text-black ">No Albums</div>
@endif
