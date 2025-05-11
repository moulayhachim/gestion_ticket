<!-- resources/views/components/icon.blade.php -->
@props(['name'])

@if($name == 'arrow-left')
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
@elseif($name == 'chevron-down')
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
@elseif($name == 'paper-clip')
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
    </svg>
@elseif($name == 'paper-airplane')
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
@endif