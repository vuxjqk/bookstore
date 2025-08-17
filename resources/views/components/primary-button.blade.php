@props(['disabled' => false])

<button
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
    ]) }}
    @disabled($disabled)>
    {{ $slot }}
</button>
