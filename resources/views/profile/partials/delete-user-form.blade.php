<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Account') }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button id="open-delete-modal"
        class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition duration-300">
        {{ __('Delete Account') }}
    </button>

    <div id="delete-user-modal"
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 {{ $errors->userDeletion->isNotEmpty() ? 'block' : 'hidden' }}">
        <div id="modal-overlay"
            class="fixed inset-0 transition-opacity duration-300 ease-out {{ $errors->userDeletion->isNotEmpty() ? 'opacity-100' : 'opacity-0' }}">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
            class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-md sm:mx-auto transition duration-300 ease-out {{ $errors->userDeletion->isNotEmpty() ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95' }}">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">{{ __('Are you sure you want to delete your account?') }}
                </h2>

                @if (!$user->password)
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                    </p>
                @else
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mt-6">
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password"
                            class="mt-1 block w-3/4 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="{{ __('Password') }}">
                        @if ($errors->userDeletion->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>
                @endif

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="close-delete-modal"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition duration-300">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('delete-user-modal');
                const overlay = document.getElementById('modal-overlay');
                const openModalBtn = document.getElementById('open-delete-modal');
                const closeModalBtn = document.getElementById('close-delete-modal');
                const focusableElements =
                    'a, button, input:not([type="hidden"]), textarea, select, details, [tabindex]:not([tabindex="-1"])';
                let focusables, firstFocusable, lastFocusable;

                // Initialize focusable elements
                const updateFocusables = () => {
                    focusables = [...modal.querySelectorAll(focusableElements)].filter(el => !el.hasAttribute(
                        'disabled'));
                    firstFocusable = focusables[0];
                    lastFocusable = focusables[focusables.length - 1];
                };

                // Open modal
                openModalBtn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('block');
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                    modal.querySelector('.sm\\:max-w-md').classList.remove('opacity-0', 'translate-y-4',
                        'sm:scale-95');
                    modal.querySelector('.sm\\:max-w-md').classList.add('opacity-100', 'translate-y-0',
                        'sm:scale-100');
                    document.body.classList.add('overflow-y-hidden');
                    updateFocusables();
                    setTimeout(() => firstFocusable.focus(), 100);
                });

                // Close modal
                const closeModal = () => {
                    modal.classList.remove('block');
                    modal.classList.add('hidden');
                    overlay.classList.remove('opacity-100');
                    overlay.classList.add('opacity-0');
                    modal.querySelector('.sm\\:max-w-md').classList.remove('opacity-100', 'translate-y-0',
                        'sm:scale-100');
                    modal.querySelector('.sm\\:max-w-md').classList.add('opacity-0', 'translate-y-4',
                    'sm:scale-95');
                    document.body.classList.remove('overflow-y-hidden');
                };

                closeModalBtn.addEventListener('click', closeModal);
                overlay.addEventListener('click', closeModal);

                // Handle keyboard navigation
                modal.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        closeModal();
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        updateFocusables();
                        const currentIndex = focusables.indexOf(document.activeElement);
                        if (e.shiftKey) {
                            const prevIndex = currentIndex === 0 ? focusables.length - 1 : currentIndex - 1;
                            focusables[prevIndex].focus();
                        } else {
                            const nextIndex = (currentIndex + 1) % focusables.length;
                            focusables[nextIndex].focus();
                        }
                    }
                });
            });
        </script>
    @endpush
</section>
