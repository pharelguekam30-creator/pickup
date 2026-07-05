<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NouvelleDemandeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de service')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez un nouveau client ! Voici ses informations :')
            ->line('Nom : ' . ($this->reservation->client_name ?? ''))
            ->line('Téléphone : ' . ($this->reservation->client->phone ?? ''))
            ->line('Adresse : ' . ($this->reservation->client->address ?? ''))
            ->line('Ville : ' . ($this->reservation->client->city ?? ''))
            ->line('Quartier : ' . ($this->reservation->client->quarter ?? ''))
            ->line('Service demandé : ' . ($this->reservation->service->name ?? ''))
            ->line('Date de la demande : ' . (optional($this->reservation->reservation_date)->format('Y-m-d H:i') ?? ''))
            ->action('Voir la demande', url('/dashboard'))
            ->line('Merci d’utiliser notre plateforme !');
    }
}
