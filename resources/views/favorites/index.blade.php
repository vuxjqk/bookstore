@extends('layouts.customer')

@section('title', __('Favorites'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-blue-600 font-medium">{{ __('Favorites') }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('Favorites') }}</h1>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="space-y-4">
                @forelse ($favorites as $item)
                    <div
                        class="favorite-item flex flex-col sm:flex-row items-start sm:items-center border-b border-gray-200 py-4 gap-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center rounded shrink-0">
                            @if (!empty($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['alt'] }}"
                                    class="w-full h-full object-cover rounded">
                            @else
                                <i class="fas fa-book text-2xl text-white"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                            <p class="text-gray-600 text-sm">{{ $item['author'] }}</p>
                            <p class="text-gray-600 text-sm">{{ number_format($item['sale_price'], 0, ',', '.') }}₫</p>
                        </div>
                        <button class="remove-favorite-btn text-gray-500 hover:text-red-600 transition-colors"
                            data-id="{{ $item['id'] }}" aria-label="{{ __('Remove from favorites') }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                @empty
                    <div id="emptyFavoritesMessage" class="text-center text-gray-600 py-8">
                        <p>{{ __('Your favorites list is empty.') }} <a href="{{ url('/') }}"
                                class="text-blue-600 hover:underline">{{ __('Continue shopping') }}</a></p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
