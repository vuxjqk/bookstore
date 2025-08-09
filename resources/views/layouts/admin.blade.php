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
    <div id="toast"
        class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform flex items-center gap-2">
        <i id="toastIcon" class="fas"></i>
        <span id="toastMessage"></span>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Confirm deletion') }}</h2>
            <p class="text-gray-600 mb-6">{{ __('Are you sure you want to delete? This action cannot be undone.') }}</p>
            <div class="flex justify-end gap-2">
                <button onclick="closeDeleteModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    {{ __('Cancel') }}
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar functionality
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

            toggleBtn.addEventListener('click', toggleSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);

            // User menu functionality
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Window resize handler
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            });

            // Toast notification
            const showToast = (message, type = 'success') => {
                const toast = document.getElementById('toast');
                const messageElement = document.getElementById('toastMessage');
                const iconElement = document.getElementById('toastIcon');

                toast.className =
                    'fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform flex items-center space-x-2';

                const toastStyles = {
                    success: {
                        bg: 'bg-green-500',
                        icon: 'fas fa-check-circle'
                    },
                    error: {
                        bg: 'bg-red-500',
                        icon: 'fas fa-exclamation-circle'
                    },
                    info: {
                        bg: 'bg-blue-500',
                        icon: 'fas fa-info-circle'
                    },
                    warning: {
                        bg: 'bg-yellow-500',
                        icon: 'fas fa-exclamation-triangle'
                    },
                    default: {
                        bg: 'bg-gray-700',
                        icon: 'fas fa-bell'
                    }
                };

                const {
                    bg,
                    icon
                } = toastStyles[type] || toastStyles.default;
                toast.classList.add(bg);
                iconElement.className = icon;
                messageElement.textContent = message;

                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');

                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    toast.classList.remove('translate-x-0');
                }, 3000);
            };

            @if (session('success'))
                showToast("{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                showToast("{{ session('error') }}", "error");
            @endif

            // Slug generation
            ['name', 'title'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', () => {
                        const slug = el.value
                            .toLowerCase()
                            .trim()
                            .replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g, 'a')
                            .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g, 'e')
                            .replace(/í|ì|ỉ|ĩ|ị/g, 'i')
                            .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/g, 'o')
                            .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g, 'u')
                            .replace(/ý|ỳ|ỷ|ỹ|ỵ/g, 'y')
                            .replace(/đ/g, 'd')
                            .replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        document.getElementById('slug').value = slug;
                    });
                }
            });

            // Delete modal
            window.openDeleteModal = (deleteUrl) => {
                const modal = document.getElementById('deleteModal');
                const form = document.getElementById('deleteForm');
                form.action = deleteUrl;
                modal.classList.remove('hidden');
            };

            window.closeDeleteModal = () => {
                document.getElementById('deleteModal').classList.add('hidden');
            };

            // Image preview
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const existingImageIdsContainer = document.getElementById('existingImageIdsContainer');

            if (fileInput && previewContainer) {
                let filesArray = [];
                let existingImagesArray = [
                    @if (isset($book))
                        @foreach ($book->images as $image)
                            {
                                id: {{ $image->id }},
                                url: "{{ asset('storage/' . $image->image_path) }}",
                                name: "{{ basename($image->image_path) }}"
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    @endif
                ];

                const updatePreview = () => {
                    previewContainer.innerHTML = '';
                    previewContainer.classList.toggle('hidden', filesArray.length === 0 && existingImagesArray
                        .length === 0);

                    existingImagesArray.forEach((img, index) => {
                        const previewBox = createPreviewBox(img.url, `Existing Image ${index + 1}`,
                            () => {
                                if (confirm(
                                        '{{ __('Are you sure you want to delete this image?') }}'
                                    )) {
                                    existingImagesArray.splice(index, 1);
                                    updateInputFiles();
                                    updatePreview();
                                    updateExistingImageInputs();
                                }
                            });
                        previewContainer.appendChild(previewBox);
                    });

                    filesArray.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const previewBox = createPreviewBox(e.target.result,
                                `Preview ${index + 1}`, () => {
                                    if (confirm(
                                            '{{ __('Are you sure you want to delete this image?') }}'
                                        )) {
                                        filesArray.splice(index, 1);
                                        updateInputFiles();
                                        updatePreview();
                                    }
                                });
                            previewContainer.appendChild(previewBox);
                        };
                        reader.readAsDataURL(file);
                    });
                };

                const createPreviewBox = (src, alt, onRemove) => {
                    const previewBox = document.createElement('div');
                    previewBox.classList.add('w-32', 'h-32', 'relative', 'rounded-lg', 'overflow-hidden',
                        'shadow', 'group');

                    const image = document.createElement('img');
                    image.src = src;
                    image.alt = alt;
                    image.className = 'w-full h-full object-cover';
                    image.loading = 'lazy';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                    removeBtn.className =
                        'absolute top-1 right-1 text-red-500 bg-white rounded-full p-1 shadow opacity-0 group-hover:opacity-100 transition';
                    removeBtn.title = '{{ __('Delete Image') }}';
                    removeBtn.addEventListener('click', onRemove);

                    previewBox.appendChild(image);
                    previewBox.appendChild(removeBtn);
                    return previewBox;
                };

                const updateInputFiles = () => {
                    const dataTransfer = new DataTransfer();
                    filesArray.forEach(file => dataTransfer.items.add(file));
                    fileInput.files = dataTransfer.files;
                    updateExistingImageInputs();
                };

                const updateExistingImageInputs = () => {
                    if (existingImageIdsContainer) {
                        existingImageIdsContainer.innerHTML = '';
                        existingImagesArray.forEach(img => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'existing_image_ids[]';
                            input.value = img.id;
                            existingImageIdsContainer.appendChild(input);
                        });
                    }
                };

                fileInput.addEventListener('change', (e) => {
                    const newFiles = Array.from(e.target.files).filter(file => file.type.match('image.*'));
                    filesArray.push(...newFiles);
                    updateInputFiles();
                    updatePreview();
                });

                updatePreview();
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
