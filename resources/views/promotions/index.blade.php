@extends('layouts.admin')

@section('title', __('Promotion Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Promotion Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-tags text-blue-500"></i>
                        {{ __('Promotion Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all promotions in the system.') }}</p>
                </div>
                <div>
                    <x-create-button :route="route('promotions.create')" :title="__('Add New Promotion')" />
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Search Promotions') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-form-label for="search" value="Search" icon="fas fa-search" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
                            placeholder="{{ __('Search by promotion code...') }}" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Promotions Table -->
            <x-table title="Promotion List">
                <x-thead>
                    <x-tr>
                        <x-th>
                            <div class="flex items-center justify-between">
                                <span>{{ __('Code') }}</span>
                                <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                            </div>
                        </x-th>
                        <x-th>{{ __('Discount (%)') }}</x-th>
                        <x-th>{{ __('Max Discount') }}</x-th>
                        <x-th>{{ __('Min Order') }}</x-th>
                        <x-th>{{ __('Max Usage') }}</x-th>
                        <x-th>{{ __('Active') }}</x-th>
                        <x-th>{{ __('Start Date') }}</x-th>
                        <x-th>{{ __('End Date') }}</x-th>
                        <x-th>{{ __('Actions') }}</x-th>
                    </x-tr>
                </x-thead>
                <x-tbody>
                    @foreach ($promotions as $promotion)
                        <x-tr>
                            <x-td>{{ $promotion->code }}</x-td>
                            <x-td>{{ number_format($promotion->discount_percentage, 2) }}%</x-td>
                            <x-td>{{ $promotion->max_discount_amount ? number_format($promotion->max_discount_amount, 2) : 'N/A' }}</x-td>
                            <x-td>{{ $promotion->min_order_amount ? number_format($promotion->min_order_amount, 2) : 'N/A' }}</x-td>
                            <x-td>{{ $promotion->max_usage_count ?? 'Unlimited' }}</x-td>
                            <x-td>
                                <span class="{{ $promotion->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $promotion->is_active ? 'Yes' : 'No' }}
                                </span>
                            </x-td>
                            <x-td>{{ $promotion->start_date?->format('Y-m-d H:i') ?? 'N/A' }}</x-td>
                            <x-td>{{ $promotion->end_date?->format('Y-m-d H:i') ?? 'N/A' }}</x-td>
                            <x-td>
                                <div class="flex items-center gap-2">
                                    <x-show-button :route="route('promotions.show', $promotion)" />
                                    <x-edit-button :route="route('promotions.edit', $promotion)" />
                                    <x-delete-button :route="route('promotions.destroy', $promotion)" />
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $promotions->links() }}
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
