@props([
    'value' => null,
    'icon' => null,
])

<label
    {{ $attributes->merge([
        'class' =>
            'block font-medium text-sm text-gray-700 flex items-center gap-2 transition-colors duration-150 hover:text-gray-900',
    ]) }}>
    @if ($icon)
        <i class="{{ $icon }} text-gray-500"></i>
    @endif
    {{ __($value) ?? __($slot) }}
</label>
