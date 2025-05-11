<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Ticket $ticket, $oldStatus, $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Statut du ticket modifié : ' . $this->ticket->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut d\'un ticket a été modifié.')
            ->line('Titre : ' . $this->ticket->title)
            ->line('Ancien statut : ' . $this->oldStatus)
            ->line('Nouveau statut : ' . $this->newStatus)
            ->action('Voir le ticket', url('/tickets/' . $this->ticket->id))
            ->line('Merci d\'utiliser notre système de gestion de tickets !');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'message' => 'Le statut du ticket a été modifié de ' . $this->oldStatus . ' à ' . $this->newStatus,
        ];
    }
}