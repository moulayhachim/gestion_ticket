<!-- resources/views/components/ticket-list.blade.php -->
@props(['tickets' => []])

<div class="space-y-4">
    @forelse($tickets as $ticket)
        <div class="flex items-start gap-4 rounded-lg border p-3">
            <div class="flex-shrink-0 rounded-full p-1">
                @if($ticket->category && $ticket->category->name == 'Bug logiciel')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                @endif
            </div>
            <div class="grid flex-1 gap-1">
                <div class="flex items-center gap-2">
                    <div class="font-semibold">{{ $ticket->title }}</div>
                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        <span>{{ $ticket->id }}</span>
                    </div>
                </div>
                <div class="text-sm text-gray-500">{{ $ticket->created_at->diffForHumans() }}</div>
                <div class="flex items-center gap-2 pt-1">
                    @if($ticket->status)
                        <span class="inline-flex items-center rounded-full bg-{{ $ticket->status->color }} px-2.5 py-0.5 text-xs font-medium text-white">
                            {{ $ticket->status->name }}
                        </span>
                    @endif
                    @if($ticket->priority)
                        <span class="inline-flex items-center rounded-full bg-{{ $ticket->priority->color }} px-2.5 py-0.5 text-xs font-medium text-white">
                            {{ $ticket->priority->name }}
                        </span>
                    @endif
                    @if($ticket->category)
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium">
                            {{ $ticket->category->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($ticket->assignee)
                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-xs font-medium">{{ substr($ticket->assignee->name, 0, 2) }}</span>
                    </div>
                @endif
                <a href="{{ route('tickets.show', $ticket) }}" class="text-sm text-blue-600 hover:underline">
                    Voir
                </a>
            </div>
        </div>
    @empty
        <div class="text-center py-4 text-gray-500">
            Aucun ticket à afficher
        </div>
    @endforelse
</div>