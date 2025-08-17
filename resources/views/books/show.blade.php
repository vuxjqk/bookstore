@extends('layouts.admin')

@section('title', __('Book Details'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Book Management', 'url' => route('books.index')],
            ['label' => 'Book Details'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-book text-blue-500"></i>
                        {{ $book->title }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('View detailed book information.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('books.index')" />
                    <x-edit-button :route="route('books.edit', $book)" />
                    <x-delete-button :route="route('books.destroy', $book)" />
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            {{ __('Basic Information') }}
                        </h3>

                        <!-- Title -->
                        <div>
                            <x-form-label value="Title" icon="fas fa-book" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $book->title }}
                            </div>
                        </div>

                        <!-- Slug -->
                        <div>
                            <x-form-label value="Slug" icon="fas fa-link" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $book->slug }}
                            </div>
                        </div>

                        <!-- Author and Publisher -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-label value="Author" icon="fas fa-user" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->author->name ?? __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Publisher" icon="fas fa-building" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->publisher->name ?? __('N/A') }}
                                </div>
                            </div>
                        </div>

                        <!-- ISBN and Language -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-label value="ISBN" icon="fas fa-barcode" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->isbn ?? __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Language" icon="fas fa-language" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->language ?? __('N/A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-form-label value="Description" icon="fas fa-align-left" />
                            <div
                                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[100px] whitespace-pre-wrap">
                                {{ $book->description ?? __('N/A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Technical Details -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-cogs text-green-500"></i>
                            {{ __('Technical Details') }}
                        </h3>

                        <!-- Pages, Weight, Dimensions -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-form-label value="Pages" icon="fas fa-file" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->pages ?? __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Dimensions" icon="fas fa-ruler" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->dimensions ?? __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Weight (g)" icon="fas fa-weight" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->weight ?? __('N/A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Publication Year, Cover Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-label value="Publication Year" icon="fas fa-calendar" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->publication_year ?? __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Cover Type" icon="fas fa-book-open" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $coverTypes[$book->cover_type] ?? __('N/A') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Book Images -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-image text-indigo-500"></i>
                            {{ __('Book Images') }}
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
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-list text-pink-500"></i>
                            {{ __('Categories') }}
                        </h3>
                        <div>
                            <x-form-label value="Categories" icon="fas fa-folder" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
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
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-dollar-sign text-yellow-500"></i>
                            {{ __('Price and Stock') }}
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-form-label value="Original Price" icon="fas fa-money-bill" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->original_price ? number_format($book->original_price) . ' ' . __('VND') : __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Sale Price" icon="fas fa-tag" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->sale_price ? number_format($book->sale_price) . ' ' . __('VND') : __('N/A') }}
                                </div>
                            </div>
                            <div>
                                <x-form-label value="Stock Quantity" icon="fas fa-warehouse" />
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                    {{ $book->stock_quantity ?? __('N/A') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="fas fa-toggle-on text-purple-500"></i>
                            {{ __('Status') }}
                        </h3>
                        <div>
                            <x-form-label value="Book Status" icon="fas fa-info-circle" />
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                {{ $statuses[$book->status] ?? __('N/A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
