@extends('layouts.customer')

@section('title', __('Profile'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-indigo-600">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-indigo-600">{{ __('Profile') }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
