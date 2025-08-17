@props(['route', 'title'])

<a href="{{ $route }}"
    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    <i class="fas fa-plus"></i>
    {{ $title }}
</a>
