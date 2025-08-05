@extends('layouts.admin')

@section('title', __('Publisher details'))

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
                <li class="text-gray-500">{{ __('Publisher details') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $publisher->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Publisher information details') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('publishers.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
                    </a>
                    <a href="{{ route('publishers.edit', $publisher->id) }}"
                        class="px-4 py-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                    </a>
                </div>
            </div>

            <!-- Publisher Details -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>{{ __('Publisher information') }}
                </h3>

                <!-- Name -->
                <div>
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-2" :value="__('Name')" />
                    <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $publisher->name }}
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-2" :value="__('Slug')" />
                    <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $publisher->slug }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-2" :value="__('Description')" />
                    <div
                        class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[100px] whitespace-pre-wrap">
                        {{ $publisher->description ?? __('No description') }}
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
