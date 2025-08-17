@extends('layouts.admin')

@section('title', __('Category Details'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Category Management', 'url' => route('categories.index')],
            ['label' => 'Category Details'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-folder text-blue-500"></i>
                        {{ $category->name }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('View detailed category information.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('categories.index')" />
                    <x-edit-button :route="route('categories.edit', $category)" />
                    <x-delete-button :route="route('categories.destroy', $category)" />
                </div>
            </div>

            <!-- Category Details -->
            <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    {{ __('Category Information') }}
                </h3>

                <!-- Name -->
                <div>
                    <x-form-label value="Name" icon="fas fa-tag" />
                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $category->name }}
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <x-form-label value="Slug" icon="fas fa-link" />
                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                        {{ $category->slug }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <x-form-label value="Description" icon="fas fa-align-left" />
                    <div
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[100px] whitespace-pre-wrap">
                        {{ $category->description ?? __('No description') }}
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
