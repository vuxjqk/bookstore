@extends('layouts.customer')

@section('title', __('Payment'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-100 py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">{{ __('Home') }}</a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition-colors">{{ __('Cart') }}</a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <span class="text-blue-600 font-medium">{{ __('Payment') }}</span>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('Payment') }}</h1>
            <form id="paymentForm" method="POST" action="{{ route('orders.saveOrder') }}"
                class="flex flex-col lg:flex-row gap-8">
                @csrf
                <!-- Billing Information -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('Billing Information') }}</h2>
                        <div class="space-y-4">
                            <!-- Full Name -->
                            <div>
                                <label for="customer_name" class="block text-gray-600 mb-2">{{ __('Full Name') }}</label>
                                <input type="text" id="customer_name" name="customer_name"
                                    value="{{ old('customer_name', Auth::user()?->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter full name') }}" {{ Auth::check() ? 'readonly' : '' }}>
                                @error('customer_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Phone Number -->
                            <div>
                                <label for="customer_phone"
                                    class="block text-gray-600 mb-2">{{ __('Phone Number') }}</label>
                                <input type="text" id="customer_phone" name="customer_phone"
                                    value="{{ old('customer_phone', Auth::user()?->phone) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter phone number') }}">
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Shipping Address -->
                            <div>
                                <label for="shipping_address"
                                    class="block text-gray-600 mb-2">{{ __('Shipping Address') }}</label>
                                <input type="text" id="shipping_address" name="shipping_address"
                                    value="{{ old('shipping_address', Auth::user()?->address) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter shipping address') }}">
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Save Address -->
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center text-gray-600">
                                    <input type="checkbox" name="save_address" value="1" class="mr-2"
                                        {{ old('save_address') ? 'checked' : '' }}>
                                    {{ __('Save address') }}
                                </label>
                            </div>
                            <!-- Promotion Code -->
                            <div>
                                <label for="code" class="block text-gray-600 mb-2">{{ __('Promotion Code') }}</label>
                                <input type="text" id="code" name="code" value="{{ old('code') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter promotion code') }}">
                                @error('code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-gray-600 mb-2">{{ __('Payment Method') }}</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="cod" class="mr-2"
                                            {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                        {{ __('Cash on Delivery (COD)') }}
                                    </label>
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="vnpay" class="mr-2"
                                            {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                        {{ __('VNPay') }}
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Order Summary') }}</h2>
                        <div class="space-y-4">
                            @forelse ($cart as $bookId => $item)
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ $item['title'] }} (x{{ $item['quantity'] }})</span>
                                    <span>{{ number_format($item['subtotal'], 0, ',', '.') }}₫</span>
                                </div>
                            @empty
                                <p class="text-gray-600">{{ __('Your cart is empty.') }} <a href="{{ url('/') }}"
                                        class="text-blue-600 hover:underline">{{ __('Continue shopping') }}</a></p>
                            @endforelse
                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span>{{ number_format($subtotal = collect($cart)->sum('subtotal'), 0, ',', '.') }}₫</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('Shipping Fee') }}</span>
                                    <span>{{ number_format($shippingFee = $subtotal > 300000 ? 0 : 30000, 0, ',', '.') }}₫</span>
                                </div>
                                @php
                                    $discount_amount = 0;

                                    if (!empty(old('code'))) {
                                        $promotion = \App\Models\Promotion::where('code', old('code'))
                                            ->where('is_active', true)
                                            ->whereDate('start_date', '<=', now())
                                            ->whereDate('end_date', '>=', now())
                                            ->first();

                                        if (
                                            $promotion &&
                                            (!$promotion->min_order_amount || $subtotal >= $promotion->min_order_amount)
                                        ) {
                                            $discount_amount = $subtotal * ($promotion->discount_percentage / 100);

                                            if (
                                                $promotion->max_discount_amount &&
                                                $discount_amount > $promotion->max_discount_amount
                                            ) {
                                                $discount_amount = $promotion->max_discount_amount;
                                            }
                                        }
                                    }
                                @endphp

                                @if (!empty(old('code')) && isset($promotion))
                                    <div class="flex justify-between text-gray-600">
                                        <span>{{ __('Discount') }} ({{ $promotion->code ?? 'N/A' }})</span>
                                        <span>-{{ number_format($discount_amount, 0, ',', '.') }}₫</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-gray-800 font-semibold">
                                    <span>{{ __('Total') }}</span>
                                    <span>{{ number_format($subtotal + $shippingFee - $discount_amount, 0, ',', '.') }}₫</span>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 transition-colors flex items-center justify-center {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ empty($cart) ? 'disabled' : '' }}>
                                <i class="fas fa-credit-card mr-2"></i>{{ __('Confirm Payment') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('paymentForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const result = await openConfirmModal(
                        '{{ __('Are you sure you want to confirm this order?') }}');
                    if (result) {
                        this.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
