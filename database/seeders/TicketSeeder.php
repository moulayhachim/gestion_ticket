<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les IDs nécessaires
        $userIds = User::where('role', 'user')->pluck('id')->toArray();
        $agentIds = User::where('role', 'agent')->pluck('id')->toArray();
        $statusIds = Status::pluck('id', 'name')->toArray();
        $priorityIds = Priority::pluck('id', 'name')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Créer 20 tickets
        for ($i = 0; $i < 20; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $assigneeId = rand(0, 1) ? $agentIds[array_rand($agentIds)] : null;
            
            // Déterminer le statut aléatoirement
            $statusName = array_rand([
                'Ouvert' => true,
                'En cours' => true,
                'Résolu' => true,
                'Fermé' => true,
            ]);
            
            $statusId = $statusIds[$statusName];
            
            // Déterminer la priorité aléatoirement
            $priorityName = array_rand([
                'Urgent' => true,
                'Élevé' => true,
                'Moyen' => true,
                'Faible' => true,
            ]);
            
            $priorityId = $priorityIds[$priorityName];
            
            // Créer le ticket
            $ticket = Ticket::create([
                'title' => 'Ticket de test #' . ($i + 1),
                'description' => 'Ceci est une description de test pour le ticket #' . ($i + 1) . '. Ce ticket a été généré automatiquement par le seeder.',
                'user_id' => $userId,
                'assignee_id' => $assigneeId,
                'status_id' => $statusId,
                'priority_id' => $priorityId,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
            
            // Si le ticket est résolu, ajouter une date de résolution
            if ($statusName === 'Résolu' || $statusName === 'Fermé') {
                $ticket->resolution_time = $ticket->created_at->addHours(rand(1, 48));
                $ticket->save();
            }
            
            // Ajouter des commentaires au ticket
            $commentCount = rand(0, 5);
            for ($j = 0; $j < $commentCount; $j++) {
                $commentUserId = rand(0, 1) ? $userId : ($assigneeId ?? $agentIds[array_rand($agentIds)]);
                
                Comment::create([
                    'content' => 'Ceci est un commentaire de test #' . ($j + 1) . ' pour le ticket #' . ($i + 1),
                    'ticket_id' => $ticket->id,
                    'user_id' => $commentUserId,
                    'created_at' => $ticket->created_at->addHours(rand(1, 24)),
                ]);
            }
        }
    }
}