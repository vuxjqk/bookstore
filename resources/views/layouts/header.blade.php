<!-- Header -->
<header class="h-16 bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
        <!-- Left Section -->
        <div class="flex items-center gap-4">
            <button id="toggleSidebar" class="lg:hidden text-gray-500 hover:text-gray-700" aria-label="Toggle Sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Admin Panel')</h1>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative hidden md:block">
                <input type="text" placeholder="{{ __('Search') }}..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="{{ __('Search') }}">
                <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button class="text-gray-500 hover:text-gray-700 relative" aria-label="Notifications">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>
            </div>

            <!-- Main Content Area -->
            <div>
                <x-select id="language-select" class="block mt-1 w-full" onchange="changeLanguage(this.value)">
                    <option value="vi" {{ app()->getLocale() == 'vi' ? 'selected' : '' }}>
                        {{ __('Vietnamese') }}
                    </option>
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                        {{ __('English') }}
                    </option>
                </x-select>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button id="userMenuButton"
                    class="flex items-center gap-2 text-gray-700 hover:text-gray-900 focus:outline-none"
                    aria-label="User Menu">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ __('User Avatar') }}"
                            class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div
                            class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="hidden md:block">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>

                <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user w-5 mr-2"></i>{{ __('Profile') }}
                    </a>
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog w-5 mr-2"></i>{{ __('Settings') }}
                    </a>
                    <hr class="my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt w-5 mr-2"></i>{{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        function changeLanguage(locale) {
            let url = "{{ route('change.locale', ['locale' => '__locale__']) }}";
            url = url.replace('__locale__', locale);
            window.location.href = url;
        }
    </script>
@endpush
