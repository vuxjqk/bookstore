@extends('layouts.admin')

@section('title', __('Edit Promotion'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Promotion Management', 'url' => route('promotions.index')],
            ['label' => 'Edit Promotion'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-tags text-blue-500"></i>
                        {{ __('Edit Promotion') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Update promotion details.') }}</p>
                </div>
                <div>
                    <x-back-button :route="route('promotions.index')" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('promotions.update', $promotion) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        {{ __('Promotion Information') }}
                    </h3>

                    <!-- Code -->
                    <div>
                        <x-form-label for="code" value="Code" icon="fas fa-ticket-alt" />
                        <x-text-input id="code" name="code" type="text" :value="old('code', $promotion->code)" required autofocus
                            autocomplete="code" placeholder="{{ __('Enter promotion code') }}" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    <!-- Discount Percentage -->
                    <div>
                        <x-form-label for="discount_percentage" value="Discount Percentage (%)" icon="fas fa-percent" />
                        <x-text-input id="discount_percentage" name="discount_percentage" type="number" step="0.01"
                            :value="old('discount_percentage', $promotion->discount_percentage)" required placeholder="{{ __('Enter discount percentage') }}" />
                        <x-input-error :messages="$errors->get('discount_percentage')" class="mt-2" />
                    </div>

                    <!-- Max Discount Amount -->
                    <div>
                        <x-form-label for="max_discount_amount" value="Max Discount Amount" icon="fas fa-money-bill" />
                        <x-text-input id="max_discount_amount" name="max_discount_amount" type="number" step="0.01"
                            :value="old('max_discount_amount', $promotion->max_discount_amount)" placeholder="{{ __('Enter max discount amount') }}" />
                        <x-input-error :messages="$errors->get('max_discount_amount')" class="mt-2" />
                    </div>

                    <!-- Min Order Amount -->
                    <div>
                        <x-form-label for="min_order_amount" value="Min Order Amount" icon="fas fa-shopping-cart" />
                        <x-text-input id="min_order_amount" name="min_order_amount" type="number" step="0.01"
                            :value="old('min_order_amount', $promotion->min_order_amount)" placeholder="{{ __('Enter minimum order amount') }}" />
                        <x-input-error :messages="$errors->get('min_order_amount')" class="mt-2" />
                    </div>

                    <!-- Max Usage Count -->
                    <div>
                        <x-form-label for="max_usage_count" value="Max Usage Count" icon="fas fa-redo" />
                        <x-text-input id="max_usage_count" name="max_usage_count" type="number" step="1"
                            :value="old('max_usage_count', $promotion->max_usage_count)" placeholder="{{ __('Enter max usage count') }}" />
                        <x-input-error :messages="$errors->get('max_usage_count')" class="mt-2" />
                    </div>

                    <!-- Is Active -->
                    <div>
                        <x-form-label for="is_active" value="Active" icon="fas fa-toggle-on" />
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-500">
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <!-- Start Date -->
                    <div>
                        <x-form-label for="start_date" value="Start Date" icon="fas fa-calendar-alt" />
                        <x-text-input id="start_date" name="start_date" type="datetime-local" :value="old('start_date', $promotion->start_date?->format('Y-m-d\TH:i'))"
                            placeholder="{{ __('Select start date') }}" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <!-- End Date -->
                    <div>
                        <x-form-label for="end_date" value="End Date" icon="fas fa-calendar-alt" />
                        <x-text-input id="end_date" name="end_date" type="datetime-local" :value="old('end_date', $promotion->end_date?->format('Y-m-d\TH:i'))"
                            placeholder="{{ __('Select end date') }}" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Update Promotion') }}
                    </x-primary-button>
                    <x-back-button :route="route('promotions.index')" />
                </div>
            </form>
        </main>
    </div>
@endsection
