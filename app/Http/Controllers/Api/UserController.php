<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $users = User::paginate(10);
        
        return response()->json($users);
    }

    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,agent,user',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        // Vérifier si l'utilisateur est un administrateur ou s'il consulte son propre profil
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        // Vérifier si l'utilisateur est un administrateur ou s'il modifie son propre profil
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|string|in:admin,agent,user',
        ]);
        
        // Seul un administrateur peut changer le rôle
        if (isset($validated['role']) && auth()->user()->role !== 'admin') {
            unset($validated['role']);
        }
        
        // Hacher le mot de passe s'il est fourni
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        $user->update($validated);
        
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        $user->delete();
        
        return response()->json(null, 204);
    }
}