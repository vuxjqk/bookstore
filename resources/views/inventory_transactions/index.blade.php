@extends('layouts.admin')

@section('title', __('Inventory Transactions'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Inventory Transactions']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-warehouse text-blue-500"></i>
                        {{ __('Inventory Transactions') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Track and manage inventory movements.') }}</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Transactions') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalTransactions }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Incoming Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalIncoming }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Outgoing Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalOutgoing }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-balance-scale text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Net Stock Change') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $netStockChange }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Filter Transactions') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-form-label for="transaction_type" value="Transaction Type" icon="fas fa-exchange-alt" />
                        <x-select id="transaction_type" name="transaction_type" :options="[
                            'in' => __('Incoming'),
                            'out' => __('Outgoing'),
                        ]"
                            placeholder="{{ __('Select type') }}" :selected="request('transaction_type')" />
                    </div>
                    <div>
                        <x-form-label for="transaction_date" value="Transaction Date" icon="fas fa-calendar" />
                        <x-text-input id="transaction_date" name="transaction_date" type="date" :value="request('transaction_date')"
                            max="{{ date('Y-m-d') }}" />
                    </div>
                    <div>
                        <x-form-label for="notes" value="Notes" icon="fas fa-sticky-note" />
                        <x-text-input id="notes" name="notes" type="search" :value="request('notes')"
                            placeholder="{{ __('Search by notes...') }}" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <x-table title="Transaction List">
                <x-thead>
                    <x-tr>
                        <x-th>{{ __('Transaction ID') }}</x-th>
                        <x-th>
                            <div class="flex items-center justify-between">
                                <span>{{ __('Book') }}</span>
                                <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                            </div>
                        </x-th>
                        <x-th>{{ __('Type') }}</x-th>
                        <x-th>{{ __('Quantity') }}</x-th>
                        <x-th>
                            <div class="flex items-center justify-between">
                                <span>{{ __('Transaction Date') }}</span>
                                <x-sortable-column :options="['newest', 'oldest']" />
                        </x-th>
                        <x-th>{{ __('Notes') }}</x-th>
                        <x-th>{{ __('Source') }}</x-th>
                    </x-tr>
                </x-thead>
                <x-tbody>
                    @foreach ($transactions as $transaction)
                        <x-tr>
                            <x-td>{{ $transaction->id }}</x-td>
                            <x-td>
                                {{ Str::limit(
                                    $transaction->purchase_order_item_id
                                        ? $transaction->purchase_order_item->book->title
                                        : ($transaction->order_item_id
                                            ? $transaction->order_item->book->title
                                            : __('N/A')),
                                    15,
                                    '...',
                                ) }}
                            </x-td>
                            <x-td>
                                @if ($transaction->transaction_type === 'in')
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        {{ __('Incoming') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        {{ __('Outgoing') }}
                                    </span>
                                @endif
                            </x-td>
                            <x-td>{{ $transaction->quantity }}</x-td>
                            <x-td>{{ $transaction->transaction_date->format('Y-m-d') }}</x-td>
                            <x-td>{{ $transaction->notes ?? __('N/A') }}</x-td>
                            <x-td>
                                @if ($transaction->purchase_order_item_id)
                                    <a href="{{ route('purchase_orders.show', $transaction->purchase_order_item->purchase_order_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ __('Purchase Order #') }}{{ $transaction->purchase_order_item->purchase_order_id }}
                                    </a>
                                @elseif ($transaction->order_item_id)
                                    <a href="{{ route('orders.show', $transaction->order_item->order_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ __('Order #') }}{{ $transaction->order_item->order_id }}
                                    </a>
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $transactions->links() }}
            </div>
        </main>
    </div>
@endsection
