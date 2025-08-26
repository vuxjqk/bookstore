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
                        <div class="p-3 bg-teal-100 rounded-lg">
                            <i class="fas fa-user text-teal-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Customers') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
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
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ __('Importers') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalImporters }}</p>
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
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-form-label for="search" value="Search" icon="fas fa-search" />
                        <x-text-input id="search" name="search" type="search" :value="request('search')"
                            placeholder="{{ __('Search by name, email or phone...') }}" />
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
                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="relative">
                <form id="deleted-users-form" class="absolute top-4 right-4 flex items-center gap-2">
                    <x-input-choice name="include_deleted" value="1" :label="__('Show deleted users')" :checked="request('include_deleted')"
                        onchange="document.getElementById('deleted-users-form').submit();" />
                </form>
                <x-table title="User List">
                    <x-thead>
                        <x-tr>
                            <x-th>{{ __('User ID') }}</x-th>
                            <x-th>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('Name') }}</span>
                                    <x-sortable-column :options="['a_to_z', 'z_to_a']" />
                                </div>
                            </x-th>
                            <x-th>{{ __('Email') }}</x-th>
                            <x-th>{{ __('Phone') }}</x-th>
                            <x-th>{{ __('Role') }}</x-th>
                            <x-th>{{ __('Actions') }}</x-th>
                        </x-tr>
                    </x-thead>
                    <x-tbody>
                        @foreach ($users as $user)
                            <x-tr>
                                <x-td>{{ $user->id }}</x-td>
                                <x-td>{{ $user->name }}</x-td>
                                <x-td>{{ $user->email ?? __('N/A') }}</x-td>
                                <x-td>{{ $user->phone }}</x-td>
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
                                <x-td>
                                    <div class="flex items-center gap-2">
                                        @if ($user->trashed())
                                            <x-restore-button :route="route('users.restore', $user)" />
                                            <x-delete-button :route="route('users.forceDelete', $user)" />
                                        @else
                                            <x-show-button :route="route('users.show', $user)" />
                                            <button data-role-update-route="{{ route('users.update', $user) }}"
                                                data-role="{{ old('role', $user->role) }}" title="{{ __('Edit') }}"
                                                class="flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <x-delete-button :route="route('users.destroy', $user)" />
                                        @endif
                                    </div>
                                </x-td>
                            </x-tr>
                        @endforeach
                    </x-tbody>
                </x-table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                {{ $users->links() }}
            </div>

            <!-- Delete/Restore Modal -->
            <x-delete-modal />

            <div id="role-update-modal"
                class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-out">
                <div
                    class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl transform transition-all duration-300 ease-out scale-95">
                    <form id="role-update-form" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fas fa-user text-blue-500 text-2xl"></i>
                            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Role') }}</h2>
                        </div>

                        <div class="mb-6">
                            <x-form-label for="role" value="Role" icon="fas fa-user" />
                            <x-select id="role" name="role" :options="[
                                'customer' => __('Customer'),
                                'admin' => __('Admin'),
                                'seller' => __('Seller'),
                                'importer' => __('Importer'),
                            ]"
                                placeholder="{{ __('Select role') }}" required :selected="old('role', $user->role)" />
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" id="cancel-role-update-btn"
                                class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-times"></i>
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-save"></i>
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('role-update-modal');
                const form = document.getElementById('role-update-form');
                const cancelBtn = document.getElementById('cancel-role-update-btn');
                const roleSlt = document.getElementById('role');

                // Open modal with route-based role update URL
                document.querySelectorAll('[data-role-update-route]').forEach(button => {
                    button.addEventListener('click', () => {
                        const roleUpdateUrl = button.getAttribute('data-role-update-route');
                        const role = button.getAttribute('data-role');

                        form.action = roleUpdateUrl;
                        roleSlt.value = role;

                        modal.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            modal.querySelector('#role-update-modal > div').classList.remove(
                                'scale-95');
                        });
                    });
                });

                // Close modal
                cancelBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.querySelector('#role-update-modal > div').classList.add(
                        'scale-95');
                });

                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.querySelector('#role-update-modal > div').classList.add(
                            'scale-95');
                    }
                });
            });
        </script>
    @endpush
@endsection
