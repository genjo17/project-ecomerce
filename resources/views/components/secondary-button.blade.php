<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-xl border border-blue-100 bg-white px-4 py-2 text-xs font-extrabold uppercase tracking-wider text-blue-950 shadow-sm transition ease-in-out duration-150 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
