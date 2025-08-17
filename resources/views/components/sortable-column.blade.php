@props([
    'options' => [],
])

@php
    if (request('sort') == $options[0]) {
        $value = $options[1];
        $icon = 'fa-sort-down';
    } elseif (request('sort') == $options[1]) {
        $value = 'none';
        $icon = 'fa-sort-up';
    } else {
        $value = $options[0];
        $icon = 'fa-sort';
    }
@endphp

<form>
    <input type="hidden" name="sort" value="{{ $value }}">
    <button type="submit" class="text-gray-500 hover:text-gray-700" title="{{ __('Sort') . ': ' . $value }}">
        <i class="fas {{ $icon }}"></i>
    </button>
</form>
