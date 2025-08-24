@extends('layouts.admin')

@section('title', __('Order Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Order Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                        {{ __('Order Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all orders in the system.') }}</p>
                </div>
                <div>
                    <x-create-button :route="route('orders.create')" :title="__('Add New Order')" />
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Orders') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-cogs text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Processing') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalProcessing }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-truck text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Shipping') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalShipping }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Completed') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCompleted }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Filter Orders') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @if (request('include_deleted'))
                        <input type="hidden" name="include_deleted" value="{{ request('include_deleted') }}">
                    @endif
                    <div>
                        <x-form-label for="search" value="Search" icon="fas fa-search" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
                            placeholder="{{ __('Search by customer name or phone...') }}" />
                    </div>
                    <div>
                        <x-form-label for="order_date" value="Order Date" icon="fas fa-calendar" />
                        <x-text-input id="order_date" name="order_date" type="date" :value="request('order_date')"
                            max="{{ date('Y-m-d') }}" />
                    </div>
                    <div>
                        <x-form-label for="status" value="Status" icon="fas fa-info-circle" />
                        <x-select id="status" name="status" :options="[
                            'pending' => __('Pending'),
                            'confirmed' => __('Confirmed'),
                            'processing' => __('Processing'),
                            'shipping' => __('Shipping'),
                            'delivered' => __('Delivered'),
                            'completed' => __('Completed'),
                            'cancelled' => __('Cancelled'),
                            'refunded' => __('Refunded'),
                            'failed' => __('Failed'),
                        ]" placeholder="{{ __('Select status') }}"
                            :selected="request('status')" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="relative">
                <form id="deleted-orders-form" class="absolute top-4 right-4 flex items-center gap-2">
                    <x-input-choice name="include_deleted" value="1" :label="__('Show deleted orders')" :checked="request('include_deleted')"
                        onchange="document.getElementById('deleted-orders-form').submit();" />
                </form>
                <x-table title="Order List">
                    <x-thead>
                        <x-tr>
                            <x-th>{{ __('Order ID') }}</x-th>
                            <x-th>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('Customer Information') }}</span>
                                    <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                                </div>
                            </x-th>
                            <x-th>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('Order Date') }}</span>
                                    <x-sortable-column :options="['newest', 'oldest']" />
                                </div>
                            </x-th>
                            <x-th>{{ __('Price') }}</x-th>
                            <x-th>{{ __('Status') }}</x-th>
                            <x-th>{{ __('Actions') }}</x-th>
                        </x-tr>
                    </x-thead>
                    <x-tbody>
                        @foreach ($orders as $order)
                            <x-tr>
                                <x-td>{{ $order->id }}</x-td>
                                <x-td>
                                    <div class="text-lg font-medium">{{ $order->customer_name }}</div>
                                    <div class="text-sm">{{ $order->customer_phone }}</div>
                                    <div class="text-sm">{{ $order->shipping_address }}</div>
                                </x-td>
                                <x-td>{{ $order->order_date->format('d-m-Y') }}</x-td>
                                <x-td>
                                    <div class="flex flex-col">
                                        <span class="font-medium">
                                            {{ number_format($order->total_amount - $order->discount_amount) }}₫
                                        </span>
                                        @if ($order->discount_amount > 0)
                                            <span class="line-through">{{ number_format($order->total_amount) }}₫</span>
                                            <span class="text-sm text-red-600">
                                                -{{ number_format($order->discount_amount) }}₫
                                            </span>
                                        @endif
                                    </div>
                                </x-td>
                                <x-td>
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
                                </x-td>
                                <x-td>
                                    <div class="flex items-center gap-2">
                                        @if (!$order->trashed())
                                            <x-export-pdf-button :route="route('orders.export', $order)" />
                                            <x-show-button :route="route('orders.show', $order)" />
                                            <x-status-update-button :route="route('orders.update', $order)" status="{{ $order->status }}" />
                                            <x-delete-button :route="route('orders.destroy', $order)" />
                                        @else
                                            <x-restore-button :route="route('orders.restore', $order)" />
                                        @endif
                                    </div>
                                </x-td>
                            </x-tr>
                        @endforeach
                    </x-tbody>
                </x-table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $orders->links() }}
            </div>

            <!-- Status Update Modal -->
            <x-status-update-modal :statuses="collect([
                'pending' => __('Pending'),
                'confirmed' => __('Confirmed'),
                'processing' => __('Processing'),
                'shipping' => __('Shipping'),
                'delivered' => __('Delivered'),
                'completed' => __('Completed'),
            ])" />
            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
