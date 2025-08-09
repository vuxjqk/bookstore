@extends('layouts.customer')

@section('title', __('Payment'))

@section('content')
    <div class="py-6">

        <!-- Breadcrumb -->
        <nav class="bg-gray-100 py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-blue-600">Trang chủ</a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-600">Giỏ hàng</a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <span class="text-blue-600">Thanh toán</span>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Thanh Toán</h1>
            <form method="POST" action="{{ route('orders.store') }}" class="flex flex-col lg:flex-row gap-8">
                @csrf
                <!-- Billing Information -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Thông tin thanh toán</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="billingName" class="block text-gray-600 mb-2">Họ và Tên</label>
                                <input type="text" id="billingName" name="name"
                                    class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                    placeholder="Nhập họ và tên">
                                <p id="billingNameError" class="error-message mt-1">Tên phải có ít nhất 2 ký tự</p>
                            </div>
                            <div>
                                <label for="billingAddress" class="block text-gray-600 mb-2">Địa chỉ giao hàng</label>
                                <input type="text" id="billingAddress" name="shipping_address"
                                    class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                    placeholder="Nhập địa chỉ">
                                <p id="billingAddressError" class="error-message mt-1">Địa chỉ không được để trống</p>
                            </div>
                            <div>
                                <label for="billingPhone" class="block text-gray-600 mb-2">Số điện thoại</label>
                                <input type="text" id="billingPhone" name="phone"
                                    class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                    placeholder="Nhập số điện thoại">
                                <p id="billingPhoneError" class="error-message mt-1">Số điện thoại không hợp lệ</p>
                            </div>
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-gray-600 mb-2">Phương thức thanh toán</label>
                                <div class="flex flex-col space-y-2">
                                    <div class="payment-method border border-gray-300 rounded p-4 cursor-pointer"
                                        data-method="cod">
                                        <input type="radio" name="payment_method" value="cash" class="mr-2" checked>
                                        <span class="text-gray-800">Thanh toán khi nhận hàng (COD)</span>
                                    </div>
                                    <div class="payment-method border border-gray-300 rounded p-4 cursor-pointer"
                                        data-method="card">
                                        <input type="radio" name="payment_method" value="card" class="mr-2">
                                        <span class="text-gray-800">Thẻ tín dụng/Thẻ ghi nợ</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Details (hidden by default) -->
                            <div id="cardDetails" class="hidden space-y-4">
                                <div>
                                    <label for="cardNumber" class="block text-gray-600 mb-2">Số thẻ</label>
                                    <input type="text" id="cardNumber"
                                        class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                        placeholder="XXXX XXXX XXXX XXXX">
                                    <p id="cardNumberError" class="error-message mt-1">Số thẻ không hợp lệ</p>
                                </div>
                                <div class="flex space-x-4">
                                    <div class="w-1/2">
                                        <label for="cardExpiry" class="block text-gray-600 mb-2">Ngày hết hạn</label>
                                        <input type="text" id="cardExpiry"
                                            class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                            placeholder="MM/YY">
                                        <p id="cardExpiryError" class="error-message mt-1">Ngày hết hạn không hợp lệ</p>
                                    </div>
                                    <div class="w-1/2">
                                        <label for="cardCVC" class="block text-gray-600 mb-2">CVC</label>
                                        <input type="text" id="cardCVC"
                                            class="input-field w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                                            placeholder="XXX">
                                        <p id="cardCVCError" class="error-message mt-1">CVC không hợp lệ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>
                        <div id="orderItems" class="space-y-4"></div>
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính</span>
                                <span id="subtotal">0₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển</span>
                                <span id="shippingFee">30.000₫</span>
                            </div>
                            <div class="flex justify-between text-gray-800 font-semibold">
                                <span>Tổng cộng</span>
                                <span id="total">0₫</span>
                            </div>
                        </div>
                        <button id="confirmPaymentButton"
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 transition duration-300 mt-4">
                            <i class="fas fa-credit-card mr-2"></i>Xác nhận thanh toán
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Load cart and user data
            function loadCart() {
                const rawCart = @json($cart);

                const initialCart = Object.values(rawCart).map(item => ({
                    id: item.id,
                    title: item.name, // đổi từ 'name' sang 'title'
                    author: item.author,
                    price: parseInt(item.unit_price),
                    quantity: item.quantity,
                    image: item.image || "fa-book-open" // nếu thiếu ảnh thì dùng mặc định
                }));
                return initialCart;
            }

            function loadUser() {
                return JSON.parse(localStorage.getItem('user') || '{}');
            }

            function saveOrders(orders) {
                localStorage.setItem('orders', JSON.stringify(orders));
            }

            // Validation functions
            function validatePhone(phone) {
                return /^[0-9]{10}$/.test(phone);
            }

            function validateCardNumber(cardNumber) {
                return /^\d{16}$/.test(cardNumber.replace(/\s/g, ''));
            }

            function validateExpiry(expiry) {
                return /^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(expiry);
            }

            function validateCVC(cvc) {
                return /^\d{3}$/.test(cvc);
            }

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.style.display = 'block';
                return false;
            }

            function clearErrors() {
                document.querySelectorAll('.error-message').forEach(error => {
                    error.style.display = 'none';
                });
            }

            // Render order summary
            function renderOrderSummary() {
                const cart = loadCart();
                const orderItems = document.getElementById('orderItems');
                const subtotalElement = document.getElementById('subtotal');
                const totalElement = document.getElementById('total');
                const cartCount = document.getElementById('cartCount');

                orderItems.innerHTML = '';
                if (cart.length === 0) {
                    orderItems.innerHTML =
                        '<p class="text-gray-600">Giỏ hàng trống. <a href="books.php" class="text-blue-600 hover:underline">Tiếp tục mua sắm</a></p>';
                    subtotalElement.textContent = '0₫';
                    totalElement.textContent = '0₫';
                    cartCount.textContent = '0';
                } else {
                    cart.forEach(item => {
                        const itemElement = document.createElement('div');
                        itemElement.className = 'flex justify-between text-gray-600';
                        itemElement.innerHTML = `
                        <span>${item.title} (x${item.quantity})</span>
                        <span>${(item.price * item.quantity).toLocaleString()}₫</span>
                    `;
                        orderItems.appendChild(itemElement);
                    });
                    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                    const shipping = 30000;
                    const total = subtotal + shipping;
                    subtotalElement.textContent = subtotal.toLocaleString() + '₫';
                    totalElement.textContent = total.toLocaleString() + '₫';
                    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
                }
            }

            // Toggle payment method
            document.querySelectorAll('.payment-method').forEach(method => {
                method.addEventListener('click', () => {
                    document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                    method.classList.add('selected');
                    method.querySelector('input').checked = true;
                    document.getElementById('cardDetails').classList.toggle('hidden', method.dataset.method !==
                        'card');
                });
            });

            // Pre-fill billing information
            function prefillBillingInfo() {
                const user = loadUser();
                if (user.name) document.getElementById('billingName').value = user.name;
                if (user.address) document.getElementById('billingAddress').value = user.address;
                if (user.phone) document.getElementById('billingPhone').value = user.phone;
            }

            // Confirm payment
            document.getElementById('confirmPaymentButton').addEventListener('click', () => {
                clearErrors();
                const cart = loadCart();
                if (cart.length === 0) {
                    alert('Giỏ hàng trống! Vui lòng thêm sản phẩm trước khi thanh toán.');
                    return;
                }

                const name = document.getElementById('billingName').value.trim();
                const address = document.getElementById('billingAddress').value.trim();
                const phone = document.getElementById('billingPhone').value.trim();
                const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
                let isValid = true;

                if (name.length < 2) {
                    isValid = showError('billingNameError', 'Tên phải có ít nhất 2 ký tự');
                }
                if (!address) {
                    isValid = showError('billingAddressError', 'Địa chỉ không được để trống');
                }
                if (!validatePhone(phone)) {
                    isValid = showError('billingPhoneError', 'Số điện thoại không hợp lệ');
                }

                if (paymentMethod === 'card') {
                    const cardNumber = document.getElementById('cardNumber').value.trim();
                    const cardExpiry = document.getElementById('cardExpiry').value.trim();
                    const cardCVC = document.getElementById('cardCVC').value.trim();

                    if (!validateCardNumber(cardNumber)) {
                        isValid = showError('cardNumberError', 'Số thẻ không hợp lệ');
                    }
                    if (!validateExpiry(cardExpiry)) {
                        isValid = showError('cardExpiryError', 'Ngày hết hạn không hợp lệ');
                    }
                    if (!validateCVC(cardCVC)) {
                        isValid = showError('cardCVCError', 'CVC không hợp lệ');
                    }
                }

                if (isValid) {
                    const user = loadUser();
                    const orders = JSON.parse(localStorage.getItem('orders') || '[]');
                    const orderId = `ORD${(orders.length + 1).toString().padStart(3, '0')}`;
                    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                    const newOrder = {
                        id: orderId,
                        userEmail: user.email || 'guest@example.com',
                        date: new Date().toLocaleDateString('vi-VN'),
                        total: subtotal + 30000,
                        status: 'Đang xử lý',
                        items: cart.map(item => ({
                            title: item.title,
                            quantity: item.quantity,
                            price: item.price
                        }))
                    };
                    orders.push(newOrder);
                    saveOrders(orders);
                    localStorage.removeItem('cart'); // Clear cart
                    alert('Thanh toán thành công! Mã đơn hàng: ' + orderId);
                    // window.location.href = 'profile.html'; // Redirect to profile/orders
                }
            });

            // Initialize page
            prefillBillingInfo();
            renderOrderSummary();
        </script>
    @endpush
@endsection
