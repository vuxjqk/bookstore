@extends('layouts.admin')

@section('title', __('Create Book'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Book Management', 'url' => route('books.index')],
            ['label' => 'Create Book'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-book text-blue-500"></i>
                        {{ __('Create Book') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Add a new book to the system.') }}</p>
                </div>
                <div>
                    <x-back-button :route="route('books.index')" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('books.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
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
                                <x-form-label for="title" value="Title" icon="fas fa-book" />
                                <x-text-input id="title" name="title" type="text" :value="old('title')" required
                                    autofocus autocomplete="title" placeholder="{{ __('Enter book title') }}" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Slug -->
                            <div>
                                <x-form-label for="slug" value="Slug" icon="fas fa-link" />
                                <x-text-input id="slug" name="slug" type="text" :value="old('slug')" required
                                    autocomplete="slug" placeholder="{{ __('Enter book slug') }}" />
                                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                            </div>

                            <!-- Author and Publisher -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-form-label for="author_id" value="Author" icon="fas fa-user" />
                                    <x-select id="author_id" name="author_id" :options="$authors->pluck('name', 'id')->toArray()"
                                        placeholder="{{ __('Select author') }}" :selected="old('author_id')" />
                                    <x-input-error :messages="$errors->get('author_id')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="publisher_id" value="Publisher" icon="fas fa-building" />
                                    <x-select id="publisher_id" name="publisher_id" :options="$publishers->pluck('name', 'id')->toArray()"
                                        placeholder="{{ __('Select publisher') }}" :selected="old('publisher_id')" />
                                    <x-input-error :messages="$errors->get('publisher_id')" class="mt-2" />
                                </div>
                            </div>

                            <!-- ISBN and Language -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-form-label for="isbn" value="ISBN" icon="fas fa-barcode" />
                                    <x-text-input id="isbn" name="isbn" type="text" :value="old('isbn')"
                                        autocomplete="isbn" placeholder="{{ __('Enter ISBN') }}" />
                                    <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="language" value="Language" icon="fas fa-language" />
                                    <x-select id="language" name="language" :options="$languages"
                                        placeholder="{{ __('Select language') }}" :selected="old('language')" />
                                    <x-input-error :messages="$errors->get('language')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <x-form-label for="description" value="Description" icon="fas fa-align-left" />
                                <x-textarea id="description" name="description" autocomplete="description"
                                    placeholder="{{ __('Enter book description') }}">{{ old('description') }}</x-textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
                                    <x-form-label for="pages" value="Pages" icon="fas fa-file" />
                                    <x-text-input id="pages" name="pages" type="number" :value="old('pages')"
                                        min="0" placeholder="{{ __('Enter pages') }}" />
                                    <x-input-error :messages="$errors->get('pages')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="dimensions" value="Dimensions" icon="fas fa-ruler" />
                                    <x-select id="dimensions" name="dimensions" :options="$dimensions"
                                        placeholder="{{ __('Select dimensions') }}" :selected="old('dimensions')" />
                                    <x-input-error :messages="$errors->get('dimensions')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="weight" value="Weight (g)" icon="fas fa-weight" />
                                    <x-text-input id="weight" name="weight" type="number" :value="old('weight')"
                                        min="0" placeholder="{{ __('Enter weight') }}" />
                                    <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Publication Year, Cover Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-form-label for="publication_year" value="Publication Year"
                                        icon="fas fa-calendar" />
                                    <x-text-input id="publication_year" name="publication_year" type="number"
                                        :value="old('publication_year')" min="1000" max="{{ date('Y') }}"
                                        placeholder="{{ __('Enter publication year') }}" />
                                    <x-input-error :messages="$errors->get('publication_year')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="cover_type" value="Cover Type" icon="fas fa-book-open" />
                                    <x-select id="cover_type" name="cover_type" :options="$coverTypes"
                                        placeholder="{{ __('Select cover type') }}" :selected="old('cover_type')" />
                                    <x-input-error :messages="$errors->get('cover_type')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 mt-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                                <i class="fas fa-image text-indigo-500"></i>
                                {{ __('Book Images') }}
                            </h3>
                            <div class="space-y-4">
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500 mb-2">{{ __('Drag and drop images here or') }}</p>
                                    <label class="cursor-pointer">
                                        <span
                                            class="text-blue-600 hover:text-blue-800 font-medium">{{ __('select files') }}</span>
                                        <input type="file" name="images[]" id="file-input" accept="image/*" multiple
                                            class="hidden">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ __('PNG, JPG, JPEG, GIF, SVG up to 4MB each') }}</p>
                                    <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                                </div>
                                <div id="image-preview-container" class="flex flex-wrap gap-4 mt-4 hidden"></div>
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
                                <x-form-label for="categories" value="Categories" icon="fas fa-folder" />
                                <x-select id="categories" name="categories[]" :options="$categories->pluck('name', 'id')->toArray()"
                                    placeholder="{{ __('Select categories') }}" :selected="old('categories', [])" multiple
                                    size="10" />
                                <x-input-error :messages="$errors->get('categories')" class="mt-2" />
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
                                    <x-form-label for="original_price" value="Original Price" icon="fas fa-money-bill" />
                                    <div class="relative">
                                        <x-text-input id="original_price" name="original_price" type="number"
                                            :value="old('original_price')" min="0" step="0.01" placeholder="0" />
                                        <span class="absolute right-3 top-2 text-gray-500">{{ __('VND') }}</span>
                                    </div>
                                    <x-input-error :messages="$errors->get('original_price')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="sale_price" value="Sale Price" icon="fas fa-tag" />
                                    <div class="relative">
                                        <x-text-input id="sale_price" name="sale_price" type="number" :value="old('sale_price')"
                                            min="0" step="0.01" placeholder="0" />
                                        <span class="absolute right-3 top-2 text-gray-500">{{ __('VND') }}</span>
                                    </div>
                                    <x-input-error :messages="$errors->get('sale_price')" class="mt-2" />
                                </div>
                                <div>
                                    <x-form-label for="stock_quantity" value="Stock Quantity" icon="fas fa-warehouse" />
                                    <x-text-input id="stock_quantity" name="stock_quantity" type="number"
                                        :value="old('stock_quantity')" min="0"
                                        placeholder="{{ __('Enter stock quantity') }}" />
                                    <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
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
                                <x-form-label for="status" value="Book Status" icon="fas fa-info-circle" />
                                <x-select id="status" name="status" :options="$statuses"
                                    placeholder="{{ __('Select status') }}" :selected="old('status')" />
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Add Book') }}
                    </x-primary-button>
                    <x-back-button :route="route('books.index')" />
                </div>
            </form>

            <x-slug />
            <x-image-preview />
        </main>
    </div>
@endsection
