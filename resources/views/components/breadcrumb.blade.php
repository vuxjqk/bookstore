<nav class="bg-gray-50 px-6 py-3 text-gray-700 rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
    <ol class="list-reset flex items-center text-sm font-medium">
        @foreach ($items as $index => $item)
            <li class="flex items-center">
                @if ($item['url'] ?? false)
                    <a href="{{ $item['url'] }}"
                        class="text-blue-600 hover:text-blue-800 transition-colors duration-150 flex items-center gap-1">
                        @if ($index === 0)
                            <i class="fas fa-home text-blue-500"></i>
                        @endif
                        {{ __($item['label']) }}
                    </a>
                @else
                    <span class="text-gray-500">{{ __($item['label']) }}</span>
                @endif
                @if (!$loop->last)
                    <span class="mx-2 text-gray-400">/</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
