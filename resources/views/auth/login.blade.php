@extends('layouts.customer')

@section('title', __('Log In'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-indigo-600">{{ __('Home') }}</a>
                <i class="fas fa-chevron-right text-sm"></i>
                <span class="text-indigo-600">{{ __('Log In') }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                        required autofocus autocomplete="username">
                    @if ($errors->has('email'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                        required autocomplete="current-password">
                    @if ($errors->has('password'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember Me') }}</span>
                    </label>
                </div>

                <!-- Links and Submit -->
                <div class="flex items-center justify-between">
                    <div class="flex flex-col gap-2">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-gray-600 hover:text-indigo-600 underline focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        <a href="{{ route('register') }}"
                            class="text-sm text-gray-600 hover:text-indigo-600 underline focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md">
                            {{ __('Don\'t Have an Account?') }}
                        </a>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                        {{ __('Log In') }}
                    </button>
                </div>
            </form>

            <!-- Social Logins -->
            <div class="mt-6 text-center">
                <hr class="w-48 mx-auto border-t-2 border-gray-300 mb-6">
                <p class="text-sm text-gray-600 mb-6">{{ __('Or Log In With') }}</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ url('/auth/google') }}"
                        class="bg-white p-3 rounded-lg shadow flex items-center gap-2 hover:bg-gray-100 transition duration-300">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg"
                            alt="Google" class="w-6 h-6">
                        <span class="text-gray-700">{{ __('Google') }}</span>
                    </a>
                    <a href="{{ url('/auth/facebook') }}"
                        class="bg-white p-3 rounded-lg shadow flex items-center gap-2 hover:bg-gray-100 transition duration-300">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                            alt="Facebook" class="w-6 h-6">
                        <span class="text-gray-700">{{ __('Facebook') }}</span>
                    </a>
                    <a href="{{ url('/auth/github') }}"
                        class="bg-white p-3 rounded-lg shadow flex items-center gap-2 hover:bg-gray-100 transition duration-300">
                        <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub"
                            class="w-6 h-6">
                        <span class="text-gray-700">{{ __('GitHub') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
