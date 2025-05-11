<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Ticket::class => TicketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a gate for updating tickets
        Gate::define('update-ticket', function (User $user, Ticket $ticket): bool {
            // Allow administrators to update any ticket
            if ($user->role === 'admin') {
                return true;
            }

            // Allow agents to update tickets assigned to them
            if ($user->role === 'agent' && $ticket->assignee_id === $user->id) {
                return true;
            }

            // Allow users to update their own tickets
            return $ticket->user_id === $user->id;
        });

        // Define a gate for deleting tickets
        Gate::define('delete-ticket', function (User $user, Ticket $ticket): bool {
            return $user->role === 'admin';
        });
    }
}