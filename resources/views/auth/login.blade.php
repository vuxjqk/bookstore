<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <div>
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('register') }}">
                    {{ __('Don\'t have an account?') }}
                </a>

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <div class="flex flex-col items-center mt-6">
        <hr class="w-48 border-t-2 border-gray-300 mb-6">
        <p class="text-sm text-gray-600 mb-6">{{ __('Or log in with') }}</p>
        <div class="flex space-x-4">
            <a href="{{ url('/auth/google') }}"
                class="bg-white p-4 rounded-lg shadow flex items-center space-x-2 hover:bg-gray-200 transition duration-300">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google"
                    class="w-6 h-6">
                <span>Google</span>
            </a>
            <a href="{{ url('/auth/facebook') }}"
                class="bg-white p-4 rounded-lg shadow flex items-center space-x-2 hover:bg-gray-200 transition duration-300">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                    alt="Facebook" class="w-6 h-6">
                <span>Facebook</span>
            </a>
            <a href="{{ url('/auth/github') }}"
                class="bg-white p-4 rounded-lg shadow flex items-center space-x-2 hover:bg-gray-200 transition duration-300">
                <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub"
                    class="w-6 h-6">
                <span>GitHub</span>
            </a>
        </div>
    </div>
</x-guest-layout>
