<div id="status-update-modal"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-out">
    <div
        class="bg-white rounded-xl p-8 w-full max-w-5xl shadow-2xl transform transition-all duration-300 ease-out scale-95">
        <div class="flex items-center gap-2 mb-6">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Confirm Status Update') }}</h2>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="flex justify-between items-center mb-2 relative z-10">
                    @php
                        $index = 0;
                    @endphp
                    @foreach ($statuses as $status => $label)
                        <div
                            class="flex flex-col
                                @if ($loop->first) items-start
                                @elseif ($loop->last)
                                    items-end
                                @else
                                    items-center @endif">
                            <div id="{{ $status }}"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-medium"
                                data-tooltip="{{ __('Step ') . ($index + 1) . ': ' . __($status) }}">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-xs mt-1 text-gray-600">{{ __($label) }}</span>
                        </div>
                        @php
                            $index++;
                        @endphp
                    @endforeach
                </div>
                <div class="h-1 bg-gray-300 absolute top-4 left-[18px] right-[18px]">
                    <div id="progress" class="h-1 bg-green-600 transition-all duration-300 ease-out">
                    </div>
                </div>
            </div>
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
            const progress = document.getElementById('progress');
            const updateBtn = document.querySelector('#status-update-form button[type="submit"]');
            const statuses = Object.keys(@json($statuses));

            // Open modal with route-based status update URL
            document.querySelectorAll('[data-status-update-route]').forEach(button => {
                button.addEventListener('click', () => {
                    const statusUpdateUrl = button.getAttribute('data-status-update-route');
                    form.action = statusUpdateUrl;

                    const currentStatus = button.getAttribute('data-status');
                    const currentStatusIndex = statuses.indexOf(currentStatus);

                    let width = '0%';
                    if (currentStatusIndex !== -1) {
                        width = (currentStatusIndex * (100 / (statuses.length - 1))) + '%';
                        updateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        updateBtn.disabled = false;
                    } else {
                        updateBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        updateBtn.disabled = true;
                    }

                    progress.style.width = width;
                    renderStatuses(statuses, currentStatus);

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
                modal.querySelector('#status-update-modal > div').classList.add('scale-95');
            });

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.querySelector('#status-update-modal > div').classList.add('scale-95');
                }
            });

            function renderStatuses(statuses, currentStatus) {
                statuses.forEach((status, index) => {
                    const element = document.getElementById(status);
                    element.classList.remove('bg-yellow-600', 'bg-green-600', 'bg-gray-300', 'text-white',
                        'text-gray-600');

                    if (status === currentStatus) {
                        element.classList.add('bg-yellow-600', 'text-white');
                    } else if (index < statuses.indexOf(currentStatus)) {
                        element.classList.add('bg-green-600', 'text-white');
                    } else {
                        element.classList.add('bg-gray-300', 'text-gray-600');
                    }
                });
            }
        });
    </script>
@endpushOnce
