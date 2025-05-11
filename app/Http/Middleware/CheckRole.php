<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Si aucun rôle n'est spécifié, continuer
        if (empty($roles)) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a l'un des rôles spécifiés
        foreach ($roles as $role) {
            // Vérifier directement le champ role
            if ($request->user()->role === $role) {
                return $next($request);
            }
        }

        // L'utilisateur n'a pas les rôles requis
        abort(403, 'Accès non autorisé.');
    }
}