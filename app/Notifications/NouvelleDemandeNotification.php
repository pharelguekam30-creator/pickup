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
            ->line('Téléphone : ' . (auth()->user()->phone ?? ''))
            ->line('Adresse : ' . (auth()->user()->address ?? ''))
            ->line('Ville : ' . (auth()->user()->city ?? ''))
            ->line('Quartier : ' . (auth()->user()->quarter ?? ''))
            ->line('Service demandé : ' . ($this->reservation->service->name ?? ''))
            ->line('Date de la demande : ' . ($this->reservation->date ?? ''))
            ->action('Voir la demande', url('/dashboard'))
            ->line('Merci d’utiliser notre plateforme !');
    }
}
