<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class MailHelper
{
    public static function sendEmail($to, $subject, $body)
    {
        $apiKey = env('BREVO_API_KEY');
        if (!$apiKey) {
            $file = storage_path('app/brevo_key.txt');
            if (file_exists($file)) {
                $apiKey = trim(file_get_contents($file));
            }
        }
        if (!$apiKey) {
            Log::error('BREVO_API_KEY not set');
            return false;
        }

        $data = [
            'sender' => ['name' => env('MAIL_FROM_NAME', 'PICKUP'), 'email' => env('MAIL_FROM_ADDRESS', 'pharelboland@gmail.com')],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'htmlContent' => nl2br($body),
        ];

        try {
            $ch = curl_init('https://api.brevo.com/v3/smtp/email');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'api-key: ' . $apiKey,
                ],
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 300) {
                Log::info("Email sent to $to via Brevo API");
                return true;
            }

            Log::error("Brevo API error ($httpCode): $response");
            return false;
        } catch (\Exception $e) {
            Log::error('Brevo API exception: ' . $e->getMessage());
            return false;
        }
    }
}
