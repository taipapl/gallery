<a href="{{ route('photos.show', $photo->id) }}" class=" cursor-pointer h-40 w-40"
    @if ($loop->last) id="last_record" @endif
    @if ($photo->is_video) style="background-image: url('{{ $photo->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
    @else
    style="background-image: url('{{ route('get.image', ['photo' => $photo->id]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;"> @endif
    </a>
