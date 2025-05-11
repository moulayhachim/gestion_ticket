<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific ticket.
     */
    public function index(Request $request)
    {
        $ticketId = $request->query('ticket_id');
        $comments = Comment::where('ticket_id', $ticketId)->with('user')->get();

        return response()->json($comments);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'is_internal' => $request->input('is_internal', false),
        ]);

        return view('tickets.show', [
            'ticket' => Ticket::with(['comments.user'])->findOrFail($request->ticket_id),
            'comments' => $comment,
        ]);
    }

    /**
     * Display the specified comment.
     */
    public function show($id)
    {
        $comment = Comment::with('user')->findOrFail($id);

        return response()->json($comment);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json($comment);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}