@props(['messages'])

@foreach ($messages as $message)
    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
        <i class="fas fa-exclamation-circle"></i>
        {{ $message }}
    </p>
@endforeach
