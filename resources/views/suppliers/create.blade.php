@extends('layouts.admin')

@section('title', __('Create Supplier'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Supplier Management', 'url' => route('suppliers.index')],
            ['label' => 'Create Supplier'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-truck text-blue-500"></i>
                        {{ __('Create Supplier') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Add a new supplier to the system.') }}</p>
                </div>
                <div>
                    <x-back-button :route="route('suppliers.index')" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
                @csrf
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        {{ __('Supplier Information') }}
                    </h3>

                    <!-- Name -->
                    <div>
                        <x-form-label for="name" value="Name" icon="fas fa-building" />
                        <x-text-input id="name" name="name" type="text" :value="old('name')" required autofocus
                            autocomplete="name" placeholder="{{ __('Enter supplier name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Contact Name -->
                    <div>
                        <x-form-label for="contact_name" value="Contact Name" icon="fas fa-user" />
                        <x-text-input id="contact_name" name="contact_name" type="text" :value="old('contact_name')"
                            autocomplete="contact-name" placeholder="{{ __('Enter contact name') }}" />
                        <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-form-label for="email" value="Email" icon="fas fa-envelope" />
                        <x-text-input id="email" name="email" type="email" :value="old('email')" required
                            autocomplete="email" placeholder="{{ __('Enter supplier email') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-form-label for="phone" value="Phone" icon="fas fa-phone" />
                        <x-text-input id="phone" name="phone" type="tel" :value="old('phone')" autocomplete="tel"
                            placeholder="{{ __('Enter supplier phone') }}" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div>
                        <x-form-label for="address" value="Address" icon="fas fa-map-marker-alt" />
                        <x-textarea id="address" name="address" autocomplete="address"
                            placeholder="{{ __('Enter supplier address') }}">{{ old('address') }}</x-textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <x-form-label for="notes" value="Notes" icon="fas fa-align-left" />
                        <x-textarea id="notes" name="notes" autocomplete="notes"
                            placeholder="{{ __('Enter supplier notes') }}">{{ old('notes') }}</x-textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Save Supplier') }}
                    </x-primary-button>
                    <x-back-button :route="route('suppliers.index')" />
                </div>
            </form>
        </main>
    </div>
@endsection
