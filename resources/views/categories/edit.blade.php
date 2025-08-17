@extends('layouts.admin')

@section('title', __('Edit Category'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Category Management', 'url' => route('categories.index')],
            ['label' => 'Edit Category'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-edit text-blue-500"></i>
                        {{ __('Edit Category') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Update the details of the category.') }}</p>
                </div>
                <div>
                    <x-back-button :route="route('categories.index')" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        {{ __('Category Information') }}
                    </h3>

                    <!-- Name -->
                    <div>
                        <x-form-label for="name" value="Name" icon="fas fa-tag" />
                        <x-text-input id="name" name="name" type="text" :value="old('name', $category->name)" required autofocus
                            autocomplete="name" placeholder="{{ __('Enter category name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-form-label for="slug" value="Slug" icon="fas fa-link" />
                        <x-text-input id="slug" name="slug" type="text" :value="old('slug', $category->slug)" required
                            autocomplete="slug" placeholder="{{ __('Enter category slug') }}" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-form-label for="description" value="Description" icon="fas fa-align-left" />
                        <x-textarea id="description" name="description" autocomplete="description"
                            placeholder="{{ __('Enter category description') }}">{{ old('description', $category->description) }}</x-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Update Category') }}
                    </x-primary-button>
                    <x-back-button :route="route('categories.index')" />
                </div>
            </form>
        </main>
    </div>
@endsection
