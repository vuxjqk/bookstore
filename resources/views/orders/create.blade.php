@extends('layouts.admin')

@section('title', __('Create Sales Order'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Order Management', 'url' => route('orders.index')],
            ['label' => 'Create Sales Order'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-blue-500"></i>
                        {{ __('Create Sales Order') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Add a new sales order to the system.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('orders.index')" />
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <!-- Order Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            {{ __('Order Information') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-label for="customer_id" value="Customer" icon="fas fa-user" />
                                <x-select id="customer_id" name="customer_id" :options="$customers
                                    ->mapWithKeys(function ($customer) {
                                        return [
                                            $customer->id => $customer->phone . ' - ' . $customer->name,
                                        ];
                                    })
                                    ->toArray()"
                                    placeholder="{{ __('Select customer') }}" :selected="old('customer_id')" />
                                <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-form-label for="discount_amount" value="Discount Amount" icon="fas fa-tag" />
                                <x-text-input id="discount_amount" name="discount_amount" type="number"
                                    value="{{ old('discount_amount', 0) }}" min="0" required />
                                <x-input-error :messages="$errors->get('discount_amount')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-form-label for="customer_name" value="Customer Name" icon="fas fa-user" />
                                <x-text-input id="customer_name" name="customer_name" type="text"
                                    value="{{ old('customer_name') }}" autocomplete="customer_name"
                                    placeholder="{{ __('Enter customer name') }}" required />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-form-label for="customer_phone" value="Customer Phone" icon="fas fa-phone" />
                                <x-text-input id="customer_phone" name="customer_phone" type="tel"
                                    value="{{ old('customer_phone') }}" autocomplete="customer_phone"
                                    placeholder="{{ __('Enter customer phone') }}" required />
                                <x-input-error :messages="$errors->get('customer_phone')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                            <i class="fas fa-list-ul text-indigo-500"></i>
                            {{ __('Order Items') }}
                        </h3>
                        <div id="items-container" class="space-y-4">
                            @foreach (old('items', [['book_id' => null, 'quantity' => 1, 'unit_price' => 0]]) as $index => $item)
                                <div class="item-row grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
                                    data-index="{{ $index }}">
                                    <div>
                                        <x-form-label :for="'items[' . $index . '][book_id]'" value="Book" icon="fas fa-book" />
                                        <x-select :id="'items[' . $index . '][book_id]'" name="items[{{ $index }}][book_id]"
                                            :options="$books->pluck('title', 'id')->toArray()" placeholder="{{ __('Select book') }}" required
                                            :selected="old('items.' . $index . '.book_id')" />
                                        <x-input-error :messages="$errors->get('items.' . $index . '.book_id')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-form-label :for="'items[' . $index . '][quantity]'" value="Quantity" icon="fas fa-sort-numeric-up" />
                                        <x-text-input :id="'items[' . $index . '][quantity]'" name="items[{{ $index }}][quantity]"
                                            type="number" min="1"
                                            value="{{ old('items.' . $index . '.quantity', 1) }}" required />
                                        <x-input-error :messages="$errors->get('items.' . $index . '.quantity')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-form-label :for="'items[' . $index . '][unit_price]'" value="Unit Price" icon="fas fa-money-bill" />
                                        <x-text-input :id="'items[' . $index . '][unit_price]'" name="items[{{ $index }}][unit_price]"
                                            type="number" step="0.01" min="0"
                                            value="{{ old('items.' . $index . '.unit_price', 0) }}" readonly />
                                        <x-input-error :messages="$errors->get('items.' . $index . '.unit_price')" class="mt-2" />
                                    </div>
                                    <div>
                                        <button type="button" title="{{ __('Remove') }}"
                                            class="remove-item flex items-center justify-center bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200 opacity-50 cursor-not-allowed"
                                            disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-item"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Item') }}
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('Create Sales Order') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('add-item').addEventListener('click', () => {
                    const container = document.getElementById('items-container');
                    const index = container.querySelectorAll('.item-row').length;
                    const template = `
                        <div class="item-row grid grid-cols-1 md:grid-cols-4 gap-4 items-end" data-index="${index}">
                            <div>
                                <x-form-label :for="'items[${index}][book_id]'" value="Book" icon="fas fa-book" />
                                <x-select :id="'items[${index}][book_id]'" name="items[${index}][book_id]"
                                    :options="$books->pluck('title', 'id')->toArray()"
                                    placeholder="{{ __('Select book') }}" required />
                            </div>
                            <div>
                                <x-form-label :for="'items[${index}][quantity]'" value="Quantity" icon="fas fa-sort-numeric-up" />
                                <x-text-input :id="'items[${index}][quantity]'" name="items[${index}][quantity]" type="number" min="1" value="1" required />
                            </div>
                            <div>
                                <x-form-label :for="'items[${index}][unit_price]'" value="Unit Price" icon="fas fa-money-bill" />
                                <x-text-input :id="'items[${index}][unit_price]'" name="items[${index}][unit_price]" type="number" step="0.01" min="0" value="0" readonly />
                            </div>
                            <div>
                                <button type="button" title="{{ __('Remove') }}"
                                    class="remove-item flex items-center justify-center bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', template);
                    updateRemoveButtons();
                });

                document.addEventListener('click', (e) => {
                    if (e.target.closest('.remove-item')) {
                        e.target.closest('.item-row').remove();
                        updateRemoveButtons();
                    }
                });

                const updateRemoveButtons = () => {
                    const removeButtons = document.querySelectorAll('.remove-item');
                    removeButtons.forEach(button => {
                        if (removeButtons.length === 1) {
                            button.disabled = true;
                            button.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            button.disabled = false;
                            button.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                }

                updateRemoveButtons();

                // Auto-fill customer details when customer_id changes
                document.getElementById('customer_id').addEventListener('change', function() {
                    const selectedId = this.value;
                    const customers = @json($customers->keyBy('id')->map->only(['name', 'phone'])->toArray());
                    const customer = customers[selectedId] || {
                        name: '',
                        phone: '',
                    };

                    const customer_name = document.getElementById('customer_name');
                    const customer_phone = document.getElementById('customer_phone');

                    const isCustomerAvailable = Boolean(customer.name);
                    customer_name.value = customer.name;
                    customer_phone.value = customer.phone;

                    customer_name.toggleAttribute('readonly', isCustomerAvailable);
                    customer_phone.toggleAttribute('readonly', isCustomerAvailable);
                });

                // Initialize with old data or default
                const initialCustomerId = document.getElementById('customer_id').value;
                if (initialCustomerId) {
                    document.getElementById('customer_id').dispatchEvent(new Event('change'));
                }

                // Auto-fill unit_price when book_id changes
                document.addEventListener('change', (e) => {
                    if (e.target.matches('select[name^="items"][name$="[book_id]"]')) {
                        const bookId = e.target.value;
                        const itemRow = e.target.closest('.item-row');
                        if (!itemRow) return;

                        const unitPriceInput = itemRow.querySelector(
                            'input[name^="items"][name$="[unit_price]"]');

                        const books = @json($books->keyBy('id')->map->only(['sale_price'])->toArray());
                        const book = books[bookId] || {
                            sale_price: 0,
                        };

                        unitPriceInput.value = book.sale_price;
                    }
                })
            });
        </script>
    @endpush
@endsection
