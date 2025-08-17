@extends('layouts.admin')

@section('title', __('Category Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Category Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-folder-open text-blue-500"></i>
                        {{ __('Category Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all categories in the system.') }}</p>
                </div>
                <div>
                    <x-create-button :route="route('categories.create')" :title="__('Add New Category')" />
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Search Categories') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-form-label for="search" value="Search" icon="fas fa-search" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
                            placeholder="{{ __('Search by category name or slug...') }}" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Categories Table -->
            <x-table title="Category List">
                <x-thead>
                    <x-tr>
                        <x-th>
                            <div class="flex items-center justify-between">
                                <span>{{ __('Name') }}</span>
                                <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                            </div>
                        </x-th>
                        <x-th>{{ __('Slug') }}</x-th>
                        <x-th>{{ __('Actions') }}</x-th>
                    </x-tr>
                </x-thead>
                <x-tbody>
                    @foreach ($categories as $category)
                        <x-tr>
                            <x-td>{{ $category->name }}</x-td>
                            <x-td>{{ $category->slug }}</x-td>
                            <x-td>
                                <div class="flex items-center gap-2">
                                    <x-show-button :route="route('categories.show', $category)" />
                                    <x-edit-button :route="route('categories.edit', $category)" />
                                    <x-delete-button :route="route('categories.destroy', $category)" />
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $categories->links() }}
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
