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
                        <x-form-label for="search" value="Notes" icon="fas fa-sticky-note" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
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
                            <x-td>
                                <button
                                    data-notes-update-route="{{ route('inventory_transactions.update', $transaction) }}"
                                    data-notes="{{ old('notes', $transaction->notes) }}"
                                    title="{{ __('Details') }}"class="flex items-center justify-center text-blue-500 hover:text-blue-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </x-td>
                            <x-td>
                                @if ($transaction->purchase_order_item_id)
                                    <a href="{{ route('purchase_orders.show', $transaction->purchase_order_item->purchase_order_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ __('PO #') }}{{ $transaction->purchase_order_item->purchase_order_id }}
                                    </a>
                                @elseif ($transaction->order_item_id)
                                    <a href="{{ route('orders.show', $transaction->order_item->order_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ __('SO #') }}{{ $transaction->order_item->order_id }}
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

            <div id="notes-update-modal"
                class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-out">
                <div
                    class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl transform transition-all duration-300 ease-out scale-95">
                    <form id="notes-update-form" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fas fa-pencil-alt text-blue-500 text-2xl"></i>
                            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Notes') }}</h2>
                        </div>

                        <div class="mb-6">
                            <x-form-label for="notes" value="Notes" icon="fas fa-sticky-note" />
                            <x-textarea id="notes" name="notes" autocomplete="notes"
                                placeholder="{{ __('Enter any additional notes...') }}"></x-textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" id="cancel-notes-update-btn"
                                class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-times"></i>
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-save"></i>
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('notes-update-modal');
                const form = document.getElementById('notes-update-form');
                const cancelBtn = document.getElementById('cancel-notes-update-btn');
                const notesTxt = document.getElementById('notes');

                // Open modal with route-based notes update URL
                document.querySelectorAll('[data-notes-update-route]').forEach(button => {
                    button.addEventListener('click', () => {
                        const notesUpdateUrl = button.getAttribute('data-notes-update-route');
                        const notes = button.getAttribute('data-notes');

                        form.action = notesUpdateUrl;
                        notesTxt.value = notes;

                        modal.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            modal.querySelector('#notes-update-modal > div').classList.remove(
                                'scale-95');
                        });
                    });
                });

                // Close modal
                cancelBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.querySelector('#notes-update-modal > div').classList.add(
                        'scale-95');
                });

                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.querySelector('#notes-update-modal > div').classList.add(
                            'scale-95');
                    }
                });
            });
        </script>
    @endpush
@endsection
