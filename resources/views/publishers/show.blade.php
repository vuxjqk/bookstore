@extends('layouts.admin')

@section('title', __('Publisher Details'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Publisher Management', 'url' => route('publishers.index')],
            ['label' => 'Publisher Details'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-building text-blue-500"></i>
                        {{ $publisher->name }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('View detailed publisher information.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('publishers.index')" />
                    <x-edit-button :route="route('publishers.edit', $publisher)" />
                    <x-delete-button :route="route('publishers.destroy', $publisher)" />
                </div>
            </div>

            <!-- Publisher Details -->
            <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    {{ __('Publisher Information') }}
                </h3>

                <!-- Name -->
                <div>
                    <x-form-label value="Name" icon="fas fa-building" />
                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $publisher->name }}
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <x-form-label value="Slug" icon="fas fa-link" />
                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $publisher->slug }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <x-form-label value="Email" icon="fas fa-envelope" />
                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $publisher->email ?? __('No email') }}
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <x-form-label value="Address" icon="fas fa-map-marker-alt" />
                    <div
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[100px] whitespace-pre-wrap">
                        {{ $publisher->address ?? __('No address') }}
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
