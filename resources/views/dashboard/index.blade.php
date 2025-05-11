<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-500">Tickets Ouverts</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mt-2">
                            <p class="text-3xl font-bold">{{ $openTicketsCount }}</p>
                            <p class="text-sm text-gray-500">
                                @if($openTicketsPercentage > 0)
                                    <span class="text-green-500">+{{ $openTicketsPercentage }}%</span>
                                @elseif($openTicketsPercentage < 0)
                                    <span class="text-red-500">{{ $openTicketsPercentage }}%</span>
                                @else
                                    <span>0%</span>
                                @endif
                                depuis la période précédente
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-500">Tickets En Cours</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mt-2">
                            <p class="text-3xl font-bold">{{ $inProgressTicketsCount }}</p>
                            <p class="text-sm text-gray-500">
                                @if($inProgressTicketsPercentage > 0)
                                    <span class="text-green-500">+{{ $inProgressTicketsPercentage }}%</span>
                                @elseif($inProgressTicketsPercentage < 0)
                                    <span class="text-red-500">{{ $inProgressTicketsPercentage }}%</span>
                                @else
                                    <span>0%</span>
                                @endif
                                depuis la période précédente
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-500">Temps Moyen de Résolution</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mt-2">
                            <p class="text-3xl font-bold">{{ $averageResolutionTime }}</p>
                            <p class="text-sm text-gray-500">
                                @if($resolutionTimePercentage > 0)
                                    <span class="text-green-500">+{{ $resolutionTimePercentage }}%</span>
                                @elseif($resolutionTimePercentage < 0)
                                    <span class="text-red-500">{{ $resolutionTimePercentage }}%</span>
                                @else
                                    <span>0%</span>
                                @endif
                                depuis la période précédente
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-500">Taux de Satisfaction</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="mt-2">
                            <p class="text-3xl font-bold">{{ $satisfactionRate }}%</p>
                            <p class="text-sm text-gray-500">
                                @if($satisfactionRatePercentage > 0)
                                    <span class="text-green-500">+{{ $satisfactionRatePercentage }}%</span>
                                @elseif($satisfactionRatePercentage < 0)
                                    <span class="text-red-500">{{ $satisfactionRatePercentage }}%</span>
                                @else
                                    <span>0%</span>
                                @endif
                                depuis la période précédente
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid gap-4 mt-8 md:grid-cols-2 lg:grid-cols-7">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-4">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Aperçu des Tickets</h3>
                        <canvas id="tickets-chart" height="350"></canvas>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-2">Performance de l'Équipe</h3>
                        <p class="text-sm text-gray-500 mb-4">Tickets résolus par agent au cours des 30 derniers jours</p>
                        <canvas id="team-performance-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="grid gap-4 mt-8 md:grid-cols-2 lg:grid-cols-7">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-4">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-2">Tickets Récents</h3>
                        <p class="text-sm text-gray-500 mb-4">Les 10 derniers tickets créés ou mis à jour</p>
                        <div class="space-y-4">
                            @foreach($recentTickets as $ticket)
                            @if ($ticket->user_id == auth()->user()->id || auth()->user()->role == "admin")

                                <div class="flex items-start gap-4 rounded-lg border p-3">
                                    <div class="flex-1 grid gap-1">
                                        <div class="font-semibold">{{ $ticket->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $ticket->created_at->diffForHumans() }}</div>
                                        <div class="flex items-center gap-2 pt-1">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: {{ $ticket->status->color }}; color: white;">
                                                {{ $ticket->status->name }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: {{ $ticket->priority->color }}; color: white;">
                                                {{ $ticket->priority->name }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium">
                                                {{ $ticket->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                        Voir
                                    </a>
                                </div>
                                                            
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-2">Métriques des Tickets</h3>
                        <p class="text-sm text-gray-500 mb-4">Répartition par catégorie et priorité</p>
                        
                        <div x-data="{ tab: 'category' }">
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex">
                                    <button @click="tab = 'category'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'category', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'category' }" class="w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm">
                                        Catégorie
                                    </button>
                                    <button @click="tab = 'priority'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'priority', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'priority' }" class="w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm">
                                        Priorité
                                    </button>
                                </nav>
                            </div>
                            
                            <div x-show="tab === 'category'">
                                <canvas id="category-chart" height="300"></canvas>
                            </div>
                            
                            <div x-show="tab === 'priority'" style="display: none;">
                                <canvas id="priority-chart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Données pour les graphiques
            const ticketsData = @json($ticketsChartData);
            const teamData = @json($teamPerformanceData);
            const categoryData = @json($categoryChartData);
            const priorityData = @json($priorityChartData);
            
            // Graphique des tickets
            const ticketsCtx = document.getElementById('tickets-chart').getContext('2d');
            new Chart(ticketsCtx, {
                type: 'bar',
                data: {
                    labels: ticketsData.map(d => d.name),
                    datasets: [
                        {
                            label: 'Ouverts',
                            data: ticketsData.map(d => d.Ouverts),
                            backgroundColor: '#f97316',
                        },
                        {
                            label: 'En cours',
                            data: ticketsData.map(d => d['En cours']),
                            backgroundColor: '#3b82f6',
                        },
                        {
                            label: 'Résolus',
                            data: ticketsData.map(d => d.Résolus),
                            backgroundColor: '#22c55e',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: false,
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Graphique de performance de l'équipe
            const teamCtx = document.getElementById('team-performance-chart').getContext('2d');
            new Chart(teamCtx, {
                type: 'bar',
                data: {
                    labels: teamData.map(d => d.name),
                    datasets: [
                        {
                            label: 'Tickets résolus',
                            data: teamData.map(d => d.tickets),
                            backgroundColor: '#8884d8',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Graphique des catégories
            const categoryCtx = document.getElementById('category-chart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: categoryData.map(d => d.name),
                    datasets: [
                        {
                            data: categoryData.map(d => d.value),
                            backgroundColor: categoryData.map(d => d.color),
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
            
            // Graphique des priorités
            const priorityCtx = document.getElementById('priority-chart').getContext('2d');
            new Chart(priorityCtx, {
                type: 'pie',
                data: {
                    labels: priorityData.map(d => d.name),
                    datasets: [
                        {
                            data: priorityData.map(d => d.value),
                            backgroundColor: priorityData.map(d => d.color),
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>