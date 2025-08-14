@extends('layouts.customer')

@section('title', __('Home'))

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-500 to-indigo-700 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-6">{{ __('Explore the World of Books') }}</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                {{ __('Thousands of diverse books from literature, science, to life skills. Find your favorite book today!') }}
            </p>
            <div class="flex justify-center gap-4">
                <button
                    class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">
                    <i class="fas fa-search mr-2"></i>{{ __('Explore Now') }}
                </button>
                <button
                    class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-indigo-600 transition duration-300">
                    <i class="fas fa-gift mr-2"></i>{{ __('Special Offers') }}
                </button>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12 text-gray-800">{{ __('Book Categories') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-heart text-4xl text-red-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Literature') }}</h4>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-flask text-4xl text-green-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Science') }}</h4>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-graduation-cap text-4xl text-blue-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Education') }}</h4>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-lightbulb text-4xl text-yellow-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Skills') }}</h4>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-child text-4xl text-purple-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Children') }}</h4>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md text-center cursor-pointer transform transition-transform duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-chart-line text-4xl text-indigo-500 mb-4"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('Economy') }}</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800">{{ __('Featured Books') }}</h3>
                <a href="{{ route('home.index') }}"
                    class="text-indigo-600 hover:text-indigo-800 font-semibold">{{ __('View All') }} <i
                        class="fas fa-arrow-right ml-2"></i></a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($related_books as $book)
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-300 ease-in-out hover:translate-y-[-5px] hover:shadow-lg">
                        <div class="relative">
                            <a href="{{ route('home.show', $book->slug) }}"
                                class="block h-64 bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                @if ($image = $book->images->first())
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}"
                                        class="object-contain h-full w-full" />
                                @else
                                    <i class="fas fa-book-open text-6xl text-white"></i>
                                @endif
                            </a>
                            @if ($book->original_price > $book->sale_price)
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    -{{ number_format((($book->original_price - $book->sale_price) / $book->original_price) * 100, 1) }}%
                                </div>
                            @endif
                            @if ($book->stock_quantity > 0)
                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    {{ __('In stock') }}
                                </div>
                            @else
                                <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded">
                                    {{ __('Pre-order') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-lg mb-2 text-gray-800">
                                {{ Str::limit($book->title, 15, '...') }}
                            </h4>
                            <p class="text-gray-600 mb-2">{{ $book->author->name ?? 'N/A' }}</p>
                            <div class="flex items-center mb-3 text-sm">
                                @if ($book->averageRating > 0)
                                    <div class="text-yellow-400 flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $book->averageRating)
                                                <i class="fas fa-star"></i>
                                            @elseif ($i - 0.5 <= $book->averageRating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 ml-2">({{ number_format($book->averageRating, 1) }})</span>
                                @else
                                    <span class="text-gray-500">{{ __('No reviews yet.') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-red-500 font-bold text-lg">{{ number_format($book->sale_price, 0, ',', '.') }}₫</span>
                                    @if ($book->original_price > $book->sale_price)
                                        <span
                                            class="text-gray-400 line-through ml-2">{{ number_format($book->original_price, 0, ',', '.') }}₫</span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    @auth
                                        @if (Auth::user()->favorites()->where('book_id', $book->id)->exists())
                                            <button data-id="{{ $book->id }}"
                                                class="remove-favorite-btn p-2 text-red-500 hover:text-red-800"
                                                title="{{ __('Added to Favorites') }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        @else
                                            <button data-id="{{ $book->id }}"
                                                class="add-favorite-btn p-2 text-gray-500 hover:text-red-500"
                                                title="{{ __('Add to Favorites') }}">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        @endif
                                    @endauth

                                    @guest
                                        <button onclick="showToast('{{ __('Please login to add to favorites.') }}', 'error')"
                                            class="p-2 text-gray-500 hover:text-red-500" title="{{ __('Add to Favorites') }}">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    @endguest

                                    <button data-id="{{ $book->id }}"
                                        class="add-to-cart-btn bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-300 {{ $book->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $book->stock_quantity < 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Promotion Banner -->
    <section class="py-16 bg-gradient-to-r from-orange-400 to-red-500 text-white">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-4xl font-bold mb-6">{{ __('Special Promotion') }}</h3>
            <p class="text-xl mb-8">{{ __('Up to 50% off on all literature and life skills books') }}</p>
            <div class="flex justify-center items-center gap-8 mb-8">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">15</div>
                    <div class="text-sm uppercase tracking-wide">{{ __('Days') }}</div>
                </div>
                <div class="text-4xl">:</div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">08</div>
                    <div class="text-sm uppercase tracking-wide">{{ __('Hours') }}</div>
                </div>
                <div class="text-4xl">:</div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">23</div>
                    <div class="text-sm uppercase tracking-wide">{{ __('Minutes') }}</div>
                </div>
                <div class="text-4xl">:</div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">45</div>
                    <div class="text-sm uppercase tracking-wide">{{ __('Seconds') }}</div>
                </div>
            </div>
            <button
                class="bg-white text-orange-500 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition duration-300">
                <i class="fas fa-fire mr-2"></i>{{ __('Shop Now') }}
            </button>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-3xl font-bold mb-6 text-gray-800">{{ __('Subscribe to Newsletter') }}</h3>
                <p class="text-gray-600 mb-8">
                    {{ __('Receive updates on new books, special promotions, and the best books') }}</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" placeholder="{{ __('Enter your email') }}"
                        class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500">
                    <button
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-envelope mr-2"></i>{{ __('Subscribe') }}
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
