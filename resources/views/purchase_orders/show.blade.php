@extends('layouts.admin')

@section('title', __('Purchase Order Details'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Purchase Order Management', 'url' => route('purchase_orders.index')],
            ['label' => 'Purchase Order Details'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-blue-500"></i>
                        {{ __('Purchase Order #') }}{{ $order->id }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('View detailed purchase order information.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('purchase_orders.index')" />
                    <x-export-pdf-button :route="route('purchase_orders.export', $order)" />
                    <x-status-update-button :route="route('purchase_orders.update', $order)" status="{{ $order->status }}" />
                    <x-delete-button :route="route('purchase_orders.destroy', $order)" />
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

                        <!-- Supplier -->
                        <div>
                            <x-form-label value="Supplier" icon="fas fa-building" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $order->supplier->name ?? __('N/A') }}
                            </div>
                        </div>

                        <!-- Order Date -->
                        <div>
                            <x-form-label value="Order Date" icon="fas fa-calendar" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $order->order_date->format('Y-m-d') }}
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-form-label value="Notes" icon="fas fa-sticky-note" />
                            <div
                                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[100px] whitespace-pre-wrap">
                                {{ $order->notes ?? __('N/A') }}
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
                        <div>
                            <x-form-label value="Discount Amount" icon="fas fa-tag" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ number_format($order->discount_amount) }} {{ __('VND') }}
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
                                @if ($order->trashed())
                                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        {{ __('Deleted') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Creator Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-user-edit text-pink-500"></i>
                            {{ __('Creator Information') }}
                        </h3>
                        <div>
                            <x-form-label value="Employee" icon="fas fa-user-tie" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                @if ($order->employee)
                                    {{ $order->employee->id }} - {{ $order->employee->name }}
                                @else
                                    {{ __('N/A') }}
                                @endif
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
            </div>

            <!-- Status Update Modal -->
            <x-status-update-modal :statuses="collect([
                'pending' => __('Pending'),
                'confirmed' => __('Confirmed'),
                'received' => __('Received'),
            ])" />
            <!-- Delete/Restore Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
