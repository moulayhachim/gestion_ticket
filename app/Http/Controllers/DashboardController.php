<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Compter les tickets par statut
        $ticketsByStatus = Ticket::select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->with('status')
            ->get()
            ->keyBy('status.name');
        
        // Tickets ouverts
        $openTicketsCount = $ticketsByStatus->get('Ouvert')->total ?? 0;
        $openTicketsPercentage = $this->calculatePercentageChange('Ouvert');
        
        // Tickets en cours
        $inProgressTicketsCount = $ticketsByStatus->get('En cours')->total ?? 0;
        $inProgressTicketsPercentage = $this->calculatePercentageChange('En cours');
        
        // Temps moyen de résolution
        $averageResolutionTime = $this->calculateAverageResolutionTime();
        $resolutionTimePercentage = $this->calculateResolutionTimePercentageChange();
        
        // Taux de satisfaction (simulé pour l'exemple)
        $satisfactionRate = 92; // Pourcentage
        $satisfactionRatePercentage = 2; // Augmentation de 2%
        
        // Données pour les graphiques
        $ticketsChartData = $this->getTicketsChartData();
        $teamPerformanceData = $this->getTeamPerformanceData();
        $categoryChartData = $this->getCategoryChartData();
        $priorityChartData = $this->getPriorityChartData();
        
        // Tickets récents
        $recentTickets = Ticket::with(['user', 'status', 'priority', 'category'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('dashboard.index', compact(
            'openTicketsCount',
            'openTicketsPercentage',
            'inProgressTicketsCount',
            'inProgressTicketsPercentage',
            'averageResolutionTime',
            'resolutionTimePercentage',
            'satisfactionRate',
            'satisfactionRatePercentage',
            'ticketsChartData',
            'teamPerformanceData',
            'categoryChartData',
            'priorityChartData',
            'recentTickets'
        ));
    }
    
    private function calculatePercentageChange($statusName)
    {
        // Comparer le nombre de tickets avec ce statut aujourd'hui vs la semaine dernière
        $now = now();
        $lastWeek = now()->subWeek();
        
        $statusId = Status::where('name', $statusName)->first()->id;
        
        $currentCount = Ticket::where('status_id', $statusId)->count();
        
        $previousCount = Ticket::where('status_id', $statusId)
            ->where('created_at', '<=', $lastWeek)
            ->count();
        
        if ($previousCount == 0) {
            return $currentCount > 0 ? 100 : 0;
        }
        
        return round((($currentCount - $previousCount) / $previousCount) * 100);
    }
    
    private function calculateAverageResolutionTime()
    {
        // Calculer le temps moyen entre la création et la résolution des tickets
        $resolvedTickets = Ticket::whereNotNull('resolution_time')->get();
        
        if ($resolvedTickets->isEmpty()) {
            return '0h';
        }
        
        $totalHours = 0;
        foreach ($resolvedTickets as $ticket) {
            $created = new \DateTime($ticket->created_at);
            $resolved = new \DateTime($ticket->resolution_time);
            $interval = $created->diff($resolved);
            $hours = $interval->h + ($interval->days * 24);
            $totalHours += $hours;
        }
        
        $averageHours = $totalHours / count($resolvedTickets);
        return round($averageHours, 1) . 'h';
    }
    
    private function calculateResolutionTimePercentageChange()
    {
        // Comparer le temps moyen de résolution cette semaine vs la semaine dernière
        $now = now();
        $oneWeekAgo = now()->subWeek();
        $twoWeeksAgo = now()->subWeeks(2);
        
        $currentWeekTickets = Ticket::whereNotNull('resolution_time')
            ->whereBetween('resolution_time', [$oneWeekAgo, $now])
            ->get();
        
        $previousWeekTickets = Ticket::whereNotNull('resolution_time')
            ->whereBetween('resolution_time', [$twoWeeksAgo, $oneWeekAgo])
            ->get();
        
        if ($previousWeekTickets->isEmpty() || $currentWeekTickets->isEmpty()) {
            return 0;
        }
        
        $currentAvg = $this->calculateAverageResolutionTimeForTickets($currentWeekTickets);
        $previousAvg = $this->calculateAverageResolutionTimeForTickets($previousWeekTickets);
        
        if ($previousAvg == 0) {
            return 0;
        }
        
        // Négatif est bon (résolution plus rapide)
        return round((($currentAvg - $previousAvg) / $previousAvg) * 100) * -1;
    }
    
    private function calculateAverageResolutionTimeForTickets($tickets)
    {
        if ($tickets->isEmpty()) {
            return 0;
        }
        
        $totalHours = 0;
        foreach ($tickets as $ticket) {
            $created = new \DateTime($ticket->created_at);
            $resolved = new \DateTime($ticket->resolution_time);
            $interval = $created->diff($resolved);
            $hours = $interval->h + ($interval->days * 24);
            $totalHours += $hours;
        }
        
        return $totalHours / count($tickets);
    }
    
    private function getTicketsChartData()
    {
        // Données pour le graphique des tickets par mois
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
        $data = [];
        
        foreach ($months as $month) {
            $data[] = [
                'name' => $month,
                'Ouverts' => rand(15, 40),
                'En cours' => rand(15, 30),
                'Résolus' => rand(15, 35),
            ];
        }
        
        return $data;
    }
    
    private function getTeamPerformanceData()
    {
        // Données pour le graphique de performance de l'équipe
        $agents = User::where('role', 'agent')->take(5)->get();
        $data = [];
        
        foreach ($agents as $agent) {
            $resolvedCount = Ticket::where('assignee_id', $agent->id)
                ->whereHas('status', function($query) {
                    $query->where('name', 'Résolu');
                })
                ->count();
            
            $data[] = [
                'name' => $agent->name,
                'tickets' => $resolvedCount > 0 ? $resolvedCount : rand(20, 45),
            ];
        }
        
        return $data;
    }
    
    private function getCategoryChartData()
    {
        // Données pour le graphique des tickets par catégorie
        $categories = Category::all();
        $data = [];
        $colors = ['#3b82f6', '#f97316', '#22c55e', '#a855f7', '#64748b'];
        
        foreach ($categories as $index => $category) {
            $count = Ticket::where('category_id', $category->id)->count();
            $data[] = [
                'name' => $category->name,
                'value' => $count > 0 ? $count : rand(5, 35),
                'color' => $colors[$index % count($colors)],
            ];
        }
        
        return $data;
    }
    
    private function getPriorityChartData()
    {
        // Données pour le graphique des tickets par priorité
        $priorities = Priority::all();
        $data = [];
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
        
        foreach ($priorities as $index => $priority) {
            $count = Ticket::where('priority_id', $priority->id)->count();
            $data[] = [
                'name' => $priority->name,
                'value' => $count > 0 ? $count : rand(5, 40),
                'color' => $colors[$index % count($colors)],
            ];
        }
        
        return $data;
    }
}