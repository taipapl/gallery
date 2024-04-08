@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'p-1.5 text-blue-500 transition-colors duration-200 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-gray-800'
            : 'p-1.5 text-gray-500 focus:outline-nones transition-colors duration-200 rounded-lg dark:text-gray-400 dark:hover:bg-gray-800 hover:bg-gray-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
