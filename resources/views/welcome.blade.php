@extends('layouts.customer')

@section('title', __('Welcome'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-indigo-600">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-indigo-600">{{ __('Welcome') }}</span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            @can('is-admin')
                <h1 class="text-3xl sm:text-4xl font-bold mb-4">{{ __('Welcome, Admin!') }}</h1>
                <p class="text-lg sm:text-xl text-gray-200 mb-6">
                    {{ __('Thank you for managing our bookstore. Access the admin dashboard to oversee operations.') }}
                </p>
                <a href="{{ route('dashboard.index') }}"
                    class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    {{ __('Go to Admin Dashboard') }}
                </a>
            @else
                <h1 class="text-3xl sm:text-4xl font-bold mb-4">{{ __('Welcome to Our Bookstore!') }}</h1>
                <p class="text-lg sm:text-xl text-gray-200 mb-6">
                    {{ __('You\'re now part of our community. Dive into a world of books and start exploring today!') }}
                </p>
                <a href="{{ url('/') }}"
                    class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    {{ __('Explore Books') }}
                </a>
            @endcan
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">{{ __('Get Started') }}</h2>
                <p class="text-gray-600 mb-6">
                    @can('is-admin')
                        {{ __('Manage books, users, and orders from your admin dashboard.') }}
                    @else
                        {{ __('Browse our vast collection, update your profile, or start adding books to your cart.') }}
                    @endcan
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('profile.edit') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                        {{ __('Update Profile') }}
                    </a>
                    @can('is-admin')
                        <a href="{{ route('dashboard.index') }}"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                            {{ __('Admin Dashboard') }}
                        </a>
                    @else
                        <a href="{{ url('/') }}"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                            {{ __('Shop Now') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
