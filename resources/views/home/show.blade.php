@extends('layouts.customer')

@section('title', __('Book Details'))

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-3">
            <nav class="text-sm text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-blue-600">{{ __('Home') }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800">{{ $book->title }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div class="book-image-gallery">
                    <!-- Main Image -->
                    <div class="mb-4">
                        @if ($book->images->first())
                            <img id="main-image" src="{{ asset('storage/' . $book->images->first()->image_path) }}"
                                alt="{{ $book['title'] }}" class="w-full max-w-md mx-auto rounded-lg shadow-md fade-in">
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
                            Tác giả: <span class="font-semibold text-blue-600">{{ $book->author->name ?? 'N/A' }}</span>
                        </p>
                        <p class="text-lg text-gray-600">
                            <i class="fas fa-building mr-2"></i>
                            Nhà xuất bản: <span class="font-semibold">{{ $book->publisher->name ?? 'N/A' }}</span>
                        </p>
                    </div>

                    <!-- Rating and Reviews -->
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star rating-stars {{ $i <= floor($book['rating']) ? '' : ($i <= $book['rating'] ? 'fas fa-star-half-alt' : 'far fa-star text-gray-300') }}"></i>
                            @endfor
                        </div>
                        <span class="ml-2 text-lg font-semibold text-gray-700">{{ $book['rating'] }}</span>
                        <span class="ml-2 text-gray-500">({{ number_format($book['review_count']) }} đánh giá)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4">
                            <span class="text-3xl font-bold text-red-600">{{ number_format($book['price']) }}₫</span>
                            @if ($book['original_price'] > $book['price'])
                                <span
                                    class="text-xl text-gray-500 line-through">{{ number_format($book['original_price']) }}₫</span>
                                <span
                                    class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">-{{ $book['discount'] }}%</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if ($book['in_stock'])
                            <p class="text-green-600 font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>
                                Còn hàng ({{ $book['stock_quantity'] }} cuốn)
                            </p>
                        @else
                            <p class="text-red-600 font-semibold">
                                <i class="fas fa-times-circle mr-2"></i>
                                Hết hàng
                            </p>
                        @endif
                    </div>

                    <!-- Quantity and Actions -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <label class="text-gray-700 font-semibold">Số lượng:</label>
                            <div class="flex items-center border border-gray-300 rounded">
                                <button onclick="decreaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1"
                                    max="{{ $book['stock_quantity'] }}"
                                    class="w-16 text-center py-2 border-none focus:ring-0">
                                <button onclick="increaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4 mb-4">
                            <button data-id="{{ $book->id }}"
                                class="add-to-cart-btn flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Thêm vào giỏ hàng
                            </button>
                            <button onclick="buyNow()"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200">
                                <i class="fas fa-bolt mr-2"></i>
                                Mua ngay
                            </button>
                        </div>

                        <!-- Wishlist Button -->
                        <button onclick="toggleWishlist()" id="wishlist-btn"
                            class="w-full border border-gray-300 hover:border-red-500 text-gray-700 hover:text-red-500 py-2 px-4 rounded-lg transition duration-200">
                            <i class="far fa-heart mr-2"></i>
                            Thêm vào yêu thích
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="bg-white rounded-lg shadow-lg mb-8">
            <div class="border-b">
                <nav class="flex space-x-8 px-6">
                    <button onclick="switchTab('description')"
                        class="tab-button py-4 px-2 border-b-2 border-blue-600 text-blue-600 font-semibold">
                        Mô tả sản phẩm
                    </button>
                    <button onclick="switchTab('specifications')"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Thông số kỹ thuật
                    </button>
                    <button onclick="switchTab('reviews')"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Đánh giá ({{ count($reviews) }})
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 text-lg leading-relaxed mb-4">{{ $book['description'] }}</p>
                        <p class="text-gray-700 leading-relaxed">{{ $book['detailed_description'] }}</p>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">ISBN:</span>
                                <span class="text-gray-600">{{ $book['isbn'] }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">Số trang:</span>
                                <span class="text-gray-600">{{ $book['pages'] }} trang</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">Ngôn ngữ:</span>
                                <span class="text-gray-600">{{ $book['language'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">Năm xuất bản:</span>
                                <span class="text-gray-600">{{ $book['publication_year'] }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">Thể loại:</span>
                                <span class="text-gray-600">{{ $book['category'] }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-32">Tác giả:</span>
                                <span class="text-gray-600">{{ $book['author'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-content hidden">
                    <!-- Rating Summary -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Đánh giá từ khách hàng</h3>
                            <button onclick="showReviewForm()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-plus mr-2"></i>Viết đánh giá
                            </button>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-4xl font-bold text-gray-800">{{ $book['rating'] }}</span>
                            <div>
                                <div class="flex items-center mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star rating-stars {{ $i <= floor($book['rating']) ? '' : ($i <= $book['rating'] ? 'fas fa-star-half-alt' : 'far fa-star text-gray-300') }}"></i>
                                    @endfor
                                </div>
                                <p class="text-gray-600">{{ number_format($book['review_count']) }} đánh giá</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review List -->
                    <div class="space-y-6">
                        @foreach ($reviews as $review)
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $review['avatar'] }}" alt="{{ $review['user'] }}"
                                        class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-800">{{ $review['user'] }}</h4>
                                            <span
                                                class="text-sm text-gray-500">{{ date('d/m/Y', strtotime($review['date'])) }}</span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review['rating'] ? 'rating-stars' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-gray-700">{{ $review['comment'] }}</p>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related_books as $related)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition duration-200">
                        <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}"
                            class="w-full h-48 object-cover rounded mb-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $related['title'] }}</h3>
                        <p class="text-gray-600 mb-2">{{ $related['author'] }}</p>
                        <div class="flex items-center mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star {{ $i <= floor($related['rating']) ? 'rating-stars' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                            <span class="text-sm text-gray-600 ml-1">({{ $related['rating'] }})</span>
                        </div>
                        <p class="text-lg font-bold text-red-600 mb-3">{{ number_format($related['price']) }}₫</p>
                        <button onclick="viewProduct({{ $related['id'] }})"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition duration-200">
                            Xem chi tiết
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            // Image gallery functionality
            function changeMainImage(imageSrc, thumbnail) {
                document.getElementById('main-image').src = imageSrc;
                document.getElementById('main-image').classList.add('fade-in');

                // Remove active class from all thumbnails
                document.querySelectorAll('.thumbnail').forEach(thumb => {
                    thumb.classList.remove('active');
                });

                // Add active class to clicked thumbnail
                thumbnail.classList.add('active');

                // Remove fade-in class after animation
                setTimeout(() => {
                    document.getElementById('main-image').classList.remove('fade-in');
                }, 300);
            }

            // Quantity controls
            function increaseQuantity() {
                const quantityInput = document.getElementById('quantity');
                const currentValue = parseInt(quantityInput.value);
                const maxValue = parseInt(quantityInput.max);

                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            }

            function decreaseQuantity() {
                const quantityInput = document.getElementById('quantity');
                const currentValue = parseInt(quantityInput.value);

                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            }

            // Tab functionality
            function switchTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active styles from all tab buttons
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-blue-600', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected tab content
                document.getElementById(tabName + '-tab').classList.remove('hidden');

                // Add active styles to clicked tab button
                event.target.classList.remove('border-transparent', 'text-gray-500');
                event.target.classList.add('border-blue-600', 'text-blue-600');
            }

            // Cart functionality
            function addToCart() {
                const quantity = document.getElementById('quantity').value;

                // Simulate API call
                showNotification('Đã thêm ' + quantity + ' sản phẩm vào giỏ hàng!', 'success');

                // Update cart count in header
                const cartCount = document.querySelector('.fa-shopping-cart').nextElementSibling.nextElementSibling;
                const currentCount = parseInt(cartCount.textContent);
                cartCount.textContent = currentCount + parseInt(quantity);

                //???
            }

            function buyNow() {
                const quantity = document.getElementById('quantity').value;
                showNotification('Chuyển đến trang thanh toán với ' + quantity + ' sản phẩm...', 'info');

                // Simulate redirect to checkout
                setTimeout(() => {
                    showNotification('Tính năng đang được phát triển!', 'warning');
                }, 1500);
            }

            // Wishlist functionality
            function toggleWishlist() {
                const wishlistBtn = document.getElementById('wishlist-btn');
                const icon = wishlistBtn.querySelector('i');

                if (icon.classList.contains('far')) {
                    // Add to wishlist
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    wishlistBtn.classList.add('text-red-500', 'border-red-500');
                    wishlistBtn.classList.remove('text-gray-700', 'border-gray-300');
                    wishlistBtn.innerHTML = '<i class="fas fa-heart mr-2"></i>Đã thêm vào yêu thích';
                    showNotification('Đã thêm vào danh sách yêu thích!', 'success');
                } else {
                    // Remove from wishlist
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    wishlistBtn.classList.remove('text-red-500', 'border-red-500');
                    wishlistBtn.classList.add('text-gray-700', 'border-gray-300');
                    wishlistBtn.innerHTML = '<i class="far fa-heart mr-2"></i>Thêm vào yêu thích';
                    showNotification('Đã xóa khỏi danh sách yêu thích!', 'info');
                }
            }

            // Review functionality
            function showReviewForm() {
                showNotification('Tính năng viết đánh giá đang được phát triển!', 'info');
            }

            // Related products
            function viewProduct(productId) {
                showNotification('Chuyển đến sản phẩm #' + productId + '...', 'info');
                // Simulate navigation
                setTimeout(() => {
                    showNotification('Tính năng đang được phát triển!', 'warning');
                }, 1000);
            }

            // Notification system
            function showNotification(message, type = 'info') {
                // Remove existing notification
                const existingNotification = document.getElementById('notification');
                if (existingNotification) {
                    existingNotification.remove();
                }

                // Create notification element
                const notification = document.createElement('div');
                notification.id = 'notification';
                notification.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-semibold max-w-sm transform transition-all duration-300 translate-x-full`;

                // Set color based on type
                switch (type) {
                    case 'success':
                        notification.classList.add('bg-green-500');
                        break;
                    case 'error':
                        notification.classList.add('bg-red-500');
                        break;
                    case 'warning':
                        notification.classList.add('bg-yellow-500');
                        break;
                    default:
                        notification.classList.add('bg-blue-500');
                }

                notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

                // Add to page
                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (notification.parentElement) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            }

            // Mobile menu toggle (if needed)
            function toggleMobileMenu() {
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            }

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                // Set first tab as active by default
                switchTab('description');

                // Add smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                // Add quantity input validation
                const quantityInput = document.getElementById('quantity');
                quantityInput.addEventListener('change', function() {
                    const value = parseInt(this.value);
                    const min = parseInt(this.min);
                    const max = parseInt(this.max);

                    if (value < min) {
                        this.value = min;
                    } else if (value > max) {
                        this.value = max;
                        showNotification('Số lượng tối đa là ' + max + ' sản phẩm!', 'warning');
                    }
                });

                // Add search functionality
                const searchInput = document.querySelector('input[placeholder="Tìm kiếm sách..."]');
                if (searchInput) {
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            const searchTerm = this.value.trim();
                            if (searchTerm) {
                                showNotification('Tìm kiếm: "' + searchTerm + '"...', 'info');
                                setTimeout(() => {
                                    showNotification('Tính năng tìm kiếm đang được phát triển!',
                                        'warning');
                                }, 1000);
                            }
                        }
                    });
                }
            });

            // Add to cart animation
            function animateAddToCart() {
                const addToCartBtn = document.querySelector('button[onclick="addToCart()"]');
                addToCartBtn.classList.add('scale-95');
                setTimeout(() => {
                    addToCartBtn.classList.remove('scale-95');
                }, 150);
            }

            // Update addToCart function to include animation
            // const originalAddToCart = addToCart;
            // addToCart = function() {
            //     animateAddToCart();
            //     originalAddToCart();
            // };
        </script>
    @endpush
@endsection
