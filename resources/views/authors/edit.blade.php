@extends('layouts.admin')

@section('title', __('Edit Author'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Author Management', 'url' => route('authors.index')],
            ['label' => 'Edit Author'],
        ]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-user-edit text-blue-500"></i>
                        {{ __('Edit Author') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Update the details of the author.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-back-button :route="route('authors.index')" />
                    <x-show-button :route="route('authors.show', $author)" />
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('authors.update', $author) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        {{ __('Author Information') }}
                    </h3>

                    <!-- Name -->
                    <div>
                        <x-form-label for="name" value="Name" icon="fas fa-user" />
                        <x-text-input id="name" name="name" type="text" :value="old('name', $author->name)" required autofocus
                            autocomplete="name" placeholder="{{ __('Enter author name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-form-label for="slug" value="Slug" icon="fas fa-link" />
                        <x-text-input id="slug" name="slug" type="text" :value="old('slug', $author->slug)" required
                            autocomplete="slug" placeholder="{{ __('Enter author slug') }}" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-form-label for="email" value="Email" icon="fas fa-envelope" />
                        <x-text-input id="email" name="email" type="email" :value="old('email', $author->email)" autocomplete="email"
                            placeholder="{{ __('Enter author email') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Biography -->
                    <div>
                        <x-form-label for="biography" value="Biography" icon="fas fa-align-left" />
                        <x-textarea id="biography" name="biography" autocomplete="biography"
                            placeholder="{{ __('Enter author biography') }}">{{ old('biography', $author->biography) }}</x-textarea>
                        <x-input-error :messages="$errors->get('biography')" class="mt-2" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4">
                    <x-primary-button>
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Update Author') }}
                    </x-primary-button>
                    <x-back-button :route="route('authors.index')" />
                </div>
            </form>
        </main>
    </div>
@endsection
