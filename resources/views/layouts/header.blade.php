<!-- Header -->
<header class="h-[72px] bg-white shadow-sm border-b border-gray-200">
    <div class="flex justify-between items-center px-6 py-4">
        <div class="flex items-center">
            <button id="toggleSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Admin Panel')</h1>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative hidden md:block">
                <input type="text" placeholder="{{ __('Search') }}..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button class="text-gray-500 hover:text-gray-700 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button id="userMenuButton"
                    class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
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
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i>{{ __('Profile') }}
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i>{{ __('Settings') }}
                    </a>
                    <hr class="my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-red-600 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
