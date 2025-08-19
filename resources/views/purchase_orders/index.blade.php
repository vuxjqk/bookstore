@extends('layouts.admin')

@section('title', __('Purchase Order Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Purchase Order Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-blue-500"></i>
                        {{ __('Purchase Order Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all purchase orders in the system.') }}</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
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
                            <i class="fas fa-hourglass-start text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Pending') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalPending }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Confirmed') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalConfirmed }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-truck-loading text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Received') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalReceived }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Filter Purchase Orders') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @if (request('include_deleted'))
                        <input type="hidden" name="include_deleted" value="{{ request('include_deleted') }}">
                    @endif
                    <div>
                        <x-form-label for="supplier" value="Supplier" icon="fas fa-building" />
                        <x-select id="supplier" name="suppliers[]" :options="$suppliers->pluck('name', 'id')->toArray()"
                            placeholder="{{ __('Select supplier') }}" :selected="request('suppliers', [])" />
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
                            'received' => __('Received'),
                            'cancelled' => __('Cancelled'),
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

            <!-- Purchase Orders Table -->
            <div class="relative">
                <form id="deleted-orders-form" class="absolute top-4 right-4 flex items-center gap-2">
                    <x-input-choice name="include_deleted" value="1" :label="__('Show deleted orders')" :checked="request('include_deleted')"
                        onchange="document.getElementById('deleted-orders-form').submit();" />
                </form>
                <x-table title="Purchase Order List">
                    <x-thead>
                        <x-tr>
                            <x-th>{{ __('Order ID') }}</x-th>
                            <x-th>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('Supplier') }}</span>
                                    <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                                </div>
                            </x-th>
                            <x-th>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('Order Date') }}</span>
                                    <x-sortable-column :options="['newest', 'oldest']" />
                                </div>
                            </x-th>
                            <x-th>{{ __('Total Amount') }}</x-th>
                            <x-th>{{ __('Status') }}</x-th>
                            <x-th>{{ __('Actions') }}</x-th>
                        </x-tr>
                    </x-thead>
                    <x-tbody>
                        @foreach ($orders as $order)
                            <x-tr>
                                <x-td>
                                    <div class="text-lg font-medium">{{ $order->id }}</div>
                                    <div class="text-sm">{{ $order->purchase_order_code }}</div>
                                </x-td>
                                <x-td>{{ Str::limit($order->supplier->name ?? __('N/A'), 15, '...') }}</x-td>
                                <x-td>{{ $order->order_date->format('Y-m-d') }}</x-td>
                                <x-td>{{ number_format($order->total_amount) }} {{ __('VND') }}</x-td>
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

                                        @case('received')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                {{ __('Received') }}
                                            </span>
                                        @break

                                        @case('cancelled')
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ __('Cancelled') }}
                                            </span>
                                        @break
                                    @endswitch
                                </x-td>
                                <x-td>
                                    <div class="flex items-center gap-2">
                                        @if (!$order->trashed())
                                            <x-export-pdf-button :route="route('purchase_orders.export', $order)" />
                                            <x-show-button :route="route('purchase_orders.show', $order)" />
                                            <x-status-update-button :route="route('purchase_orders.update', $order)" />
                                            <x-delete-button :route="route('purchase_orders.destroy', $order)" />
                                        @else
                                            <x-restore-button :route="route('purchase_orders.destroy', $order)" />
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
            <x-status-update-modal />
            <!-- Delete/Restore Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
