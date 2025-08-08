@extends('layouts.admin')

@section('title', __('Book Management'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-50 px-6 py-3 text-gray-700">
            <ol class="list-reset flex text-sm">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">{{ __('Home') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ __('Book Management') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Book Management') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('List of all books in the system') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('books.export') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>{{ __('Export Excel') }}
                    </a>
                    <a href="{{ route('books.create') }}"
                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>{{ __('Add new book') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <form class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-filter mr-2 text-blue-500"></i>{{ __('Filter') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"
                            for="search">{{ __('Search') }}</label>
                        <input type="search" id="search" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Book title, slug...') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"
                            for="category">{{ __('Category') }}</label>
                        <input type="search" list="categories" id="category" name="category"
                            value="{{ request('category') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="{{ __('Select category') }}">
                        <datalist id="categories">
                            <option value="">{{ __('All categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"
                            for="status">{{ __('Status') }}</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">{{ __('All statuses') }}</option>
                            @foreach ($statuses as $key => $status)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>{{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Books') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $books->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Available') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $books->where('status', 'available')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Out of Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $books->where('status', 'out_of_stock')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-warehouse text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $books->sum('stock_quantity') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-2 text-green-500"></i>{{ __('Book List') }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Book Information') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Categories') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Sale Price') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Stock') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($books as $book)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                @if ($book->images->first())
                                                    <img src="{{ asset('storage/' . $book->images->first()->image_path) }}"
                                                        alt="{{ $book->images->first()->alt_text }}"
                                                        class="h-full w-full object-cover rounded">
                                                @else
                                                    <i class="fas fa-book text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $book->title }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ __('Auth') }}: {{ $book->author->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ __('Pub') }}: {{ $book->publisher->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $book->pages }} {{ __('pages') }} |
                                                    {{ $book->dimensions }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap space-y-3">
                                        @foreach ($book->categories as $category)
                                            <span
                                                class="block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($book->sale_price) }}₫
                                        </div>
                                        @if ($book->original_price > $book->sale_price)
                                            <div class="text-sm text-gray-500 line-through">
                                                {{ number_format($book->original_price) }}₫
                                            </div>
                                            <div class="text-sm text-red-600">
                                                -{{ round((($book->original_price - $book->sale_price) / $book->original_price) * 100) }}%
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $book->stock_quantity }} {{ __('items') }}
                                        </div>
                                        @if ($book->stock_quantity < 10 && $book->stock_quantity > 0)
                                            <div class="text-sm text-yellow-600">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Low Stock') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($book->status)
                                            @case('available')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                    {{ __('Available') }}
                                                </span>
                                            @break

                                            @case('out_of_stock')
                                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                    {{ __('Out of Stock') }}
                                                </span>
                                            @break

                                            @case('pre_order')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                    {{ __('Pre-order') }}
                                                </span>
                                            @break

                                            @case('discontinued')
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    {{ __('Discontinued') }}
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('books.show', $book) }}"
                                                class="text-blue-600 hover:text-blue-900" title="{{ __('Details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('books.edit', $book) }}"
                                                class="text-yellow-600 hover:text-yellow-900"
                                                title="{{ __('Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="openDeleteModal('{{ route('books.destroy', $book->id) }}')"
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
                    {{ $books->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection
