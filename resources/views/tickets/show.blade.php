<x-app-layout>
    <x-avatar :user="$ticket->user" size="md" class="mr-2" />

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('tickets.index') }}" class="mr-4">
                <x-button variant="outline" size="icon">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    <span class="sr-only">Retour</span>
                </x-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ $ticket->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-start justify-between">
                                    <h3 class="text-lg font-medium">{{ $ticket->title }}</h3>
                                    <div class="flex items-center">
                                        <x-dropdown>
                                            <x-slot name="trigger">
                                                <x-button variant="outline" size="sm">
                                                    Actions
                                                    <x-icon name="chevron-down" class="ml-2 h-4 w-4" />
                                                </x-button>
                                            </x-slot>
                                            <x-dropdown-content>
                                                @can('update', $ticket)
                                                    <x-dropdown-link href="{{ route('tickets.edit', $ticket) }}">
                                                        Modifier
                                                    </x-dropdown-link>
                                                    <x-dropdown-link href="#" x-data="" @click.prevent="$dispatch('open-modal', 'assign-ticket')">
                                                        Assigner
                                                    </x-dropdown-link>
                                                    <x-dropdown-link href="#" x-data="" @click.prevent="$dispatch('open-modal', 'change-status')">
                                                        Changer le statut
                                                    </x-dropdown-link>
                                                @endcan
                                                @can('delete', $ticket)
                                                    <form method="POST" action="{{ route('tickets.destroy', $ticket) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                                            Supprimer
                                                        </x-dropdown-link>
                                                    </form>
                                                @endcan
                                            </x-dropdown-content>
                                        </x-dropdown>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Créé le {{ $ticket->created_at->format('d/m/Y H:i') }} par {{ $ticket->user->name }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <x-badge :color="$ticket->status->color">{{ $ticket->status->name }}</x-badge>
                                    <x-badge :color="$ticket->priority->color">{{ $ticket->priority->name }}</x-badge>
                                    <x-badge variant="outline">{{ $ticket->category->name }}</x-badge>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="rounded-lg border p-4">
                                    <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                                    @if($ticket->attachments->isNotEmpty())
                                        <div class="mt-4">
                                            <h4 class="mb-2 text-sm font-medium">Pièces jointes:</h4>
                                            <div class="space-y-2">
                                                @foreach($ticket->attachments as $attachment)
                                                    <div class="flex items-center gap-2 rounded-md border p-2">
                                                        <x-icon name="paper-clip" class="h-4 w-4" />
                                                        <span class="text-sm">{{ $attachment->name }}</span>
                                                        <span class="text-xs text-gray-500">{{ $attachment->size }}</span>
                                                        <a href="{{ route('attachments.download', $attachment) }}" class="ml-auto">
                                                            <x-button variant="ghost" size="sm">
                                                                Télécharger
                                                            </x-button>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium">Commentaires</h3>
                                    @forelse($ticket->comments as $comment)
                                        <div class="flex gap-4 rounded-lg p-4 {{ $comment->user->isAgent() ? 'bg-gray-50' : 'border' }}">
                                            <x-avatar :user="$comment->user" class="h-10 w-10" />
                                            <div class="flex-1 space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium">{{ $comment->user->name }}</span>
                                                        @if($comment->user->isAgent())
                                                            <x-badge variant="outline" color="primary">Agent</x-badge>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <p class="text-sm">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">Aucun commentaire pour le moment.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="p-6 border-t">
                            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
        <div class="flex flex-col gap-2">
            <x-textarea name="content" placeholder="Ajouter un commentaire..." class="min-h-24" required />
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-button type="button" variant="outline" size="sm" onclick="document.getElementById('file-upload').click()">
                        <x-icon name="paper-clip" class="h-4 w-4 mr-1" />
                        Ajouter un fichier
                    </x-button>
                    <input id="file-upload" type="file" name="attachments[]" multiple class="hidden" />
                    <div id="file-list" class="text-sm"></div>
                </div>
                <x-button type="submit" size="sm">
                    <x-icon name="paper-airplane" class="h-4 w-4 mr-1" />
                    Envoyer
                </x-button>
            </div>
        </div>
    </form>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium mb-4">Détails</h3>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium">Assigné à</h4>
                                    @if($ticket->assignee)
                                        <div class="flex items-center gap-2">
                                            <x-avatar :user="$ticket->assignee" class="h-8 w-8" />
                                            <div>
                                                <p class="text-sm font-medium">{{ $ticket->assignee->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $ticket->assignee->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <x-badge variant="outline">Non assigné</x-badge>
                                    @endif
                                </div>
                                <hr>
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium">Demandeur</h4>
                                    <div class="flex items-center gap-2">
                                        <x-avatar :user="$ticket->user" class="h-8 w-8" />
                                        <div>
                                            <p class="text-sm font-medium">{{ $ticket->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium">Dates</h4>
                                    <div class="grid grid-cols-2 gap-1 text-sm">
                                        <div class="text-gray-500">Créé le:</div>
                                        <div>{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-gray-500">Mis à jour le:</div>
                                        <div>{{ $ticket->updated_at->format('d/m/Y H:i') }}</div>
                                        @if($ticket->resolution_time)
                                            <div class="text-gray-500">Résolu le:</div>
                                            <div>{{ $ticket->resolution_time->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($suggestions) && count($suggestions) > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium mb-2">Suggestions IA</h3>
                            <p class="text-sm text-gray-500 mb-4">Solutions basées sur des tickets similaires</p>
                            <div class="space-y-4">
                                @foreach($suggestions as $suggestion)
                                <div class="rounded-lg border p-3">
                                    <h4 class="font-medium">{{ $suggestion['title'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $suggestion['description'] }}</p>
                                    <a href="{{ $suggestion['link'] }}" class="text-indigo-600 hover:text-indigo-900 text-sm mt-1 inline-block">
                                        Voir la solution complète
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>