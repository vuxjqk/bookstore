@extends('layouts.admin')

@section('title', __('Create Book'))

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
                <li class="text-gray-500">{{ __('Create Book') }}</li>
            </ol>
        </nav>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Book') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Add new book details') }}</p>
                </div>
                <div>
                    <a href="{{ route('books.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('books.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>{{ __('Basic Information') }}
                            </h3>

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                                    {{ __('Title') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="{{ __('Enter book title') }}">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="slug">
                                    {{ __('Slug') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="{{ __('Enter book slug') }}">
                                @error('slug')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Author and Publisher -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="author_id">
                                        {{ __('Author') }}
                                    </label>
                                    <select name="author_id" id="author_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">{{ __('Select author') }}</option>
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}"
                                                {{ old('author_id') === $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="publisher_id">
                                        {{ __('Publisher') }}
                                    </label>
                                    <select name="publisher_id" id="publisher_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">{{ __('Select publisher') }}</option>
                                        @foreach ($publishers as $publisher)
                                            <option value="{{ $publisher->id }}"
                                                {{ old('publisher_id') === $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- ISBN and Language -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="isbn">
                                        {{ __('ISBN') }}
                                    </label>
                                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter ISBN') }}">
                                    @error('isbn')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="language">
                                        {{ __('Language') }}
                                    </label>
                                    <input list="languages" name="language" id="language" value="{{ old('language') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter language') }}">
                                    <datalist id="languages">
                                        @foreach ($languages as $lang)
                                            <option value="{{ $lang }}">
                                        @endforeach
                                    </datalist>
                                    @error('language')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="description">
                                    {{ __('Description') }}
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="{{ __('Enter book description') }}">
                                    {{ old('description') }}    
                                </textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="pages">
                                        {{ __('Pages') }}
                                    </label>
                                    <input type="number" name="pages" id="pages" value="{{ old('pages') }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter pages') }}">
                                    @error('pages')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="dimensions">
                                        {{ __('Dimensions') }}
                                    </label>
                                    <input list="dimensions_list" name="dimensions" id="dimensions"
                                        value="{{ old('dimensions') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter dimensions') }}">
                                    <datalist id="dimensions_list">
                                        @foreach ($dimensions as $dim)
                                            <option value="{{ $dim }}">
                                        @endforeach
                                    </datalist>
                                    @error('dimensions')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="weight">
                                        {{ __('Weight (g)') }}
                                    </label>
                                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter weight') }}">
                                    @error('weight')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Publication Year, Cover Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="publication_year">
                                        {{ __('Publication Year') }}
                                    </label>
                                    <input type="number" name="publication_year" id="publication_year"
                                        value="{{ old('publication_year') }}" min="1000" max="{{ date('Y') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="{{ __('Enter publication year') }}">
                                    @error('publication_year')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="cover_type">
                                        {{ __('Cover Type') }}
                                    </label>
                                    <select name="cover_type" id="cover_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">{{ __('Select cover type') }}</option>
                                        @foreach ($coverTypes as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('cover_type') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cover_type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                                <i class="fas fa-image mr-2 text-indigo-500"></i>{{ __('Book Images') }}
                            </h3>

                            <div class="space-y-4">
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500 mb-2">{{ __('Drag and drop images here or') }}</p>
                                    <label class="cursor-pointer">
                                        <span
                                            class="text-blue-600 hover:text-blue-800 font-medium">{{ __('select files') }}</span>
                                        <input type="file" name="images[]" id="fileInput" accept="image/*" multiple
                                            class="hidden">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ __('PNG, JPG, JPEG, GIF, SVG up to 4MB each') }}</p>
                                    @error('images.*')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Preview area -->
                                <div id="imagePreviewContainer" class="flex flex-wrap gap-4 mt-4 hidden"></div>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="categories">
                                    {{ __('Categories') }}
                                </label>
                                <select name="categories[]" id="categories" multiple size="10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="original_price">
                                        {{ __('Original Price') }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="original_price" id="original_price"
                                            value="{{ old('original_price') }}" min="0" step="0.01"
                                            class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="0">
                                        <span class="absolute right-3 top-2 text-gray-500">{{ __('VND') }}</span>
                                    </div>
                                    @error('original_price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="sale_price">
                                        {{ __('Sale Price') }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="sale_price" id="sale_price"
                                            value="{{ old('sale_price') }}" min="0" step="0.01"
                                            class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="0">
                                        <span class="absolute right-3 top-2 text-gray-500">{{ __('VND') }}</span>
                                    </div>
                                    @error('sale_price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="stock_quantity">
                                    {{ __('Stock Quantity') }}
                                </label>
                                <input type="number" name="stock_quantity" id="stock_quantity"
                                    value="{{ old('stock_quantity') }}" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="{{ __('Enter stock quantity') }}">
                                @error('stock_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-white rounded-lg shadow p-6 space-y-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3">
                                <i class="fas fa-toggle-on mr-2 text-purple-500"></i>{{ __('Status') }}
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="status">
                                    {{ __('Book Status') }}
                                </label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">{{ __('Select status') }}</option>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('books.index') }}"
                        class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>{{ __('Add Book') }}
                    </button>
                </div>
            </form>
        </main>
    </div>
@endsection
