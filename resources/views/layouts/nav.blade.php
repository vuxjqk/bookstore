<!-- Sidebar -->
<div id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform -translate-x-full lg:translate-x-0 sidebar-transition">
    <div class="flex items-center justify-between h-16 px-6 bg-gray-800">
        <h1 class="text-xl font-bold">
            <i class="fas fa-book-open mr-2"></i>
            BookStore Admin
        </h1>
        <button id="closeSidebar" class="lg:hidden text-white hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>

    @php
        $active =
            'flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 hover:text-white transition-colors duration-200 border-r-4 border-blue-500';
        $inactive =
            'flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200';
    @endphp

    <nav class="mt-8 h-[calc(100vh-6rem)] overflow-y-auto scrollbar-hidden">
        <div class="px-6 py-3">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Overview') }}</h2>
        </div>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') ? $active : $inactive }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            {{ __('Dashboard') }}
        </a>

        <div class="px-6 py-3 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Management') }}</h2>
        </div>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('books.*') ? $active : $inactive }}">
            <i class="fas fa-book mr-3"></i>
            {{ __('Books') }}
        </a>
        <a href="{{ route('categories.index') }}"
            class="{{ request()->routeIs('categories.*') ? $active : $inactive }}">
            <i class="fas fa-list mr-3"></i>
            {{ __('Categories') }}
        </a>
        <a href="{{ route('authors.index') }}" class="{{ request()->routeIs('authors.*') ? $active : $inactive }}">
            <i class="fas fa-user-edit mr-3"></i>
            {{ __('Authors') }}
        </a>
        <a href="{{ route('publishers.index') }}"
            class="{{ request()->routeIs('publishers.*') ? $active : $inactive }}">
            <i class="fas fa-building mr-3"></i>
            {{ __('Publishers') }}
        </a>
        <a href="{{ route('suppliers.index') }}"
            class="{{ request()->routeIs('suppliers.*') ? $active : $inactive }}">
            <i class="fas fa-university mr-3"></i>
            {{ __('Suppliers') }}
        </a>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('orders.*') ? $active : $inactive }}">
            <i class="fas fa-shopping-cart mr-3"></i>
            {{ __('Orders') }}
        </a>
        <a href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('customers.*') ? $active : $inactive }}">
            <i class="fas fa-users mr-3"></i>
            {{ __('Customers') }}
        </a>

        <div class="px-6 py-3 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Reports') }}</h2>
        </div>
        <a href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('statistics.*') ? $active : $inactive }}">
            <i class="fas fa-chart-bar mr-3"></i>
            {{ __('Statistics') }}
        </a>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('reports.*') ? $active : $inactive }}">
            <i class="fas fa-file-alt mr-3"></i>
            {{ __('Reports') }}
        </a>

        <div class="px-6 py-3 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Settings') }}</h2>
        </div>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('system.*') ? $active : $inactive }}">
            <i class="fas fa-cog mr-3"></i>
            {{ __('System') }}
        </a>
        <a href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('permissions.*') ? $active : $inactive }}">
            <i class="fas fa-shield-alt mr-3"></i>
            {{ __('Permissions') }}
        </a>
    </nav>
</div>
