<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }
        
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'API Token';
        
        return response()->json([
            'user' => $user,
            'token' => $user->createToken($deviceName)->plainTextToken,
        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Déconnecté avec succès']);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Par défaut, tous les utilisateurs enregistrés sont des utilisateurs normaux
        ]);
        
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'API Token';
        
        return response()->json([
            'user' => $user,
            'token' => $user->createToken($deviceName)->plainTextToken,
        ], 201);
    }
}