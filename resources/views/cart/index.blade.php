@extends('layouts.customer')

@section('title', __('Cart'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-100 py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-blue-600">Trang chủ</a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <span class="text-blue-600">Giỏ Hàng</span>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Giỏ Hàng</h1>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div id="cartItems" class="space-y-6">
                            <!-- Cart items will be dynamically added here -->
                            @foreach ($cart as $bookId => $item)
                                <div class="cart-item flex items-center border-b border-gray-200 py-4">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center rounded">
                                        @if (!empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}"
                                                alt="{{ __('Book Image') }}">
                                        @else
                                            <i class="fas ${item.image} text-2xl text-white"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $item['author'] }}</p>
                                        <p class="text-gray-600 text-sm">
                                            {{ number_format($item['unit_price'], 0, ',', '.') }}₫</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="quantity-btn decrease bg-gray-200 text-gray-600 px-2 py-1 rounded"
                                            data-id="{{ $bookId }}">-</button>
                                        <input type="number" value="{{ $item['quantity'] }}" min="1"
                                            class="w-12 text-center border border-gray-300 rounded update-num"
                                            data-id="{{ $bookId }}">
                                        <button class="quantity-btn increase bg-gray-200 text-gray-600 px-2 py-1 rounded"
                                            data-id="{{ $bookId }}">+</button>
                                    </div>
                                    <div class="ml-4 text-lg font-semibold text-gray-800">
                                        {{ number_format($item['unit_price'] * $item['quantity'], 0, ',', '.') }}₫</div>
                                    <button class="remove-btn text-gray-500 ml-4" data-id="{{ $bookId }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div id="emptyCartMessage" class="text-center text-gray-600 py-8 hidden">
                            <p>Giỏ hàng của bạn đang trống. <a href="books.php" class="text-blue-600 hover:underline">Tiếp
                                    tục mua sắm</a></p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Tổng sản phẩm</span>
                                <span id="totalItems">0</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính</span>
                                <span id="subtotal">0₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển</span>
                                <span>30.000₫</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-gray-800 font-semibold">
                                    <span>Tổng cộng</span>
                                    <span id="total">0₫</span>
                                </div>
                            </div>
                            <button id="checkoutButton"
                                class="w-full bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 transition duration-300 mt-4">
                                <i class="fas fa-credit-card mr-2"></i>Thanh Toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                updateSummary()

                function updateSummary() {
                    const totalItems = {{ collect(session('cart', []))->sum('quantity') }};
                    const subtotal = {{ collect(session('cart', []))->sum('amount') }};
                    const total = subtotal + 30000;

                    document.getElementById('totalItems').textContent = totalItems;
                    document.getElementById('subtotal').textContent = subtotal.toLocaleString() + '₫';
                    document.getElementById('total').textContent = total.toLocaleString() + '₫';
                }

                function update(bookId, quantity) {
                    const url = '{{ route('cart.update') }}';
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                book_id: bookId,
                                quantity: quantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message);
                            }
                            location.reload();
                        })
                        .catch(error => {
                            alert('{{ __('Unknown error while updating.') }}');
                            console.error(error);
                        });
                }

                function remove(bookId) {
                    const url = '{{ route('cart.remove') }}';
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                book_id: bookId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message);
                            }
                            location.reload();
                        })
                        .catch(error => {
                            alert('{{ __('Unknown error while deleting.') }}');
                            console.error(error);
                        });
                }

                document.querySelectorAll('.update-num').forEach(input => {
                    input.addEventListener('change', () => {
                        // Implement update action
                        const bookId = input.dataset.id;
                        const quantity = input.value;
                        update(bookId, quantity);
                    });
                });

                document.addEventListener('click', (e) => {
                    if (e.target.classList.contains('increase') || e.target.classList.contains('decrease')) {
                        const id = e.target.getAttribute('data-id');
                        if (!id) return;

                        const input = document.querySelector(`input[data-id="${id}"]`);
                        if (!input) return;

                        let quantity = parseInt(input.value) || 0;

                        if (e.target.classList.contains('increase')) {
                            quantity += 1;
                        } else if (e.target.classList.contains('decrease')) {
                            quantity = Math.max(quantity - 1, 0);
                        }

                        input.value = quantity;

                        const bookId = parseInt(id);
                        update(bookId, quantity);
                    }

                    const btn = e.target.closest('.remove-btn');
                    if (btn) {
                        if (confirm('{{ __('Are you sure you want to delete this product in cart?') }}')) {
                            const id = btn.getAttribute('data-id');
                            if (!id) return;
                            const bookId = parseInt(id);
                            remove(bookId);
                        }
                    }
                });

                document.getElementById('checkoutButton').addEventListener('click', () => {
                    if ({{ collect(session('cart', []))->sum('quantity') }} === 0) {
                        alert('Giỏ hàng của bạn đang trống!');
                    } else {
                        alert('Chuyển đến trang thanh toán...');
                        // In production, redirect to checkout page
                        window.location.href = '{{ route('cart.payment') }}';
                    }
                });
            });
        </script>
    @endpush
@endsection
