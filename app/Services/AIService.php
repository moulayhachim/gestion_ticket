<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIService
{
    /**
     * Analyser et classifier automatiquement un ticket
     */
    public function classifyTicket(Ticket $ticket)
    {
        try {
            // Utiliser un modèle NLP pour classifier le ticket
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.ai.api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.ai.endpoint') . '/classify', [
                'text' => $ticket->title . ' ' . $ticket->description,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Trouver la catégorie correspondante
                $categoryName = $data['category'] ?? null;
                $priorityLevel = $data['priority'] ?? null;
                
                if ($categoryName) {
                    $category = Category::where('name', 'like', '%' . $categoryName . '%')->first();
                    if ($category) {
                        $ticket->category_id = $category->id;
                    }
                }
                
                if ($priorityLevel) {
                    $priority = Priority::where('name', 'like', '%' . $priorityLevel . '%')->first();
                    if ($priority) {
                        $ticket->priority_id = $priority->id;
                    }
                }
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la classification IA: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir des suggestions basées sur des tickets similaires
     */
    public function getSuggestions(Ticket $ticket)
    {
        // Utiliser le cache pour éviter des appels API répétés
        $cacheKey = 'ticket_suggestions_' . $ticket->id;
        
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($ticket) {
            try {
                // Rechercher des tickets similaires dans la base de connaissances
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.ai.api_key'),
                    'Content-Type' => 'application/json',
                ])->post(config('services.ai.endpoint') . '/suggest', [
                    'text' => $ticket->title . ' ' . $ticket->description,
                    'category' => $ticket->category->name,
                ]);
                
                if ($response->successful()) {
                    $suggestions = $response->json()['suggestions'] ?? [];
                    
                    // Limiter à 3 suggestions
                    return array_slice($suggestions, 0, 3);
                }
                
                return [];
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des suggestions IA: ' . $e->getMessage());
                return [];
            }
        });
    }
    
    /**
     * Prédire le temps de résolution d'un ticket
     */
    public function predictResolutionTime(Ticket $ticket)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.ai.api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.ai.endpoint') . '/predict-time', [
                'category_id' => $ticket->category_id,
                'priority_id' => $ticket->priority_id,
                'description_length' => strlen($ticket->description),
                'has_attachments' => $ticket->attachments->count() > 0,
            ]);
            
            if ($response->successful()) {
                // Retourne le temps estimé en heures
                return $response->json()['estimated_hours'] ?? null;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la prédiction du temps de résolution: ' . $e->getMessage());
            return null;
        }
    }
}