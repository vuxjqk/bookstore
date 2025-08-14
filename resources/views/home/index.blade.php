@extends('layouts.customer')

@section('title', __('All Books'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-indigo-600">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-indigo-600">{{ __('All Books') }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 overflow-y-auto max-h-[calc(100vh-200px)]">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">{{ __('Filters') }}</h3>
                        <button id="clearFilters" class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-times mr-1"></i>{{ __('Clear All') }}
                        </button>
                    </div>

                    <!-- Active Filters -->
                    <div id="activeFilters" class="mb-6 hidden">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Active Filters') }}</h4>
                        <div class="flex flex-wrap gap-2" id="filterTags"></div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Categories') }}</h4>
                        <div class="space-y-2">
                            @foreach ($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" class="category-filter mr-2" value="{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <span class="text-gray-600">{{ __($category->name) }}</span>
                                    <span class="ml-auto text-gray-400">({{ $category->books_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Price Range') }}</h4>
                        <div class="space-y-4">
                            <input type="range" id="priceRange"
                                class="w-full h-2 bg-gray-200 rounded appearance-none cursor-pointer" min="0"
                                max="500000" value="{{ request('price_max', 500000) }}">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>0₫</span>
                                <span id="priceValue">500,000₫</span>
                            </div>
                            <div class="flex gap-2">
                                <input type="number" id="minPrice" placeholder="{{ __('From') }}"
                                    class="w-1/2 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-indigo-500"
                                    min="0" value="{{ request('price_min', 0) }}">
                                <input type="number" id="maxPrice" placeholder="{{ __('To') }}"
                                    class="w-1/2 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Rating') }}</h4>
                        <div class="space-y-2">
                            @for ($i = 5; $i >= 3; $i--)
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="{{ $i }}" class="mr-2"
                                        {{ request('rating') == $i ? 'checked' : '' }}>
                                    <div class="text-yellow-400 flex">
                                        @for ($j = 1; $j <= 5; $j++)
                                            <i class="{{ $j <= $i ? 'fas fa-star' : 'far fa-star' }}"></i>
                                        @endfor
                                    </div>
                                    <span
                                        class="ml-2 text-gray-600 text-xs">{{ $i === 5 ? __($i . ' Stars') : __('over ') . __($i . ' Stars') }}
                                    </span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Publisher Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Publishers') }}</h4>
                        <div class="space-y-2">
                            @foreach ($publishers as $publisher)
                                <label class="flex items-center">
                                    <input type="checkbox" class="publisher-filter mr-2" value="{{ $publisher->id }}"
                                        {{ in_array($publisher->id, request('publishers', [])) ? 'checked' : '' }}>
                                    <span class="text-gray-600">{{ __($publisher->name) }}</span>
                                    <span class="ml-auto text-gray-400">({{ $publisher->books_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Language Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Languages') }}</h4>
                        <div class="space-y-2">
                            @foreach ($languages as $language)
                                <label class="flex items-center">
                                    <input type="checkbox" class="language-filter mr-2" value="{{ $language }}"
                                        {{ in_array($language, request('languages', [])) ? 'checked' : '' }}>
                                    <span class="text-gray-600">{{ __($language) }}</span>
                                    <span
                                        class="ml-auto text-gray-400">({{ App\Models\Book::where('language', $language)->count() }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Availability Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-gray-700">{{ __('Availability') }}</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="availability-filter mr-2" value="1"
                                    {{ in_array('1', request('in_stock', [])) ? 'checked' : '' }}>
                                <span class="text-gray-600">{{ __('In Stock') }}</span>
                                <span
                                    class="ml-auto text-gray-400">({{ App\Models\Book::where('stock_quantity', '>', 0)->count() }})</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="availability-filter mr-2" value="0"
                                    {{ in_array('0', request('in_stock', [])) ? 'checked' : '' }}>
                                <span class="text-gray-600">{{ __('Pre-order') }}</span>
                                <span
                                    class="ml-auto text-gray-400">({{ App\Models\Book::where('stock_quantity', 0)->count() }})</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Top Bar -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col sm:flex-row justify-end items-center">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <label class="text-gray-600">{{ __('Sort By') }}</label>
                                <select id="sortBy"
                                    class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                        {{ __('Newest') }}
                                    </option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                        {{ __('Oldest') }}
                                    </option>
                                    <option value="price_low_to_high"
                                        {{ request('sort') === 'price_low_to_high' ? 'selected' : '' }}>
                                        {{ __('Price: Low to High') }}
                                    </option>
                                    <option value="price_high_to_low"
                                        {{ request('sort') === 'price_high_to_low' ? 'selected' : '' }}>
                                        {{ __('Price: High to Low') }}
                                    </option>
                                    <option value="highest_rated"
                                        {{ request('sort') === 'highest_rated' ? 'selected' : '' }}>
                                        {{ __('Highest Rated') }}
                                    </option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                                        {{ __('Most Popular') }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="gridView" class="p-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button id="listView" class="p-2 bg-gray-300 text-gray-600 rounded hover:bg-gray-400">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Books Grid -->
                <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:-translate-y-1 hover:shadow-lg transition duration-300">
                            <div class="relative">
                                <a href="{{ route('home.show', $book->slug) }}"
                                    class="block h-64 bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                    @if ($image = $book->images->first())
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->alt_text }}" class="object-contain h-full w-full" />
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
                                        {{ __('In Stock') }}
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
                                <p class="text-gray-600 mb-2">{{ $book->author->name ?? __('N/A') }}</p>
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
                                        <span
                                            class="text-gray-500 ml-2">({{ number_format($book->averageRating, 1) }})</span>
                                    @else
                                        <span class="text-gray-500">{{ __('No Reviews Yet') }}</span>
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
                                    <div class="flex gap-2">
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
                                            <button
                                                onclick="showToast('{{ __('Please login to add to favorites.') }}', 'error')"
                                                class="p-2 text-gray-600 hover:text-red-500"
                                                title="{{ __('Add to Favorites') }}">
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

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Debounce function to limit rapid input events
                const debounce = (func, wait) => {
                    let timeout;
                    return (...args) => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                };

                // Collect all filter values and submit form
                const submitFilters = () => {
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = '{{ route('home.index') }}';

                    // Categories (checkboxes)
                    const categoryCheckboxes = document.querySelectorAll('.category-filter:checked');
                    categoryCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'categories[]';
                        input.value = checkbox.value;
                        form.appendChild(input);
                    });

                    // Price range
                    const minPrice = document.getElementById('minPrice').value;
                    if (minPrice) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'price_min';
                        input.value = minPrice;
                        form.appendChild(input);
                    }

                    const maxPrice = document.getElementById('maxPrice').value;
                    if (maxPrice) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'price_max';
                        input.value = maxPrice;
                        form.appendChild(input);
                    }

                    // Rating (radio)
                    const ratingRadio = document.querySelector('input[name="rating"]:checked');
                    if (ratingRadio) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'rating';
                        input.value = ratingRadio.value;
                        form.appendChild(input);
                    }

                    // Publishers (checkboxes)
                    const publisherCheckboxes = document.querySelectorAll('.publisher-filter:checked');
                    publisherCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'publishers[]';
                        input.value = checkbox.value;
                        form.appendChild(input);
                    });

                    // Languages (checkboxes)
                    const languageCheckboxes = document.querySelectorAll('.language-filter:checked');
                    languageCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'languages[]';
                        input.value = checkbox.value;
                        form.appendChild(input);
                    });

                    // Availability (checkboxes)
                    const availabilityCheckboxes = document.querySelectorAll('.availability-filter:checked');
                    availabilityCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'in_stock[]';
                        input.value = checkbox.value;
                        form.appendChild(input);
                    });

                    // Sort
                    const sortBy = document.getElementById('sortBy').value;
                    if (sortBy) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'sort';
                        input.value = sortBy;
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                };

                // Update price value display for range slider
                const updatePriceValue = () => {
                    const priceRange = document.getElementById('priceRange');
                    const priceValue = document.getElementById('priceValue');
                    priceValue.textContent = `${parseInt(priceRange.value).toLocaleString('vi-VN')}₫`;
                    document.getElementById('maxPrice').value = priceRange.value;
                };

                // Event listeners
                document.querySelectorAll(
                    '.category-filter, .publisher-filter, .language-filter, .availability-filter, input[name="rating"]'
                ).forEach(input => {
                    input.addEventListener('change', submitFilters);
                });

                document.getElementById('sortBy').addEventListener('change', submitFilters);

                document.getElementById('minPrice').addEventListener('blur', debounce(submitFilters, 500));
                document.getElementById('maxPrice').addEventListener('blur', debounce(submitFilters, 500));
                document.getElementById('priceRange').addEventListener('blur', () => {
                    updatePriceValue();
                    debounce(submitFilters, 500)();
                });

                document.getElementById('clearFilters').addEventListener('click', () => {
                    document.querySelectorAll(
                            '.category-filter, .publisher-filter, .language-filter, .availability-filter')
                        .forEach(checkbox => {
                            checkbox.checked = false;
                        });
                    document.querySelectorAll('input[name="rating"]').forEach(radio => {
                        radio.checked = false;
                    });
                    document.getElementById('minPrice').value = '';
                    document.getElementById('maxPrice').value = '';
                    document.getElementById('priceRange').value = 500000;
                    updatePriceValue();
                    submitFilters();
                });

                // Initialize price value display
                updatePriceValue();
            });
        </script>
    @endpush
@endsection
