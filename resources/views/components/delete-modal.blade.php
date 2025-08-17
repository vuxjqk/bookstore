<div id="delete-modal"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-out">
    <div
        class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl transform transition-all duration-300 ease-out scale-95">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Confirm Deletion') }}</h2>
        </div>
        <p class="text-gray-600 mb-6">
            {{ __('Are you sure you want to delete this item? This action cannot be undone.') }}
        </p>
        <div class="flex justify-end gap-3">
            <button type="button" id="cancel-delete-btn"
                class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </button>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            const cancelBtn = document.getElementById('cancel-delete-btn');

            // Open modal with route-based delete URL
            document.querySelectorAll('[data-delete-route]').forEach(button => {
                button.addEventListener('click', () => {
                    const deleteUrl = button.getAttribute('data-delete-route');
                    form.action = deleteUrl;
                    modal.classList.remove('hidden');
                    modal.querySelector('.scale-95').classList.remove('scale-95');
                });
            });

            // Close modal
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
@endpushOnce
