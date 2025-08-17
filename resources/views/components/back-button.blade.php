@props(['route'])

<a href="{{ $route }}"
    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-200">
    <i class="fas fa-arrow-left"></i>
    {{ __('Back') }}
</a>
