@props([
    'options' => [],
])

@php
    if (request('sort') == $options[0]) {
        $sortValue = $options[1];
        $icon = 'fa-sort-down';
    } elseif (request('sort') == $options[1]) {
        $sortValue = 'none';
        $icon = 'fa-sort-up';
    } else {
        $sortValue = $options[0];
        $icon = 'fa-sort';
    }
@endphp

<form>
    @foreach (request()->except(['sort']) as $key => $value)
        @if (is_array($value))
            @foreach ($value as $subKey => $subValue)
                <input type="hidden" name="{{ $key }}[{{ $subKey }}]" value="{{ $subValue }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <input type="hidden" name="sort" value="{{ $sortValue }}">
    <button type="submit" class="text-gray-500 hover:text-gray-700" title="{{ __('Sort') . ': ' . $sortValue }}">
        <i class="fas {{ $icon }}"></i>
    </button>
</form>
