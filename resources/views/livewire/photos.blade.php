<div>
    <div class="flex gap-2 flex-wrap">
        @foreach ($photos ?? [] as $key => $photo)
            <div class="h-40 w-40" @if ($loop->last) id="last_record" @endif
                style="background-image: url('{{ route('get.image', ['filename' => $photo->path]) }}');  background-repeat: no-repeat; background-position: top center;  background-size: cover;">

            </div>
        @endforeach
        <div x-intersect="$wire.loadMore()" class="text-center text-lg text-white "></div>
    </div>
</div>
