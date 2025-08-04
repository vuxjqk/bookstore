@extends('layouts.admin')

@section('title', __('Profile'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 px-6 py-3 text-gray-700">
        <ol class="list-reset flex text-sm">
            <li><a href="#" class="text-blue-600 hover:text-blue-800">{{ __('Home') }}</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">{{ __('Profile') }}</li>
        </ol>
    </nav>

    <!-- Main Content Area -->
    <main class="p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </main>
@endsection
