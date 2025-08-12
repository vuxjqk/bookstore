@extends('layouts.customer')

@section('title', __('Cart'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-blue-600 font-medium">{{ __('Cart') }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">{{ __('Cart') }}</h1>
            @if (!empty($cart))
                <button id="clear-cart" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors"
                    aria-label="{{ __('Clear cart') }}">
                    <i class="fas fa-trash mr-2"></i>{{ __('Clear cart') }}
                </button>
            @endif
        </div>
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Cart Items -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div id="cartItems" class="space-y-4">
                        @forelse ($cart as $bookId => $item)
                            <div
                                class="cart-item flex flex-col sm:flex-row items-start sm:items-center border-b border-gray-200 py-4 gap-4">
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
                                    <p class="text-gray-600 text-sm">{{ number_format($item['unit_price'], 0, ',', '.') }}₫
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        class="quantity-btn decrease bg-gray-200 text-gray-600 px-2 py-1 rounded hover:bg-gray-300 transition-colors"
                                        data-id="{{ $bookId }}"
                                        aria-label="{{ __('Decrease quantity') }}">-</button>
                                    <input type="number" value="{{ $item['quantity'] }}" min="1"
                                        class="w-16 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 update-num"
                                        data-id="{{ $bookId }}" aria-label="{{ __('Quantity') }}">
                                    <button
                                        class="quantity-btn increase bg-gray-200 text-gray-600 px-2 py-1 rounded hover:bg-gray-300 transition-colors"
                                        data-id="{{ $bookId }}"
                                        aria-label="{{ __('Increase quantity') }}">+</button>
                                </div>
                                <div class="text-lg font-semibold text-gray-800 text-right w-24">
                                    {{ number_format($item['subtotal'], 0, ',', '.') }}₫
                                </div>
                                <button class="remove-btn text-gray-500 hover:text-red-600 transition-colors"
                                    data-id="{{ $bookId }}" aria-label="{{ __('Remove item') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        @empty
                            <div id="emptyCartMessage" class="text-center text-gray-600 py-8">
                                <p>{{ __('Your cart is empty.') }} <a href="{{ url('/') }}"
                                        class="text-blue-600 hover:underline">{{ __('Continue shopping') }}</a></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Order Summary') }}</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('Total Items') }}</span>
                            <span>{{ collect($cart)->sum('quantity') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('Subtotal') }}</span>
                            <span>{{ number_format(collect($cart)->sum('subtotal'), 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('Shipping Fee') }}</span>
                            <span>{{ number_format(30000, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between text-gray-800 font-semibold">
                                <span>{{ __('Total') }}</span>
                                <span>{{ number_format(collect($cart)->sum('subtotal') + 30000, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                        <a href="{{ route('cart.payment') }}"
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 transition-colors flex items-center justify-center {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($cart) ? 'disabled' : '' }}>
                            <i class="fas fa-credit-card mr-2"></i>{{ __('Checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const cartItems = document.getElementById('cartItems');
                const clearCart = document.getElementById('clear-cart');

                cartItems.addEventListener('click', async (e) => {
                    const btn = e.target.closest('.quantity-btn, .remove-btn');
                    if (!btn) return;

                    const bookId = parseInt(btn.dataset.id);
                    if (!bookId) return;

                    if (btn.classList.contains('remove-btn')) {
                        const result = await openConfirmModal(
                            '{{ __('Are you sure you want to delete this product from cart?') }}');

                        if (result) {
                            sendRequest('{{ route('cart.remove') }}', 'POST', {
                                book_id: bookId
                            });
                        }
                        return;
                    }

                    const input = document.querySelector(`input[data-id="${bookId}"]`);
                    let quantity = parseInt(input.value) || 1;

                    if (btn.classList.contains('increase')) {
                        quantity += 1;
                    } else if (btn.classList.contains('decrease')) {
                        quantity = Math.max(quantity - 1, 1);
                    }

                    input.value = quantity;
                    sendRequest('{{ route('cart.update') }}', 'POST', {
                        book_id: bookId,
                        quantity
                    });
                });

                cartItems.addEventListener('change', e => {
                    if (e.target.classList.contains('update-num')) {
                        const bookId = parseInt(e.target.dataset.id);
                        const quantity = Math.max(parseInt(e.target.value) || 1, 1);
                        e.target.value = quantity;
                        sendRequest('{{ route('cart.update') }}', 'POST', {
                            book_id: bookId,
                            quantity
                        });
                    }
                });

                if (clearCart) {
                    clearCart.addEventListener('click', async () => {
                        const result = await openConfirmModal(
                            '{{ __('Are you sure you want to clear your cart?') }}');

                        if (result) {
                            sendRequest('{{ route('cart.clear') }}', 'POST', {});
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
