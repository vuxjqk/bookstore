@props([
    'type' => 'checkbox', // 'checkbox' or 'radio'
    'name' => null,
    'value' => null,
    'label' => null,
    'checked' => false,
    'id' => null,
])

@php
    $id = $id ?? $name . ($type === 'radio' && $value ? '_' . $value : '');
@endphp

<div class="flex items-center" {{ $attributes->merge([]) }}>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" id="{{ $id }}"
        class="h-4 w-4 border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400"
        {{ $checked ? 'checked' : '' }} />
    @if ($label)
        <label for="{{ $id }}"
            class="pl-2 font-medium text-sm text-gray-700 transition-colors duration-150 hover:text-gray-900">
            {{ $label }}
        </label>
    @endif
</div>
