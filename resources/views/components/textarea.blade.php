@props([
    'disabled' => false,
])

<textarea
    {{ $attributes->merge([
        'class' =>
            'block w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400',
    ]) }}
    @disabled($disabled)>
        {{ $slot }}
    </textarea>
