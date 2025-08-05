@extends('layouts.admin')

@section('title', __('Publisher management'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-50 px-6 py-3 text-gray-700">
            <ol class="list-reset flex text-sm">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">{{ __('Home') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ __('Publisher management') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Publisher management') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('List of all publishers in the system') }}</p>
                </div>
                <div>
                    <a href="{{ route('publishers.create') }}"
                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>{{ __('Add new publisher') }}
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-filter mr-2 text-blue-500"></i>{{ __('Search') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="search" :value="__('Search')" />
                        <x-text-input id="search" class="block mt-1 w-full" type="search" name="search"
                            :value="request('search')" placeholder="{{ __('Publisher name, slug...') }}" />
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>{{ __('Search') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Publishers Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-2 text-green-500"></i>{{ __('Publisher list') }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-[800px] w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Slug') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Description') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($publishers as $publisher)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $publisher->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $publisher->slug }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $publisher->description ?? __('No description') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('publishers.show', $publisher->id) }}"
                                                class="text-blue-600 hover:text-blue-900" title="{{ __('Details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('publishers.edit', $publisher->id) }}"
                                                class="text-yellow-600 hover:text-yellow-900" title="{{ __('Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                onclick="openDeleteModal('{{ route('publishers.destroy', $publisher->id) }}')"
                                                class="text-red-600 hover:text-red-900 delete-btn"
                                                title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $publishers->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection
