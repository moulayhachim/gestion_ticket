@props([
    'variant' => 'default',
    'color' => null
])

@php
$variantClasses = [
    'default' => 'bg-primary text-primary-foreground hover:bg-primary/80',
    'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground'
];

$colorClass = $color ? $color : '';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . $colorClass]) }}>
    {{ $slot }}
</span>