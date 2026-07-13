<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Helpers\MailHelper;

$email = getenv('MAIL_FROM_ADDRESS') ?: 'pharelboland@gmail.com';
$sent = MailHelper::sendEmail($email, 'Test PICKUP (script)', 'Test via API Brevo');
echo $sent ? "Email envoye avec succes a $email\n" : "ERREUR: Echec envoi\n";
