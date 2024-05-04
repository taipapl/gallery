<div {{ $attributes->merge(['class' => 'mx-auto sm:px-6 lg:px-8 mb-4']) }}>
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class=" p-6 text-gray-900 ">

            {{ $slot }}
        </div>
    </div>
</div>
