@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 px-6 py-3 text-gray-700">
        <ol class="list-reset flex text-sm">
            <li><a href="#" class="text-blue-600 hover:text-blue-800">Trang chủ</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">Dashboard</li>
        </ol>
    </nav>

    <!-- Main Content Area -->
    <main class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tổng sách</p>
                        <p class="text-2xl font-bold text-gray-900">1,234</p>
                    </div>
                    <div class="bg-blue-500 p-3 rounded-full">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Đơn hàng</p>
                        <p class="text-2xl font-bold text-gray-900">567</p>
                    </div>
                    <div class="bg-green-500 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Khách hàng</p>
                        <p class="text-2xl font-bold text-gray-900">890</p>
                    </div>
                    <div class="bg-yellow-500 p-3 rounded-full">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Doanh thu</p>
                        <p class="text-2xl font-bold text-gray-900">123M</p>
                    </div>
                    <div class="bg-red-500 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Đơn hàng gần đây</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-receipt text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">#DH001</p>
                                    <p class="text-sm text-gray-500">Nguyễn Văn A</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">450,000đ</p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Hoàn thành
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-receipt text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">#DH002</p>
                                    <p class="text-sm text-gray-500">Trần Thị B</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">320,000đ</p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Đang xử lý
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Thao tác nhanh</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <i class="fas fa-plus text-blue-500 text-2xl mb-2"></i>
                            <span class="text-sm font-medium text-gray-700">Thêm sách</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                            <i class="fas fa-eye text-green-500 text-2xl mb-2"></i>
                            <span class="text-sm font-medium text-gray-700">Xem đơn hàng</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                            <i class="fas fa-chart-line text-yellow-500 text-2xl mb-2"></i>
                            <span class="text-sm font-medium text-gray-700">Báo cáo</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                            <i class="fas fa-cog text-purple-500 text-2xl mb-2"></i>
                            <span class="text-sm font-medium text-gray-700">Cài đặt</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
