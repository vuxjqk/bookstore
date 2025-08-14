@extends('layouts.customer')

@section('title', __('Book Details'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-blue-600 font-medium">{{ $book->title }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div class="book-image-gallery">
                    <!-- Main Image -->
                    <div
                        class="mb-4 w-full max-w-md mx-auto rounded-lg shadow-md fade-in bg-blue-500 h-96 flex items-center justify-center overflow-hidden">
                        @if ($image = $book->images->first())
                            <img id="main-image" src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $image->alt_text }}" class="object-contain h-full w-full" />
                        @else
                            <i class="fas fa-book text-white text-9xl"></i>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="flex space-x-2 justify-center">
                        @foreach ($book->images as $index => $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}"
                                class="thumbnail w-16 h-20 object-cover rounded border cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                        @endforeach
                    </div>
                </div>

                <!-- Product Information -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $book->title }}</h1>

                    <!-- Author and Publisher -->
                    <div class="mb-4">
                        <p class="text-lg text-gray-600 mb-1">
                            <i class="fas fa-user-edit mr-2"></i>
                            {{ __('Author') }}: <span
                                class="font-semibold text-blue-600">{{ $book->author->name ?? 'N/A' }}</span>
                        </p>
                        <p class="text-lg text-gray-600">
                            <i class="fas fa-building mr-2"></i>
                            {{ __('Publisher') }}: <span
                                class="font-semibold">{{ $book->publisher->name ?? 'N/A' }}</span>
                        </p>
                    </div>

                    <!-- Rating and Reviews -->
                    <div class="flex items-center mb-4">
                        @if ($book->averageRating > 0)
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $book->averageRating)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @elseif ($i - 0.5 <= $book->averageRating)
                                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                                @else
                                    <i class="far fa-star text-gray-400"></i>
                                @endif
                            @endfor
                            <span
                                class="ml-2 text-lg font-semibold text-gray-800">{{ number_format($book->averageRating, 1) }}</span>
                            <span class="ml-2 text-gray-600">({{ $book->reviews->count() }}
                                {{ __('reviews') }})</span>
                        @else
                            <span class="text-gray-600">{{ __('No reviews yet.') }}</span>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4">
                            <span
                                class="text-3xl font-bold text-red-600">{{ number_format($book->sale_price, 0, ',', '.') }}₫</span>
                            @if ($book->original_price > $book->sale_price)
                                <span
                                    class="text-xl text-gray-500 line-through">{{ number_format($book->original_price, 0, ',', '.') }}₫</span>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">
                                    -{{ round((($book->original_price - $book->sale_price) / $book->original_price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if ($book->stock_quantity > 0)
                            <p class="text-green-600 font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ __('In stock') }} ({{ $book->stock_quantity }} {{ __('books') }})
                            </p>
                        @else
                            <p class="text-red-600 font-semibold">
                                <i class="fas fa-times-circle mr-2"></i>
                                {{ __('Out of stock') }}
                            </p>
                        @endif
                    </div>

                    <!-- Quantity and Actions -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <label class="text-gray-700 font-semibold">{{ __('Quantity') }}:</label>
                            <div class="flex items-center border border-gray-300 rounded">
                                <button onclick="decreaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1"
                                    max="{{ $book->stock_quantity }}"
                                    class="w-16 text-center py-2 border-none focus:ring-0">
                                <button onclick="increaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4 mb-4">
                            <button data-id="{{ $book->id }}"
                                class="add-to-cart-btn flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200 {{ $book->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $book->stock_quantity < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart mr-2"></i>
                                {{ __('Add to cart') }}
                            </button>
                            <button onclick="buyNow()"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200 {{ $book->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $book->stock_quantity < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-bolt mr-2"></i>
                                {{ __('Buy now') }}
                            </button>
                        </div>

                        <!-- Wishlist Button -->
                        @auth
                            @if (Auth::user()->favorites()->where('book_id', $book->id)->exists())
                                <button
                                    class="remove-favorite-btn w-full border border-red-500 hover:border-red-500 text-red-500 hover:text-red-500 py-2 px-4 rounded-lg transition duration-200"
                                    data-id="{{ $book->id }}">
                                    <i class="fas fa-heart mr-2"></i>
                                    {{ __('Added to Favorites') }}
                                </button>
                            @else
                                <button
                                    class="add-favorite-btn w-full border border-gray-300 hover:border-red-500 text-gray-700 hover:text-red-500 py-2 px-4 rounded-lg transition duration-200"
                                    data-id="{{ $book->id }}">
                                    <i class="far fa-heart mr-2"></i>
                                    {{ __('Add to Favorites') }}
                                </button>
                            @endif
                        @endauth

                        @guest
                            <button onclick="showToast('{{ __('Please login to add to favorites.') }}', 'error')"
                                class="w-full border border-gray-300 hover:border-red-500 text-gray-700 hover:text-red-500 py-2 px-4 rounded-lg transition duration-200"
                                <i class="far fa-heart mr-2"></i>
                                {{ __('Add to Favorites') }}
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="bg-white rounded-lg shadow-lg mb-8">
            <div class="border-b">
                <nav class="flex space-x-8 px-6">
                    <button id="description-btn"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        {{ __('Product Description') }}
                    </button>
                    <button id="specifications-btn"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        {{ __('Specifications') }}
                    </button>
                    <button id="reviews-btn"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        {{ __('Reviews') }} ({{ $book->reviews->count() }})
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content hidden">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $book->description }}</p>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">{{ __('ISBN Code') }}:</span>
                                <span class="text-gray-600">{{ $book->isbn ?? __('N/A') }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">{{ __('Language') }}:</span>
                                <span class="text-gray-600">{{ $book->language ?? __('N/A') }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">{{ __('Categories') }}:</span>
                                <div class="flex flex-col">
                                    @forelse($book->categories as $category)
                                        <a href="#" class="text-blue-600 hover:text-blue-800">
                                            {{ $category->name }}
                                        </a>
                                    @empty
                                        <span class="text-gray-600">{{ __('N/A') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @php
                                $technicals = [
                                    __('Page Count') => $book->pages . ' ' . __('page') ?? __('N/A'),
                                    __('Dimensions') => $book->dimensions ?? __('N/A'),
                                    __('Weight (g)') => $book->weight ?? __('N/A'),
                                    __('Publication Year') => $book->publication_year ?? __('N/A'),
                                    __('Cover Type') => $coverTypes[$book->cover_type] ?? __('N/A'),
                                ];
                            @endphp
                            @foreach ($technicals as $term => $details)
                                <div class="flex">
                                    <span class="font-semibold text-gray-700 w-32">{{ $term }}:</span>
                                    <span class="text-gray-600">{{ $details ?? __('N/A') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-content hidden">
                    <!-- Rating Summary -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">{{ __('Customer Reviews') }}</h3>
                            @auth
                                <button onclick="openReviewModal()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus mr-2"></i>{{ __('Write a review') }}
                                </button>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 hover:underline">
                                    {{ __('Please login to submit a review.') }}
                                </a>
                            @endguest
                        </div>
                        <div class="flex items-center mb-4">
                            @if ($book->averageRating > 0)
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $book->averageRating)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @elseif ($i - 0.5 <= $book->averageRating)
                                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-400"></i>
                                    @endif
                                @endfor
                                <span
                                    class="ml-2 text-lg font-semibold text-gray-800">{{ number_format($book->averageRating, 1) }}</span>
                                <span class="ml-2 text-gray-600">({{ $book->reviews->count() }}
                                    {{ __('reviews') }})</span>
                            @else
                                <span class="text-gray-600">{{ __('No reviews yet.') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Review List -->
                    <div class="space-y-6">
                        @foreach ($book->reviews as $review)
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    @if ($review->user->avatar)
                                        <img src="{{ asset('storage/' . $review->user->avatar) }}" alt="Avatar"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
                                            <span
                                                class="text-sm text-gray-500">{{ date('d/m/Y', strtotime($review->updated_at)) }}</span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-400"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Related products') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related_books as $related)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition duration-200">
                        <div
                            class="mb-4 w-full h-48 max-w-md mx-auto rounded-lg shadow-md fade-in bg-blue-500 flex items-center justify-center overflow-hidden">
                            @if ($image = $related->images->first())
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}"
                                    class="object-contain h-full w-full" />
                            @else
                                <i class="fas fa-book text-white text-6xl"></i>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                            {{ Str::limit($related->title, 20, '...') }}
                        </h3>
                        <p class="text-gray-600 mb-2">{{ $related->author->name }}</p>
                        <div class="flex items-center mb-2">
                            @if ($related->averageRating > 0)
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $related->averageRating)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @elseif ($i - 0.5 <= $related->averageRating)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                    @else
                                        <i class="far fa-star text-gray-400 text-sm"></i>
                                    @endif
                                @endfor
                                <span
                                    class="ml-2 text-lg font-semibold text-gray-800 text-sm">{{ number_format($related->averageRating, 1) }}</span>
                                <span class="ml-2 text-gray-600 text-sm">({{ $related->reviews->count() }})</span>
                            @else
                                <span class="text-gray-600 text-sm">{{ __('No reviews yet.') }}</span>
                            @endif
                        </div>
                        <p class="text-lg font-bold text-red-600 mb-3">
                            {{ number_format($related->sale_price, 0, ',', '.') }}₫</p>
                        <a href="{{ route('home.show', $related->slug) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition duration-200">
                            {{ __('View details') }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Submit Your Review') }}</h2>

            <!-- Rating -->
            <div>
                <label class="block text-gray-600 mb-2">{{ __('Rating') }}</label>
                <div class="flex flex-row-reverse justify-center space-x-1">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" value="{{ $i }}"
                            id="rating{{ $i }}" class="hidden peer" required>
                        <label for="rating{{ $i }}"
                            class="text-2xl cursor-pointer peer-checked:text-yellow-400 text-gray-400 hover:text-yellow-300">
                            <i class="fas fa-star"></i>
                        </label>
                    @endfor
                </div>
            </div>

            <div id="reviewError" class="text-center text-red-500 text-sm my-2 hidden"></div>

            <!-- Comment -->
            <div class="mt-4">
                <label for="reviewComment" class="block text-gray-600 mb-2">{{ __('Comment (optional)') }}</label>
                <textarea id="reviewComment"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4" placeholder="{{ __('Enter your comment') }}"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2 mt-6">
                <button onclick="closeReviewModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    {{ __('Cancel') }}
                </button>
                <button onclick="submitReview()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Submit Review') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Image gallery functionality
                window.changeMainImage = (imageSrc, thumbnail) => {
                    const mainImage = document.getElementById('main-image');
                    mainImage.src = imageSrc;
                    mainImage.classList.add('fade-in');

                    document.querySelectorAll('.thumbnail').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    thumbnail.classList.add('active');

                    setTimeout(() => {
                        mainImage.classList.remove('fade-in');
                    }, 300);
                };

                // Quantity controls
                const updateQuantity = (step) => {
                    const input = document.getElementById('quantity');
                    let value = parseInt(input.value);
                    const max = parseInt(input.max);
                    value += step;
                    if (value >= 1 && value <= max) {
                        input.value = value;
                    }
                };

                const increaseQuantity = () => updateQuantity(1);
                const decreaseQuantity = () => updateQuantity(-1);

                // Tab functionality
                const switchTab = (tabName) => {
                    localStorage.setItem('tabName', tabName);

                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    document.querySelectorAll('.tab-button').forEach(button => {
                        button.classList.remove('border-blue-600', 'text-blue-600');
                        button.classList.add('border-transparent', 'text-gray-500');
                    });

                    document.getElementById(`${tabName}-tab`).classList.remove('hidden');
                    const btn = document.getElementById(`${tabName}-btn`);
                    btn.classList.remove('border-transparent', 'text-gray-500');
                    btn.classList.add('border-blue-600', 'text-blue-600');
                };

                // Tab button bindings
                ['description', 'specifications', 'reviews'].forEach(tab => {
                    const btn = document.getElementById(`${tab}-btn`);
                    if (btn) btn.addEventListener('click', () => switchTab(tab));
                });

                // Set default tab
                switchTab(localStorage.getItem('tabName') || 'description');

                // Review functionality
                window.openReviewModal = () => {
                    document.getElementById('reviewModal').classList.remove('hidden');
                    document.getElementById('reviewError').classList.add('hidden');
                }

                window.closeReviewModal = () => {
                    document.getElementById('reviewModal').classList.add('hidden');
                };

                window.submitReview = () => {
                    const bookId = {{ $book->id }};
                    const rating = document.querySelector('input[name="rating"]:checked')?.value;
                    const comment = document.getElementById('reviewComment').value.trim();
                    const errorEl = document.getElementById('reviewError');

                    if (!rating) {
                        errorEl.textContent = 'Please select a rating.';
                        errorEl.classList.remove('hidden');
                        return;
                    }

                    sendRequest('{{ route('reviews.store') }}', 'POST', {
                        book_id: bookId,
                        rating,
                        comment,
                    });
                }
            });

            function buyNow() {
                showToast('Tính năng đang được phát triển!', 'error');
            }
        </script>
    @endpush
@endsection
