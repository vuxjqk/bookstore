@props([
    'options' => [],
    'placeholder' => null,
    'selected' => null,
    'disabled' => false,
])

<select
    {{ $attributes->merge([
        'class' =>
            'block w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400',
    ]) }}
    @disabled($disabled)>
    @if ($placeholder)
        <option value="" {{ is_null($selected) || (is_array($selected) && empty($selected)) ? 'selected' : '' }}>
            {{ $placeholder }}
        </option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}"
            {{ is_array($selected) ? (in_array($value, $selected) ? 'selected' : '') : ($selected == $value ? 'selected' : '') }}>
            {{ $label }}
        </option>
    @endforeach
</select>
