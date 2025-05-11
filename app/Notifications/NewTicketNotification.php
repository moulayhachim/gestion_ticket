<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau ticket créé : ' . $this->ticket->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouveau ticket a été créé et nécessite votre attention.')
            ->line('Titre : ' . $this->ticket->title)
            ->line('Priorité : ' . $this->ticket->priority->name)
            ->line('Catégorie : ' . $this->ticket->category->name)
            ->action('Voir le ticket', url('/tickets/' . $this->ticket->id))
            ->line('Merci d\'utiliser notre système de gestion de tickets !');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'message' => 'Un nouveau ticket a été créé',
            'priority' => $this->ticket->priority->name,
        ];
    }
}