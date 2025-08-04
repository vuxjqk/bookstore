<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    @include('layouts.nav')

    <!-- Main Content -->
    <div id="main-content" class="lg:ml-64 content-transition">
        @include('layouts.header')

        <div class="h-[calc(100vh-72px)] overflow-y-auto scrollbar-hidden">
            @yield('content')
        </div>
    </div>

    <!-- Backdrop for mobile -->
    <div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Unified Toast -->
    <div id="toast"
        class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform flex items-center space-x-2">
        <i id="toastIcon" class="fas"></i>
        <span id="toastMessage">...</span>
    </div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const backdrop = document.getElementById('backdrop');
        const mainContent = document.getElementById('main-content');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);

        // User menu toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const messageElement = document.getElementById('toastMessage');
            const iconElement = document.getElementById('toastIcon');

            // Reset class
            toast.className =
                'fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform flex items-center space-x-2';

            // Set style based on type
            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-500');
                    iconElement.className = 'fas fa-check-circle';
                    break;
                case 'error':
                    toast.classList.add('bg-red-500');
                    iconElement.className = 'fas fa-exclamation-circle';
                    break;
                case 'info':
                    toast.classList.add('bg-blue-500');
                    iconElement.className = 'fas fa-info-circle';
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-500');
                    iconElement.className = 'fas fa-exclamation-triangle';
                    break;
                default:
                    toast.classList.add('bg-gray-700');
                    iconElement.className = 'fas fa-bell';
                    break;
            }

            // Set message & show
            messageElement.textContent = message;
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                toast.classList.remove('translate-x-0');
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>

</html>
