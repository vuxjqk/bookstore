<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toastContainer = document.getElementById('toast-container');

            window.showToast = (message, type = 'success', duration = 5000) => {
                const toast = document.createElement('div');
                toast.className =
                    'flex items-center gap-2 px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 ease-out opacity-0';

                // Define styles based on type
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
                toast.classList.add(bg, 'text-white', 'max-w-xs', 'shadow-lg', 'animate-bounce-in');

                // Toast content
                toast.innerHTML = `
                    <i class="${icon}"></i>
                    <span class="flex-1">${message}</span>
                    <button type="button" class="close-toast ml-2 text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="progress-bar w-full h-1 bg-opacity-50 bg-white absolute bottom-0 left-0" style="transition: width ${duration}ms linear"></div>
                `;

                // Append to container (stacking)
                toastContainer.appendChild(toast);

                // Animation and progress
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                });

                const progressBar = toast.querySelector('.progress-bar');
                let timeout;
                let startTime;

                const startTimer = () => {
                    startTime = Date.now();
                    timeout = setTimeout(() => {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);

                    requestAnimationFrame(() => {
                        progressBar.style.transition = `width ${duration}ms linear`;
                        progressBar.style.width = '0%';
                    });
                }

                const pauseTimer = () => {
                    clearTimeout(timeout);
                    const elapsed = Date.now() - startTime;
                    duration -= elapsed;

                    const computedStyle = getComputedStyle(progressBar);
                    const currentWidth = computedStyle.width;
                    progressBar.style.transition = 'none';
                    progressBar.style.width = currentWidth;
                }

                // Auto-close with fade-out
                startTimer();

                // Pause on hover
                toast.addEventListener('mouseenter', () => {
                    pauseTimer();
                });

                // Resume on mouse leave
                toast.addEventListener('mouseleave', () => {
                    startTimer();
                });

                // Close button
                toast.querySelector('.close-toast').addEventListener('click', () => {
                    clearTimeout(timeout);
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                });
            }
        });
    </script>
@endPushOnce
