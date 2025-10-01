<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BookStore')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        #loadingOverlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .bouncing-dots {
            display: flex;
            gap: 8px;
        }

        .dot {
            width: 12px;
            height: 12px;
            background-color: #2563eb;
            border-radius: 50%;
            animation: bounce 1s infinite ease-in-out;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .book-image-gallery .thumbnail:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in;
        }

        .book-image-gallery .thumbnail.active {
            border: 2px solid #3b82f6;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .toast-success {
            background-color: #10b981;
            color: #ffffff;
        }

        .toast-error {
            background-color: #ef4444;
            color: #ffffff;
        }

        .cart-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .cart-item {
                flex-direction: row;
                align-items: center;
            }
        }

        .favorite-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .favorite-item {
                flex-direction: row;
                align-items: center;
            }
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100 font-figtree antialiased">
    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div class="bouncing-dots">
            <span class="dot" style="animation-delay: 0s;"></span>
            <span class="dot" style="animation-delay: 0.3s;"></span>
            <span class="dot" style="animation-delay: 0.6s;"></span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu (Mobile) -->
                    <button id="menu-toggle" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none"
                        aria-label="{{ __('Toggle menu') }}">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-book text-3xl text-blue-600"></i>
                        <span class="text-2xl font-bold text-gray-800">{{ __('BookStore') }}</span>
                    </div>
                </div>

                <!-- Search Container -->
                <div id="search-container"
                    class="flex items-center bg-white px-2 py-2 transition-all duration-300 w-10 md:flex-grow md:mx-4">
                    <button id="search-toggle" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none"
                        aria-label="{{ __('Toggle search') }}">
                        <i class="fas fa-search text-2xl"></i>
                    </button>
                    <form method="GET" action="{{ route('home.index') }}" id="search-input"
                        class="relative w-0 md:w-full hidden md:block transition-[width] duration-300">
                        <input type="search" id="book-search" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search books...') }}"
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            aria-label="{{ __('Search') }}">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </form>
                </div>

                <!-- Navigation Icons -->
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors"
                        title="{{ __('Favorites') }}" aria-label="{{ __('Favorites') }}">
                        <i class="fas fa-heart text-lg relative">
                            @auth
                                <span
                                    class="absolute -top-3 -right-3 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ Auth::user()->favorites()->count() }}
                                </span>
                            @endauth
                        </i>
                        <span class="ml-1 hidden sm:inline">{{ __('Favorites') }}</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors"
                        title="{{ __('Cart') }}" aria-label="{{ __('Cart') }}">
                        <i class="fas fa-shopping-cart text-lg relative">
                            <span
                                class="absolute -top-3 -right-3 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ collect(session('cart', []))->sum('quantity') }}
                            </span>
                        </i>
                        <span class="ml-1 hidden sm:inline">{{ __('Cart') }}</span>
                    </a>

                    <div>
                        <select id="language-select"
                            class="block w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400">
                            <option value="vi" {{ app()->getLocale() == 'vi' ? 'selected' : '' }}>
                                {{ __('Vietnamese') }}
                            </option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                                {{ __('English') }}
                            </option>
                        </select>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        @auth
                            <button id="userMenuButton"
                                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 focus:outline-none"
                                aria-label="User Menu">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                        alt="{{ __('User Avatar') }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors"
                                title="{{ __('Account') }}" aria-label="{{ __('Account') }}">
                                <i class="fas fa-user text-lg"></i>
                                <span class="ml-1 hidden sm:inline">{{ __('Account') }}</span>
                            </a>
                        @endguest

                        <div id="userMenu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-5 mr-2"></i>{{ __('Profile') }}
                            </a>
                            <a href="#"
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
                </nav>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav id="nav-menu" class="bg-blue-600 text-white hidden md:block">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row md:space-x-8 py-3">
                    <a href="{{ url('/') }}"
                        class="hover:text-blue-200 transition-colors py-2">{{ __('Home') }}</a>
                    <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Novels') }}</a>
                    <a href="#"
                        class="hover:text-blue-200 transition-colors py-2">{{ __('Children Books') }}</a>
                    <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Textbooks') }}</a>
                    <a href="#"
                        class="hover:text-blue-200 transition-colors py-2">{{ __('Foreign Language Books') }}</a>
                    <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Promotions') }}</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sidebar for Mobile -->
    <div id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-blue-600 text-white transform -translate-x-full transition-transform duration-300 z-50">
        <div class="flex justify-between items-center p-4 border-b border-blue-700">
            <span class="text-xl font-bold">{{ __('Menu') }}</span>
            <button id="close-sidebar" class="text-white hover:text-blue-200 focus:outline-none"
                aria-label="{{ __('Close menu') }}">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <nav class="flex flex-col p-4 space-y-2">
            <a href="{{ url('/') }}" class="hover:text-blue-200 transition-colors py-2">{{ __('Home') }}</a>
            <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Novels') }}</a>
            <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Children Books') }}</a>
            <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Textbooks') }}</a>
            <a href="#"
                class="hover:text-blue-200 transition-colors py-2">{{ __('Foreign Language Books') }}</a>
            <a href="#" class="hover:text-blue-200 transition-colors py-2">{{ __('Promotions') }}</a>
        </nav>
    </div>

    <!-- Overlay for Sidebar -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-book text-2xl text-blue-500"></i>
                        <span class="text-xl font-bold">{{ __('BookStore') }}</span>
                    </div>
                    <p>{{ __('Trusted online bookstore with thousands of quality books.') }}</p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition-colors"
                            aria-label="{{ __('Facebook') }}"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-white transition-colors"
                            aria-label="{{ __('Twitter') }}"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-white transition-colors"
                            aria-label="{{ __('Instagram') }}"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">{{ __('About Us') }}</a>
                        </li>
                        <li><a href="#"
                                class="hover:text-white transition-colors">{{ __('Return Policy') }}</a></li>
                        <li><a href="#"
                                class="hover:text-white transition-colors">{{ __('Shopping Guide') }}</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Support') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">{{ __('FAQs') }}</a></li>
                        <li><a href="#"
                                class="hover:text-white transition-colors">{{ __('Shipping Info') }}</a></li>
                        <li><a href="#"
                                class="hover:text-white transition-colors">{{ __('Payment Methods') }}</a></li>
                        <li><a href="#"
                                class="hover:text-white transition-colors">{{ __('Customer Support') }}</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Contact') }}</h3>
                    <p><i class="fas fa-phone mr-2"></i>{{ __('1900 1234') }}</p>
                    <p><i class="fas fa-envelope mr-2"></i>{{ __('info@bookstore.com') }}</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>{{ __('123 ABC Street, Ho Chi Minh City') }}</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p>&copy; {{ now()->year }} {{ __('BookStore') }}. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Confirmation Modal -->
    <div id="modal" class="fixed inset-0 bg-gray-600/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Confirm') }}</h2>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>
            <div class="flex justify-end gap-2">
                <button id="cancelBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    {{ __('Cancel') }}
                </button>
                <button id="confirmBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <x-toast />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
    <script>
        window.addEventListener('load', () => {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', () => {
            const languageSelect = document.getElementById('language-select');

            languageSelect.addEventListener('change', (e) => {
                const locale = e.target.value;

                const baseUrl = "{{ route('change.locale', ['locale' => '__locale__']) }}";
                const redirectUrl = baseUrl.replace('__locale__', locale);

                window.location.href = redirectUrl;
            });

            // Prevent default for placeholder links
            document.querySelectorAll('a[href="#"]').forEach(link => {
                link.addEventListener('click', e => e.preventDefault());
            });

            // Sidebar Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const overlay = document.getElementById('overlay');

            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            };

            menuToggle.addEventListener('click', toggleSidebar);
            closeSidebar.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Search Toggle
            const searchContainer = document.getElementById('search-container');
            const searchToggle = document.getElementById('search-toggle');
            const searchInput = document.getElementById('search-input');

            const toggleSearch = () => {
                searchContainer.classList.toggle('w-full');
                searchContainer.classList.toggle('absolute');
                searchContainer.classList.toggle('left-1/2');
                searchContainer.classList.toggle('top-1/2');
                searchContainer.classList.toggle('-translate-x-1/2');
                searchContainer.classList.toggle('-translate-y-1/2');
                searchContainer.classList.toggle('z-10');
                searchInput.classList.toggle('hidden');
                searchInput.classList.toggle('w-full');
                searchToggle.classList.toggle('opacity-0');
                searchToggle.classList.toggle('pointer-events-none');
                if (!searchInput.classList.contains('hidden')) {
                    setTimeout(() => searchInput.querySelector('input').focus(), 150);
                }
            };

            searchToggle.addEventListener('click', e => {
                e.stopPropagation();
                toggleSearch();
            });

            document.addEventListener('click', e => {
                if (!searchContainer.contains(e.target) && !searchInput.classList.contains('hidden')) {
                    toggleSearch();
                }
            });

            searchContainer.addEventListener('click', e => e.stopPropagation());

            // Debounce window resize
            let resizeTimer;
            const mdBreakpoint = 768;

            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth >= mdBreakpoint) {
                        if (!overlay.classList.contains('hidden')) {
                            toggleSidebar();
                        }
                        if (!searchInput.classList.contains('hidden')) {
                            toggleSearch();
                        }
                    }
                }, 200);
            });

            // Handle session storage messages
            window.onload = () => {
                const success = sessionStorage.getItem('success');
                const error = sessionStorage.getItem('error');

                if (success) {
                    showToast(success, 'success');
                    sessionStorage.removeItem('success');
                }
                if (error) {
                    showToast(error, 'error');
                    sessionStorage.removeItem('error');
                }
            };

            @if (session('status'))
                showToast("{{ session('status') }}", "success");
            @endif

            @if (session('status') == 'verification-link-sent')
                showToast(
                    "{{ __('A new verification link has been sent to the email address you provided during registration.') }}",
                    'success');
            @endif

            @if (session('status') === 'verification-link-sent')
                showToast("{{ __('A new verification link has been sent to your email address.') }}", "success");
            @endif

            @if (session('status') === 'profile-updated')
                showToast("{{ __('Saved.') }}", "success");
            @endif

            @if (session('status') === 'password-updated')
                showToast("{{ __('Saved.') }}", 'success');
            @endif

            // Modal
            window.openConfirmModal = (message) => {
                return new Promise((resolve) => {
                    const modal = document.getElementById('modal');
                    const msg = document.getElementById('confirmMessage');
                    const confirmBtn = document.getElementById('confirmBtn');
                    const cancelBtn = document.getElementById('cancelBtn');

                    msg.textContent = message;
                    modal.classList.remove('hidden');

                    confirmBtn.onclick = () => {
                        modal.classList.add('hidden');
                        resolve(true);
                    };

                    cancelBtn.onclick = () => {
                        modal.classList.add('hidden');
                        resolve(false);
                    };
                });
            };

            // Generic AJAX Request
            let isLoading = false;

            window.sendRequest = async (route, method, body = {}) => {
                if (isLoading) return;
                isLoading = true;

                try {
                    const response = await fetch(route, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });

                    const data = await response.json();
                    sessionStorage.setItem(data.success ? 'success' : 'error', data.message ||
                        '{{ __('Unknown error.') }}');
                    location.reload();
                } catch (error) {
                    sessionStorage.setItem('error', '{{ __('Unknown error.') }}');
                    console.error(error);
                    location.reload();
                }

                isLoading = false;
            };

            // Cart functionality
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const bookId = button.dataset.id;
                    const quantityElement = document.getElementById('quantity');
                    const quantity = quantityElement ? quantityElement.value : 1;
                    sendRequest('{{ route('cart.add') }}', 'POST', {
                        book_id: bookId,
                        quantity
                    });
                });
            });

            // Favorites functionality
            document.querySelectorAll('.remove-favorite-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const result = await openConfirmModal(
                        '{{ __('Are you sure you want to remove this book from favorites?') }}'
                    );

                    if (result) {
                        const bookId = button.dataset.id;
                        sendRequest('{{ route('favorites.destroy') }}', 'POST', {
                            book_id: bookId
                        });
                    }
                });
            });

            document.querySelectorAll('.add-favorite-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const bookId = button.dataset.id;
                    sendRequest('{{ route('favorites.store') }}', 'POST', {
                        book_id: bookId
                    });
                });
            });

            // User menu functionality
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', () => {
                    userMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });

        // Autocomplete book search
        $(document).ready(function() {
            $('#book-search').autocomplete({
                source: "{{ route('home.autocomplete') }}",
                minLength: 1,
                select: function(event, ui) {
                    $('#book-search').val(ui.item.value);
                    window.location.href = "{{ route('home.show', ['book' => '__ID__']) }}"
                        .replace('__ID__', ui.item.id);
                },
                open: function(event, ui) {
                    $('.ui-autocomplete').css('width', $('#book-search').outerWidth());
                }
            });
        });
    </script>
</body>

</html>
