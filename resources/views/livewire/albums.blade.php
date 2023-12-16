<div>
    @foreach ($albums as $album)
        <a href="{{ route('album', $album->id) }}" wire:navigate>

            <div class="font-bold text-xl mb-2">{{ $album->name }}</div>

        </a>
    @endforeach

</div>
