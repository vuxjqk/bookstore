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
            <form id="paymentForm" method="POST" action="{{ route('orders.store') }}"
                class="flex flex-col lg:flex-row gap-8">
                @csrf
                <!-- Billing Information -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('Billing Information') }}</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="billingName" class="block text-gray-600 mb-2">{{ __('Full Name') }}</label>
                                <input type="text" id="billingName" name="name"
                                    value="{{ old('name', Auth::user()?->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter full name') }}" {{ Auth::check() ? 'readonly' : '' }}>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="billingPhone" class="block text-gray-600 mb-2">{{ __('Phone Number') }}</label>
                                <input type="text" id="billingPhone" name="phone"
                                    value="{{ old('phone', Auth::user()?->phone) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter phone number') }}">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="billingAddress"
                                    class="block text-gray-600 mb-2">{{ __('Shipping Address') }}</label>
                                <input type="text" id="billingAddress" name="shipping_address"
                                    value="{{ old('shipping_address', Auth::user()?->address) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter shipping address') }}">
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center text-gray-600">
                                    <input type="checkbox" name="save_phone" value="1" class="mr-2">
                                    {{ __('Save phone number') }}
                                </label>
                                <label class="flex items-center text-gray-600">
                                    <input type="checkbox" name="save_address" value="1" class="mr-2">
                                    {{ __('Save address') }}
                                </label>
                            </div>
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-gray-600 mb-2">{{ __('Payment Method') }}</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="cod" class="mr-2" checked>
                                        {{ __('Cash on Delivery (COD)') }}
                                    </label>
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="bank_transfer" class="mr-2">
                                        {{ __('Bank Transfer') }}
                                    </label>
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="momo" class="mr-2">
                                        {{ __('MoMo') }}
                                    </label>
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="vnpay" class="mr-2">
                                        {{ __('VNPay') }}
                                    </label>
                                    <label class="border border-gray-300 rounded p-4 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="credit_card" class="mr-2">
                                        {{ __('Credit/Debit Card') }}
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
                                    <span>{{ number_format(collect($cart)->sum('subtotal'), 0, ',', '.') }}₫</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('Shipping Fee') }}</span>
                                    <span>{{ number_format(30000, 0, ',', '.') }}₫</span>
                                </div>
                                <div class="flex justify-between text-gray-800 font-semibold">
                                    <span>{{ __('Total') }}</span>
                                    <span>{{ number_format(collect($cart)->sum('subtotal') + 30000, 0, ',', '.') }}₫</span>
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
