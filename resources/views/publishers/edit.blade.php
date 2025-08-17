@extends('layouts.admin')

@section('title', __('Edit Publisher'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Publisher Management', 'url' => route('publishers.index')],
            ['label' => 'Edit Publisher'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-building text-blue-500"></i>
                        {{ __('Edit Publisher') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Update the details of the publisher.') }}</p>
                </div>
                <div>
                    <x-back-button :route="route('publishers.index')" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('publishers.update', $publisher) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        {{ __('Publisher Information') }}
                    </h3>

                    <!-- Name -->
                    <div>
                        <x-form-label for="name" value="Name" icon="fas fa-building" />
                        <x-text-input id="name" name="name" type="text" :value="old('name', $publisher->name)" required autofocus
                            autocomplete="name" placeholder="{{ __('Enter publisher name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-form-label for="slug" value="Slug" icon="fas fa-link" />
                        <x-text-input id="slug" name="slug" type="text" :value="old('slug', $publisher->slug)" required
                            autocomplete="slug" placeholder="{{ __('Enter publisher slug') }}" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-form-label for="email" value="Email" icon="fas fa-envelope" />
                        <x-text-input id="email" name="email" type="email" :value="old('email', $publisher->email)" autocomplete="email"
                            placeholder="{{ __('Enter publisher email') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div>
                        <x-form-label for="address" value="Address" icon="fas fa-map-marker-alt" />
                        <x-textarea id="address" name="address" autocomplete="address"
                            placeholder="{{ __('Enter publisher address') }}">{{ old('address', $publisher->address) }}</x-textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Update Publisher') }}
                    </x-primary-button>
                    <x-back-button :route="route('publishers.index')" />
                </div>
            </form>
        </main>
    </div>
@endsection
