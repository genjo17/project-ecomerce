@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-xl bg-blue-950 px-4 py-2 text-sm font-extrabold leading-5 text-white focus:outline-none focus:ring-4 focus:ring-blue-100 transition duration-150 ease-in-out'
            : 'inline-flex items-center rounded-xl px-4 py-2 text-sm font-bold leading-5 text-slate-600 hover:bg-blue-50 hover:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
