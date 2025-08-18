@extends('layouts.admin')

@section('title', __('Order Details'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Order Management', 'url' => route('orders.index')],
            ['label' => 'Order Details'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                        {{ __('Order #') }}{{ $order->id }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('View detailed order information.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('orders.index')" />
                    <x-export-pdf-button :route="route('orders.export', $order)" />
                    <x-status-update-button :route="route('orders.update', $order)" />
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            {{ __('Order Information') }}
                        </h3>

                        <!-- Order ID -->
                        <div>
                            <x-form-label value="Order ID" icon="fas fa-hashtag" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $order->id }}
                            </div>
                        </div>

                        <!-- Customer Name and Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-label value="Customer Name" icon="fas fa-user" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $order->customer_name }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Phone" icon="fas fa-phone" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $order->customer_phone }}
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div>
                            <x-form-label value="Shipping Address" icon="fas fa-map-marker-alt" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $order->shipping_address }}
                            </div>
                        </div>

                        <!-- Order Date -->
                        <div>
                            <x-form-label value="Order Date" icon="fas fa-calendar" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $order->order_date->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Total Amount -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-dollar-sign text-yellow-500"></i>
                            {{ __('Order Summary') }}
                        </h3>
                        <div>
                            <x-form-label value="Total Amount" icon="fas fa-money-bill" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ number_format($order->total_amount) }} {{ __('VND') }}
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-toggle-on text-purple-500"></i>
                            {{ __('Status') }}
                        </h3>
                        <div>
                            <x-form-label value="Order Status" icon="fas fa-info-circle" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ __('Pending') }}
                                        </span>
                                    @break

                                    @case('confirmed')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ __('Confirmed') }}
                                        </span>
                                    @break

                                    @case('processing')
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            {{ __('Processing') }}
                                        </span>
                                    @break

                                    @case('shipping')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ __('Shipping') }}
                                        </span>
                                    @break

                                    @case('delivered')
                                        <span class="px-2 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                            {{ __('Delivered') }}
                                        </span>
                                    @break

                                    @case('completed')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ __('Completed') }}
                                        </span>
                                    @break

                                    @case('cancelled')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            {{ __('Cancelled') }}
                                        </span>
                                    @break

                                    @case('refunded')
                                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                            {{ __('Refunded') }}
                                        </span>
                                    @break

                                    @case('failed')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            {{ __('Failed') }}
                                        </span>
                                    @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-list-ul text-indigo-500"></i>
                            {{ __('Order Items') }}
                        </h3>
                        <x-table title="Items">
                            <x-thead>
                                <x-tr>
                                    <x-th>{{ __('Book') }}</x-th>
                                    <x-th>{{ __('Quantity') }}</x-th>
                                    <x-th>{{ __('Unit Price') }}</x-th>
                                    <x-th>{{ __('Subtotal') }}</x-th>
                                </x-tr>
                            </x-thead>
                            <x-tbody>
                                @foreach ($order->items as $item)
                                    <x-tr>
                                        <x-td>{{ $item->book->title }}</x-td>
                                        <x-td>{{ $item->quantity }}</x-td>
                                        <x-td>{{ number_format($item->unit_price) }} {{ __('VND') }}</x-td>
                                        <x-td>{{ number_format($item->subtotal) }} {{ __('VND') }}</x-td>
                                    </x-tr>
                                @endforeach
                            </x-tbody>
                        </x-table>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-credit-card text-green-500"></i>
                            {{ __('Payment Information') }}
                        </h3>
                        @if ($order->payment)
                            <div class="space-y-4">
                                <!-- Payment Method -->
                                <div>
                                    <x-form-label value="Payment Method" icon="fas fa-wallet" />
                                    <div
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                        @switch($order->payment->payment_method)
                                            @case('cod')
                                                {{ __('Cash on Delivery') }}
                                            @break

                                            @case('bank_transfer')
                                                {{ __('Bank Transfer') }}
                                            @break

                                            @case('momo')
                                                {{ __('MoMo') }}
                                            @break

                                            @case('vnpay')
                                                {{ __('VNPay') }}
                                            @break

                                            @case('credit_card')
                                                {{ __('Credit Card') }}
                                            @break

                                            @default
                                                {{ __('N/A') }}
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Payment Amount -->
                                <div>
                                    <x-form-label value="Amount" icon="fas fa-money-bill" />
                                    <div
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                        {{ number_format($order->payment->amount) }} {{ __('VND') }}
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div>
                                    <x-form-label value="Payment Status" icon="fas fa-info-circle" />
                                    <div
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                        @switch($order->payment->payment_status)
                                            @case('pending')
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    {{ __('Pending') }}
                                                </span>
                                            @break

                                            @case('completed')
                                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    {{ __('Completed') }}
                                                </span>
                                            @break

                                            @case('failed')
                                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                    {{ __('Failed') }}
                                                </span>
                                            @break

                                            @case('refunded')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                                    {{ __('Refunded') }}
                                                </span>
                                            @break
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Payment Date -->
                                <div>
                                    <x-form-label value="Payment Date" icon="fas fa-calendar" />
                                    <div
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                        {{ $order->payment->paid_at ? $order->payment->paid_at->format('Y-m-d H:i:s') : __('N/A') }}
                                    </div>
                                </div>

                                <!-- Transaction ID -->
                                <div>
                                    <x-form-label value="Transaction ID" icon="fas fa-receipt" />
                                    <div
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                        {{ $order->payment->transaction_id ?? __('N/A') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500">{{ __('No payment information available') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Update Modal -->
            <x-status-update-modal />
        </main>
    </div>
@endsection
