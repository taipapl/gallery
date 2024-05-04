<div {{ $attributes->merge(['class' => 'container mx-auto md:mt-3 px-3 md:px-0']) }}>
    <div class="flex flex-col gap-3">
        {{ $slot }}
    </div>
</div>
