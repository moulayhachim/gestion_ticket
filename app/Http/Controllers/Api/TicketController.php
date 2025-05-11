<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Status;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignee', 'category', 'priority', 'status']);
        
        // Filtres
        if ($request->has('status')) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('name', $request->status);
            });
        }
        
        if ($request->has('priority')) {
            $query->whereHas('priority', function($q) use ($request) {
                $q->where('name', $request->priority);
            });
        }
        
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }
        
        // Pour les utilisateurs normaux, ne montrer que leurs tickets
        if (auth()->user()->role === 'user') {
            $query->where('user_id', auth()->id());
        }
        
        $tickets = $query->latest()->paginate(10);
        
        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
        ]);
        
        $ticket = new Ticket($validated);
        $ticket->user_id = auth()->id();
        $ticket->status_id = Status::where('name', 'Ouvert')->first()->id;
        $ticket->save();
        
        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        // Vérifier si l'utilisateur a le droit de voir ce ticket
        if (auth()->user()->role === 'user' && $ticket->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $ticket->load(['user', 'assignee', 'category', 'priority', 'status', 'comments.user', 'attachments']);
        
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Vérifier si l'utilisateur a le droit de modifier ce ticket
        if (auth()->user()->role === 'user' && $ticket->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'priority_id' => 'sometimes|required|exists:priorities,id',
            'status_id' => 'sometimes|required|exists:statuses,id',
            'assignee_id' => 'sometimes|nullable|exists:users,id',
        ]);
        
        $oldStatus = $ticket->status->name;
        
        $ticket->update($validated);
        
        // Si le statut a changé à "Résolu", enregistrer le temps de résolution
        if ($oldStatus !== 'Résolu' && $ticket->status->name === 'Résolu') {
            $ticket->resolution_time = now();
            $ticket->save();
        }
        
        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        // Check if the user has permission to delete the ticket
        if (auth()->user()->role !== 'admin' && $ticket->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket supprimé avec succès'], 200);
    }
    
    public function assign(Request $request, Ticket $ticket)
    {
        // Vérifier si l'utilisateur a le droit d'assigner ce ticket
        if (auth()->user()->role === 'user') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $validated = $request->validate([
            'assignee_id' => 'required|exists:users,id',
        ]);
        
        $ticket->assignee_id = $validated['assignee_id'];
        $ticket->save();
        
        return response()->json($ticket);
    }
    
    public function comments(Ticket $ticket)
    {
        // Vérifier si l'utilisateur a le droit de voir les commentaires de ce ticket
        if (auth()->user()->role === 'user' && $ticket->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $comments = $ticket->comments()->with('user')->latest()->get();
        
        return response()->json($comments);
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Check if the user has permission to delete the ticket
        // if (auth()->user()->role !== 'admin' && $ticket->user_id !== auth()->id()) {
        //     return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce ticket.');
        // }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
    }
}