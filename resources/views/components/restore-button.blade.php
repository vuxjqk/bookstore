<form action="{{ $route }}" method="POST">
    @csrf
    <button type="submit" title="{{ __('Restore') }}"
        class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors duration-200">
        <i class="fas fa-undo-alt"></i>
    </button>
</form>
