@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-blue-950 px-4 py-3 text-start text-sm font-extrabold text-white focus:outline-none focus:ring-4 focus:ring-blue-100 transition duration-150 ease-in-out'
            : 'block w-full rounded-xl px-4 py-3 text-start text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
