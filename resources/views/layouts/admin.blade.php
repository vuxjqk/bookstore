<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Custom Styles -->
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

<body class="bg-gray-100 font-figtree antialiased">

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="flex gap-2">
            <span class="w-3 h-3 bg-blue-600 rounded-full animate-bounce"></span>
            <span class="w-3 h-3 bg-blue-600 rounded-full animate-bounce [animation-delay:-.3s]"></span>
            <span class="w-3 h-3 bg-blue-600 rounded-full animate-bounce [animation-delay:-.6s]"></span>
        </div>
    </div>

    @include('layouts.nav')

    <!-- Main Content -->
    <div class="lg:ml-64 content-transition">
        @include('layouts.header')

        <div class="h-[calc(100vh-72px)] overflow-y-auto scrollbar-hidden">
            @yield('content')
        </div>
    </div>

    <!-- Backdrop for Mobile -->
    <div id="backdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Toast Notification -->
    <x-toast />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')

    <script>
        window.addEventListener('load', () => {
            // Hide loading overlay
            document.getElementById('loadingOverlay').classList.add('hidden');
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const backdrop = document.getElementById('backdrop');

            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            };

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            };

            toggleBtn?.addEventListener('click', toggleSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            // User menu toggle
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            userMenuButton?.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            // Close user menu on outside click
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Responsive behavior
            const handleResize = () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            };

            window.addEventListener('resize', handleResize);
            handleResize(); // initial check
        });
    </script>
</body>

</html>
