<a href="{{ route('photos.show', $photo->uuid) }}" class=" cursor-pointer "
    @if ($loop->last) id="last_record" @endif>

    <img loading="lazy"
        @if ($photo->is_video) src="{{ $photo->path }}"
    @else
        src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '160']) }}" @endif
        class="object-cover shadow-md rounded-md h-40 w-40 ">
    @if (now()->diffInDays(Carbon\Carbon::parse($photo->created_at)) < 3)
        <x-icons.new class="fill-blue-500" />
    @endif
</a>
