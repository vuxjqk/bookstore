@extends('layouts.admin')

@section('title', __('Book Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Book Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-book text-blue-500"></i>
                        {{ __('Book Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all books in the system.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-create-button :route="route('books.create')" :title="__('Add New Book')" />
                    <a href="{{ route('books.export') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-download"></i>
                        {{ __('Export Excel') }}
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Books') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalBooks }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Available') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalAvailable }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Out of Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalOutOfStock }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-warehouse text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Stock') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalStock }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Filter Books') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-form-label for="search" value="Search" icon="fas fa-search" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
                            placeholder="{{ __('Search by book title or slug...') }}" />
                    </div>
                    <div>
                        <x-form-label for="category" value="Category" icon="fas fa-folder" />
                        <x-select id="category" name="categories[]" :options="$categories->pluck('name', 'id')->toArray()"
                            placeholder="{{ __('Select category') }}" :selected="request('categories', [])" />
                    </div>
                    <div>
                        <x-form-label for="status" value="Status" icon="fas fa-info-circle" />
                        <x-select id="status" name="statuses[]" :options="$statuses"
                            placeholder="{{ __('Select status') }}" :selected="request('statuses')" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Books Table -->
            <x-table title="Book List">
                <x-thead>
                    <x-tr>
                        <x-th>
                            <div class="flex items-center justify-between">
                                <span>{{ __('Book Information') }}</span>
                                <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                            </div>
                        </x-th>
                        <x-th>{{ __('Categories') }}</x-th>
                        <x-th>{{ __('Price') }}</x-th>
                        <x-th>{{ __('Stock Quantity') }}</x-th>
                        <x-th>{{ __('Status') }}</x-th>
                        <x-th>{{ __('Actions') }}</x-th>
                    </x-tr>
                </x-thead>
                <x-tbody>
                    @foreach ($books as $book)
                        <x-tr>
                            <x-td>
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                        @if ($book->firstImage)
                                            <img src="{{ asset('storage/' . $book->firstImage->image_path) }}"
                                                alt="{{ $book->firstImage->alt_text }}"
                                                class="h-full w-full object-cover rounded">
                                        @else
                                            <i class="fas fa-book text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-lg font-medium">{{ Str::limit($book->title, 15, '...') }}</div>
                                        <div class="text-sm">{{ $book->author->name ?? __('N/A') }}</div>
                                        <div class="text-sm">{{ $book->publisher->name ?? __('N/A') }}</div>
                                    </div>
                                </div>
                            </x-td>
                            <x-td>
                                <div class="flex flex-col gap-2">
                                    @foreach ($book->categories as $category)
                                        <span
                                            class="block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </x-td>
                            <x-td>
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ number_format($book->original_price) }}₫</span>
                                    <span class="line-through">{{ number_format($book->sale_price) }}₫</span>
                                    @if ($book->original_price > $book->sale_price)
                                        <span class="text-sm text-red-600">
                                            -{{ round((($book->original_price - $book->sale_price) / $book->original_price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            </x-td>
                            <x-td>
                                <div>{{ $book->stock_quantity }} {{ __('items') }}</div>
                                @if ($book->stock_quantity < 10 && $book->stock_quantity > 0)
                                    <div class="text-sm text-yellow-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Low Stock') }}
                                    </div>
                                @endif
                            </x-td>
                            <x-td>
                                @switch($book->status)
                                    @case('available')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ __('Available') }}
                                        </span>
                                    @break

                                    @case('out_of_stock')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            {{ __('Out of Stock') }}
                                        </span>
                                    @break

                                    @case('pre_order')
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            {{ __('Pre-order') }}
                                        </span>
                                    @break

                                    @case('discontinued')
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ __('Discontinued') }}
                                        </span>
                                    @break
                                @endswitch
                            </x-td>
                            <x-td>
                                <div class="flex items-center gap-2">
                                    <x-show-button :route="route('books.show', $book)" />
                                    <x-edit-button :route="route('books.edit', $book)" />
                                    <x-delete-button :route="route('books.destroy', $book)" />
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $books->links() }}
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
