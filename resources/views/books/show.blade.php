@extends('layouts.admin')

@section('title', __('Book Details'))

@section('content')
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="bg-gray-50 px-6 py-3 text-gray-700">
            <ol class="list-reset flex text-sm">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">{{ __('Home') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('books.index') }}"
                        class="text-blue-600 hover:text-blue-800">{{ __('Book Management') }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ __('Book Details') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $book->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Book information details') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('books.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
                    </a>
                    <a href="{{ route('books.edit', $book->id) }}"
                        class="px-4 py-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>{{ __('Basic Information') }}
                        </h3>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Title') }}</label>
                            <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $book->title }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Slug') }}</label>
                            <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $book->slug }}
                            </p>
                        </div>

                        <!-- Author and Publisher -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Author') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->author->name ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Publisher') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->publisher->name ?? __('N/A') }}
                                </p>
                            </div>
                        </div>

                        <!-- ISBN and Language -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ISBN') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->isbn ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Language') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->language ?? __('N/A') }}
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                            <p
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 min-h-[100px]">
                                {{ $book->description ?? __('N/A') }}
                            </p>
                        </div>
                    </div>

                    <!-- Technical Details -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-cogs mr-2 text-green-500"></i>{{ __('Technical Details') }}
                        </h3>

                        <!-- Pages, Weight, Dimensions -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Pages') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->pages ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Dimensions') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->dimensions ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Weight (g)') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->weight ?? __('N/A') }}
                                </p>
                            </div>
                        </div>

                        <!-- Publication Year, Cover Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('Publication Year') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->publication_year ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Cover Type') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $coverTypes[$book->cover_type] ?? __('N/A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Book Images -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-image mr-2 text-indigo-500"></i>{{ __('Book Images') }}
                        </h3>

                        <div class="flex flex-wrap gap-4 mt-4">
                            @if ($book->images->isEmpty())
                                <p class="text-gray-500">{{ __('No images available') }}</p>
                            @else
                                @foreach ($book->images as $image)
                                    <div class="w-32 h-32 relative rounded-lg overflow-hidden shadow">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->alt_text }}" class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Categories -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-list mr-2 text-pink-500"></i>{{ __('Categories') }}
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Categories') }}</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                @if ($book->categories->isEmpty())
                                    <p>{{ __('No categories assigned') }}</p>
                                @else
                                    <ul class="list-disc list-inside">
                                        @foreach ($book->categories as $category)
                                            <li>{{ $category->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Price and Stock -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>{{ __('Price and Stock') }}
                        </h3>

                        <!-- Prices -->
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('Original Price') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->original_price ? number_format($book->original_price) . ' ' . __('VND') : __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Sale Price') }}</label>
                                <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                    {{ $book->sale_price ? number_format($book->sale_price) . ' ' . __('VND') : __('N/A') }}
                                </p>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Stock Quantity') }}</label>
                            <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $book->stock_quantity ?? __('N/A') }}
                            </p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                            <i class="fas fa-toggle-on mr-2 text-purple-500"></i>{{ __('Status') }}
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Book Status') }}</label>
                            <p class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $statuses[$book->status] ?? __('N/A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
