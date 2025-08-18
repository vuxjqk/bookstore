<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform -translate-x-full lg:translate-x-0 sidebar-transition">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 bg-gray-800">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-book-open"></i>
            BookStore Admin
        </h1>
        <button id="closeSidebar" class="lg:hidden text-white hover:text-gray-300" aria-label="Close Sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="h-[calc(100vh-4rem)] overflow-y-auto scrollbar-hidden">
        @php
            $active = 'flex items-center gap-2 px-6 py-3 text-gray-100 bg-gray-700 border-r-4 border-blue-500';
            $inactive =
                'flex items-center gap-2 px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200';
        @endphp

        <!-- Overview Section -->
        <div class="px-6 py-2 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Overview') }}</h2>
        </div>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') ? $active : $inactive }}"
            aria-current="{{ request()->routeIs('dashboard.*') ? 'page' : 'false' }}">
            <i class="fas fa-tachometer-alt w-6"></i>
            {{ __('Dashboard') }}
        </a>

        <!-- Management Section -->
        <div class="px-6 py-2 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Management') }}</h2>
        </div>
        @foreach ([
        [
            'route' => 'books.index',
            'icon' => 'fa-book',
            'label' => __('Books'),
            'routeMatch' => 'books.*',
        ],
        ['route' => 'categories.index', 'icon' => 'fa-list', 'label' => __('Categories'), 'routeMatch' => 'categories.*'],
        ['route' => 'authors.index', 'icon' => 'fa-user-edit', 'label' => __('Authors'), 'routeMatch' => 'authors.*'],
        ['route' => 'publishers.index', 'icon' => 'fa-building', 'label' => __('Publishers'), 'routeMatch' => 'publishers.*'],
        ['route' => 'suppliers.index', 'icon' => 'fa-university', 'label' => __('Suppliers'), 'routeMatch' => 'suppliers.*'],
        ['route' => 'orders.index', 'icon' => 'fa-shopping-cart', 'label' => __('Orders'), 'routeMatch' => 'orders.*'],
        ['route' => 'customers.index', 'icon' => 'fa-users', 'label' => __('Customers'), 'routeMatch' => 'customers.*'],
        ['route' => 'purchase_orders.index', 'icon' => 'fa-file-invoice', 'label' => __('Purchase Orders'), 'routeMatch' => 'purchase_orders.*'],
    ] as $item)
            <a href="{{ route($item['route']) }}"
                class="{{ request()->routeIs($item['routeMatch']) ? $active : $inactive }}"
                aria-current="{{ request()->routeIs($item['routeMatch']) ? 'page' : 'false' }}">
                <i class="fas {{ $item['icon'] }} w-6"></i>
                {{ $item['label'] }}
            </a>
        @endforeach

        <!-- Reports Section -->
        <div class="px-6 py-2 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Reports') }}</h2>
        </div>
        @foreach ([
        [
            'route' => 'statistics.index',
            'icon' => 'fa-chart-bar',
            'label' => __('Statistics'),
            'routeMatch' => 'statistics.*',
        ],
        [
            'route' => 'reports.index',
            'icon' => 'fa-file-alt',
            'label' => __('Reports'),
            'routeMatch' => 'reports.*',
        ],
    ] as $item)
            <a href="{{ route($item['route']) }}"
                class="{{ request()->routeIs($item['routeMatch']) ? $active : $inactive }}"
                aria-current="{{ request()->routeIs($item['routeMatch']) ? 'page' : 'false' }}">
                <i class="fas {{ $item['icon'] }} w-6"></i>
                {{ $item['label'] }}
            </a>
        @endforeach

        <!-- Settings Section -->
        <div class="px-6 py-2 mt-4">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Settings') }}</h2>
        </div>
        @foreach ([
        [
            'route' => 'system.index',
            'icon' => 'fa-cog',
            'label' => __('System'),
            'routeMatch' => 'system.*',
        ],
        [
            'route' => 'permissions.index',
            'icon' => 'fa-shield-alt',
            'label' => __('Permissions'),
            'routeMatch' => 'permissions.*',
        ],
    ] as $item)
            <a href="{{ route($item['route']) }}"
                class="{{ request()->routeIs($item['routeMatch']) ? $active : $inactive }}"
                aria-current="{{ request()->routeIs($item['routeMatch']) ? 'page' : 'false' }}">
                <i class="fas {{ $item['icon'] }} w-6"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
