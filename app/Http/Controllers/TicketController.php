<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:update,ticket')->only(['edit', 'update']);
        // $this->middleware('can:delete,ticket')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignee', 'category', 'priority', 'status']);

        if ($request->has('status')) {
            $query->whereHas('status', fn($q) => $q->where('name', $request->status));
        }

        if ($request->has('priority')) {
            $query->whereHas('priority', fn($q) => $q->where('name', $request->priority));
        }

        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('name', $request->category));
        }

        if (auth()->user()->role === 'user') {
            $query->where('user_id', auth()->id());
        }

        $tickets = $query->latest()->paginate(10);

        return view('tickets.index', [
            'tickets' => $tickets,
            'statuses' => Status::all(),
            'priorities' => Priority::all(),
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        return view('tickets.create', [
            'categories' => Category::all(),
            'priorities' => Priority::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $ticket = new Ticket($validated);
        $ticket->user_id = auth()->id();
        $ticket->status_id = Status::where('name', 'Ouvert')->first()->id;
        $ticket->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $ticket->attachments()->create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'user_id' => auth()->id(),
                ]);
            }
        }

        $agents = User::where('role', 'agent')->get();
        foreach ($agents as $agent) {
            $agent->notify(new \App\Notifications\NewTicketNotification($ticket));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'assignee', 'category', 'priority', 'status','comments','comments.user', 'attachments']);

        $agents = null;
        if (in_array(auth()->user()->role, ['admin', 'agent'])) {
            $agents = User::where('role', 'agent')->get();
        }

        return view('tickets.show', [
            'ticket' => $ticket,
            'agents' => $agents,
            'statuses' => Status::all(),
        ]);
    }

    public function edit(Ticket $ticket)
    {
        // if (Gate::denies('update-ticket', $ticket)) {
        //     abort(403, 'Access denied.');
        // }

        return view('tickets.edit', [
            'ticket' => $ticket,
            'categories' => Category::all(),
            'priorities' => Priority::all(),
            'statuses' => Status::all(),
            'agents' => User::where('role', 'agent')->get(),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        if (Gate::denies('update-ticket', $ticket)) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'status_id' => 'required|exists:statuses,id',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $ticket->status->name;

        $ticket->update($validated);

        if ($oldStatus !== 'Résolu' && $ticket->status->name === 'Résolu') {
            $ticket->resolution_time = now();
            $ticket->save();
        }

        if ($oldStatus != $ticket->status->name) {
            $ticket->user->notify(new \App\Notifications\TicketStatusChangedNotification(
                $ticket,
                $oldStatus,
                $ticket->status->name
            ));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket mis à jour avec succès.');
    }

    public function destroy(Ticket $ticket)
    {
        if (Gate::denies('delete-ticket', $ticket)) {
            abort(403, 'Access denied.');
        }

        foreach ($ticket->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket supprimé avec succès.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assignee_id' => 'required|exists:users,id',
        ]);

        $ticket->assignee_id = $validated['assignee_id'];
        $ticket->save();

        $assignee = User::find($validated['assignee_id']);
        $assignee->notify(new \App\Notifications\TicketAssignedNotification($ticket));

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket assigné avec succès.');
    }
}
