@extends('layouts.admin')

@section('title', __('Edit publisher'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-50 px-6 py-3 text-gray-700">
            <ol class="list-reset flex text-sm">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">{{ __('Home') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('publishers.index') }}"
                        class="text-blue-600 hover:text-blue-800">{{ __('Publisher management') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ __('Edit publisher') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Edit publisher') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Update publisher details') }}</p>
                </div>
                <div>
                    <a href="{{ route('publishers.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('publishers.update', $publisher) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>{{ __('Publisher information') }}
                    </h3>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name', $publisher->name)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-input-label for="slug" :value="__('Slug')" />
                        <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                            :value="old('slug', $publisher->slug)" required autocomplete="slug" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <x-textarea id="description" class="block mt-1 w-full" name="description"
                            autocomplete="description">{{ old('description', $publisher->description) }}</x-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex item-center space-x-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>{{ __('Update') }}
                    </x-primary-button>

                    <a href="{{ route('publishers.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </main>
    </div>
@endsection
