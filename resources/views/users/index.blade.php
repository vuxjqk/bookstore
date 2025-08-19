@extends('layouts.admin')

@section('title', __('User Management'))

@section('content')
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'User Management']]" />

        <!-- Main Content Area -->
        <main class="mt-6 bg-gray-50 rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-users text-blue-500"></i>
                        {{ __('User Management') }}
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all users in the system.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button :route="route('users.create')">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Add User') }}
                    </x-primary-button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Total Users') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-user-shield text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Admins') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalAdmins }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-user-tie text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Sellers') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalSellers }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-teal-100 rounded-lg">
                            <i class="fas fa-user text-teal-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Customers') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    {{ __('Filter Users') }}
                </h3>
                <form class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <x-form-label for="name" value="Name" icon="fas fa-user" />
                        <x-text-input id="name" name="name" type="search" :value="request('name')"
                            placeholder="{{ __('Search by name...') }}" />
                    </div>
                    <div>
                        <x-form-label for="email" value="Email" icon="fas fa-envelope" />
                        <x-text-input id="email" name="email" type="search" :value="request('email')"
                            placeholder="{{ __('Search by email...') }}" />
                    </div>
                    <div>
                        <x-form-label for="role" value="Role" icon="fas fa-user-tag" />
                        <x-select id="role" name="role" :options="[
                            'customer' => __('Customer'),
                            'admin' => __('Admin'),
                            'seller' => __('Seller'),
                            'importer' => __('Importer'),
                        ]" placeholder="{{ __('Select role') }}"
                            :selected="request('role')" />
                    </div>
                    <div>
                        <x-form-label for="phone" value="Phone" icon="fas fa-phone" />
                        <x-text-input id="phone" name="phone" type="search" :value="request('phone')"
                            placeholder="{{ __('Search by phone...') }}" />
                    </div>
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <x-table title="User List">
                <x-thead>
                    <x-tr>
                        <x-th>{{ __('User ID') }}</x-th>
                        {{-- <x-th>
                            <x-sortable-column column="name" label="{{ __('Name') }}"
                                sortKey="{{ request('sort') }}" />
                        </x-th>
                        <x-th>
                            <x-sortable-column column="email" label="{{ __('Email') }}"
                                sortKey="{{ request('sort') }}" />
                        </x-th>
                        <x-th>
                            <x-sortable-column column="role" label="{{ __('Role') }}"
                                sortKey="{{ request('sort') }}" />
                        </x-th>
                        <x-th>
                            <x-sortable-column column="phone" label="{{ __('Phone') }}"
                                sortKey="{{ request('sort') }}" />
                        </x-th> --}}
                        <x-th>{{ __('Address') }}</x-th>
                        <x-th>{{ __('Actions') }}</x-th>
                    </x-tr>
                </x-thead>
                <x-tbody>
                    @foreach ($users as $user)
                        <x-tr>
                            <x-td>{{ $user->id }}</x-td>
                            <x-td>{{ $user->name }}</x-td>
                            <x-td>{{ $user->email ?? __('N/A') }}</x-td>
                            <x-td>
                                @switch($user->role)
                                    @case('customer')
                                        <span class="px-2 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                            {{ __('Customer') }}
                                        </span>
                                    @break

                                    @case('admin')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ __('Admin') }}
                                        </span>
                                    @break

                                    @case('seller')
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            {{ __('Seller') }}
                                        </span>
                                    @break

                                    @case('importer')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ __('Importer') }}
                                        </span>
                                    @break
                                @endswitch
                            </x-td>
                            <x-td>{{ $user->phone ?? __('N/A') }}</x-td>
                            <x-td>{{ $user->address ?? __('N/A') }}</x-td>
                            <x-td>
                                <div class="flex items-center gap-2">
                                    <x-show-button :route="route('users.show', $user)" />
                                    <x-edit-button :route="route('users.edit', $user)" />
                                    @if ($user->trashed())
                                        <x-restore-button :route="route('users.restore', $user)" />
                                    @else
                                        <x-delete-button :route="route('users.destroy', $user)" />
                                    @endif
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $users->links() }}
            </div>

            <!-- Delete/Restore Modal -->
            <x-delete-modal />
        </main>
    </div>
@endsection
