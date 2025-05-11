@props(['type' => 'button', 'variant' => 'primary'])

@php
    $variantClasses = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
        'info' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
        'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-800',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white',
        'link' => 'bg-transparent hover:underline text-blue-600',
        'outline' => 'bg-transparent border border-gray-300 hover:bg-gray-100 text-gray-700',
    ];
    
    $classes = $variantClasses['$variant'] ?? $variantClasses['primary'];
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' . $classes]) }}
>
    {{ $slot }}
</button>