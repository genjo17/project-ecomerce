<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-transparent bg-red-600 px-4 py-2 text-xs font-extrabold uppercase tracking-widest text-white transition duration-150 hover:bg-red-500 focus:outline-none focus:ring-4 focus:ring-red-100 active:bg-red-700']) }}>
    {{ $slot }}
</button>
