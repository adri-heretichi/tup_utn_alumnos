@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 focus:border-black focus:ring-black rounded-md shadow-sm']) }}>
