<div class="flex gap-2 flex-wrap ">
    @foreach ($albums as $album)
        <a @if ($loop->last) id="last_record" @endif
            href="{{ route('albums.show', ['uuid' => $album->uuid]) }}" class="w-full md:w-auto cursor-pointer"
            wire:navigate>

            @if ($album->cover)
                <img loading="lazy" src="{{ route('get.image', ['photo' => $album->cover, 'size' => '160']) }}"
                    alt="{{ $album->name }}" class="h-40 w-full md:w-40 object-cover object-top  rounded-lg shadow-lg">
            @else
                <div class="h-40 w-full md:w-40 bg-gray-200 flex items-center justify-center rounded-lg shadow-lg">
                    <div class="text-center text-lg text-gray-500">@lang('No photos')
                    </div>
                </div>
            @endif

            <div class="text-sm mt-1">
                <div> {{ Str::limit($album->name, 18) }}</div>
                <div> {{ $album->photos->count() }} @lang('elements')</div>
            </div>

        </a>
    @endforeach

    <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
</div>

@if (count($albums) == 0)
    <div class="text-center text-lg text-black ">No Albums</div>
@endif
