<a href="{{ route('photos.show', $photo->uuid) }}" class="w-full  md:w-auto relative cursor-pointer "
    @if ($loop->last) id="last_record" @endif>

    <img loading="lazy"
        @if ($photo->is_video) src="{{ $photo->path }}"
    @else
        src="{{ route('get.image', ['photo' => $photo->uuid, 'size' => '600']) }}" @endif
        class="object-cover shadow-md rounded-md w-full h-50  md:h-40 md:w-40 ">
    @if (now()->diffInDays(Carbon\Carbon::parse($photo->created_at)) < 3)
        <x-icons.new class="fill-blue-500 absolute top-1 left-1" />
    @endif
</a>
