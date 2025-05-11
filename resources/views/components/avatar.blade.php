@props(['user' => null, 'size' => 'md', 'class' => ''])

<div {{ $attributes->merge(['class' => 'avatar ' . $class]) }}>
    @if($user && isset($user->avatar) && $user->avatar)
        <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="rounded-full h-{{ $size }} w-{{ $size }} object-cover">
    @else
        <div class="rounded-full bg-gray-500 h-{{ $size }} w-{{ $size }} flex items-center justify-center text-white">
            @if($user && isset($user->name))
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-{{ $size == 'sm' ? '4' : ($size == 'lg' ? '8' : '6') }} w-{{ $size == 'sm' ? '4' : ($size == 'lg' ? '8' : '6') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            @endif
        </div>
    @endif
</div>