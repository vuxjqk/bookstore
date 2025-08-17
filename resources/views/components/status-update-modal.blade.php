<div id="status-update-modal"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-out">
    <div
        class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl transform transition-all duration-300 ease-out scale-95">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Confirm Status Update') }}</h2>
        </div>
        <p class="text-gray-600 mb-6">
            {{ __('Are you sure you want to update the status of this order? This action cannot be undone.') }}
        </p>
        <div class="flex justify-end gap-3">
            <button type="button" id="cancel-status-update-btn"
                class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </button>
            <form id="status-update-form" method="POST" action="">
                @csrf
                @method('PUT')
                <button type="submit"
                    class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-check"></i>
                    {{ __('Update Status') }}
                </button>
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('status-update-modal');
            const form = document.getElementById('status-update-form');
            const cancelBtn = document.getElementById('cancel-status-update-btn');

            // Open modal with route-based status update URL
            document.querySelectorAll('[data-status-update-route]').forEach(button => {
                button.addEventListener('click', () => {
                    const statusUpdateUrl = button.getAttribute('data-status-update-route');
                    form.action = statusUpdateUrl;
                    modal.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        modal.querySelector('#status-update-modal > div').classList.remove(
                            'scale-95');
                    });
                });
            });

            // Close modal
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.querySelector('#status-update-modal > div').classList.add(
                    'scale-95');
            });

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.querySelector('#status-update-modal > div').classList.add(
                        'scale-95');
                }
            });
        });
    </script>
@endpushOnce
