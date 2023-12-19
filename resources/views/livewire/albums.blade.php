<div class="flex gap-3">
    @foreach ($albums as $album)
        <a href="{{ route('album', $album->id) }}" class="border-2 h-[125px] w-[125px] block overflow-hidden "
            wire:navigate>

            <div class="font-bold text-xl mb-2">{{ $album->name }}</div>

        </a>
    @endforeach

</div>
