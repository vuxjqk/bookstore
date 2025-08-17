<div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-200 hover:shadow-xl">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i class="fas fa-table text-green-500"></i>
            {{ $title ?? 'List' }}
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            {{ $slot }}
        </table>
    </div>
</div>
