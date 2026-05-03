@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active w-100 text-start'
            : 'nav-link w-100 text-start';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
