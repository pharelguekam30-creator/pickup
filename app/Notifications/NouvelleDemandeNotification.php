<?php

namespace App\Notifications;

use App\Helpers\MailHelper;

class NouvelleDemandeNotification
{
    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function send()
    {
        $vidangeur = $this->reservation->vidangeur;
        if (!$vidangeur || !$vidangeur->email) return;

        $details = $this->reservation;
        $body = "Bonjour {$vidangeur->name},\n\n"
            . "Vous avez un nouveau client ! Voici ses informations :\n\n"
            . "Nom : " . ($details->client_name ?? '') . "\n"
            . "Telephone : " . ($details->client->phone ?? '') . "\n"
            . "Adresse : " . ($details->client->address ?? '') . "\n"
            . "Ville : " . ($details->client->city ?? '') . "\n"
            . "Quartier : " . ($details->client->quarter ?? '') . "\n"
            . "Service demande : " . ($details->service->name ?? '') . "\n"
            . "Date de la demande : " . (optional($details->reservation_date)->format('Y-m-d H:i') ?? '') . "\n\n"
            . "Connectez-vous pour voir la demande : " . url('/dashboard') . "\n\n"
            . "Merci d'utiliser notre plateforme !";

        return MailHelper::sendEmail($vidangeur->email, 'Nouvelle demande de service', $body);
    }
}
