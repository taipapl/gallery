<a href="{{ route('photos.show', $photo->uuid) }}" class=" cursor-pointer h-40 w-40 shadow-md rounded-md"
    @if ($loop->last) id="last_record" @endif
    @if ($photo->is_video) style="background-image: url('{{ $photo->path }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">
    @else
    style="background-image: url('{{ route('get.image', ['photo' => $photo->uuid]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;"> @endif
    @if (now()->diffInDays(Carbon\Carbon::parse($photo->created_at)) < 3) <x-icons.new class="fill-blue-500" /> @endif </a>
