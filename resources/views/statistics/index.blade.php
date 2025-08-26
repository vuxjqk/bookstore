@extends('layouts.admin')

@section('title', __('Statistics'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Statistics']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        {{ __('Statistics') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Detailed statistics with time-based filtering.') }}</p>
                </div>
                <!-- Filter Form -->
                <form action="{{ route('statistics.index') }}" method="GET" class="flex gap-2">
                    <select name="filter" id="filter"
                        class="border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()">
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                    @if ($filter === 'custom')
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                            class="border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
                        <input type="date" name="end_date"
                            value="{{ $endDate ? $endDate->format('Y-m-d') : now()->format('Y-m-d') }}"
                            class="border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
                        <button type="submit"
                            class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Apply</button>
                    @endif
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Total Books') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBooks) }}</p>
                        </div>
                        <div class="bg-blue-500 p-3 rounded-full">
                            <i class="fas fa-book text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Purchase Cost') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPurchaseCostToday) }}₫</p>
                        </div>
                        <div class="bg-yellow-500 p-3 rounded-full">
                            <i class="fas fa-truck-loading text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Orders') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrdersToday) }}</p>
                        </div>
                        <div class="bg-green-500 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenueToday) }}₫</p>
                        </div>
                        <div class="bg-red-500 p-3 rounded-full">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-bar-chart text-blue-500"></i>
                    {{ __('Monthly Revenue') }}
                </h3>
                <canvas id="monthlyRevenueChart"></canvas>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                        <i class="fas fa-shopping-cart text-green-500"></i>
                        {{ __('Recent Orders') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach ($recentOrders as $index => $order)
                            <a href="{{ route('orders.show', $order) }}"
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:scale-105 hover:shadow-lg duration-300 ease-in-out">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-{{ $index % 2 == 0 ? 'blue' : 'yellow' }}-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-receipt text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">#{{ $order->id }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->customer_name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">
                                        {{ number_format($order->total_amount - $order->discount_amount) }}₫
                                    </p>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                {{ __('Pending') }}
                                            </span>
                                        @break

                                        @case('confirmed')
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ __('Confirmed') }}
                                            </span>
                                        @break

                                        @case('processing')
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                {{ __('Processing') }}
                                            </span>
                                        @break

                                        @case('shipping')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                {{ __('Shipping') }}
                                            </span>
                                        @break

                                        @case('delivered')
                                            <span class="px-2 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                                {{ __('Delivered') }}
                                            </span>
                                        @break

                                        @case('completed')
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ __('Completed') }}
                                            </span>
                                        @break

                                        @case('cancelled')
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ __('Cancelled') }}
                                            </span>
                                        @break

                                        @case('refunded')
                                            <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                                {{ __('Refunded') }}
                                            </span>
                                        @break

                                        @case('failed')
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ __('Failed') }}
                                            </span>
                                        @break
                                    @endswitch
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Top Books Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                        <i class="fas fa-pie-chart text-indigo-500"></i>
                        {{ __('Top Books') }}
                    </h3>
                    <canvas id="topBooksChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initial chart rendering
                function renderCharts() {
                    // Monthly Revenue Chart
                    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
                    const monthlyRevenue = @json($monthlyRevenue);
                    const months = Object.keys(monthlyRevenue);
                    const revenues = Object.values(monthlyRevenue);

                    new Chart(monthlyRevenueCtx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Revenue (₫)',
                                data: revenues,
                                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Revenue (₫)'
                                    },
                                    ticks: {
                                        callback: value => `${value.toLocaleString()}₫`
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        }
                    });

                    // Top Books Chart
                    const topBooksCtx = document.getElementById('topBooksChart').getContext('2d');
                    const topBooks = @json($topBooksLimited);
                    const bookLabels = topBooks.map(book => book.title);
                    const bookSales = topBooks.map(book => book.total_sold);

                    new Chart(topBooksCtx, {
                        type: 'pie',
                        data: {
                            labels: bookLabels,
                            datasets: [{
                                data: bookSales,
                                backgroundColor: ['#3b82f6', '#9333ea', '#f59e0b', '#10b981'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    mode: 'point'
                                }
                            }
                        }
                    });
                }

                // Initial render
                renderCharts();

                // Handle filter change with AJAX (simulated)
                document.getElementById('filter').addEventListener('change', function() {
                    const form = this.form;
                    if (this.value === 'custom') {
                        form.querySelectorAll('input[type="date"]').forEach(input => input.classList.remove(
                            'hidden'));
                        form.querySelector('button[type="submit"]').classList.remove('hidden');
                    } else {
                        form.querySelectorAll('input[type="date"]').forEach(input => input.classList.add(
                            'hidden'));
                        form.querySelector('button[type="submit"]').classList.add('hidden');
                        form.submit();
                    }
                });

                // Simulated AJAX update (replace with actual API call)
                function updateStatistics(filter) {
                    // This would typically fetch from /api/statistics?filter={filter}
                    fetch(`/api/statistics?filter=${filter}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.querySelectorAll('.stats-card p:nth-child(2)').forEach((el, idx) => {
                                el.textContent = number_format(data.stats[idx].value) + (idx < 2 ? '₫' :
                                '');
                            });
                            // Update charts (simplified)
                            renderCharts(); // Re-render with new data
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Trigger update on form submit
                document.querySelector('form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const filter = formData.get('filter');
                    updateStatistics(filter);
                });
            });

            // Helper function for number formatting
            function number_format(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        </script>
    @endpush
@endsection
