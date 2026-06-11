<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-transparent bg-blue-950 px-5 py-3 text-xs font-extrabold uppercase tracking-wider text-white shadow-sm transition ease-in-out duration-150 hover:bg-blue-900 focus:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-100 active:bg-blue-950']) }}>
    {{ $slot }}
</button>
