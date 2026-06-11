@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-slate-300 bg-white text-slate-800 shadow-sm focus:border-blue-950 focus:ring-blue-950']) }}>
