<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class MailHelper
{
    public static function getApiKey()
    {
        $key = env('BREVO_API_KEY');
        if (!$key) {
            $file = storage_path('app/brevo_key.txt');
            if (file_exists($file)) {
                $key = trim(file_get_contents($file));
            }
        }
        if (!$key) {
            try {
                $dbKey = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'brevo_api_key')->value('value');
                if ($dbKey) $key = $dbKey;
            } catch (\Exception $e) {}
        }
        return $key ?: null;
    }

    public static function sendEmail($to, $subject, $body, &$log = null)
    {
        $apiKey = self::getApiKey();
        if (!$apiKey) {
            $msg = 'BREVO_API_KEY not set';
            Log::error($msg);
            if ($log !== null) $log = $msg;
            return false;
        }

        $data = [
            'sender' => ['name' => env('MAIL_FROM_NAME', 'PICKUP'), 'email' => env('MAIL_FROM_ADDRESS', 'pharelboland@gmail.com')],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'htmlContent' => nl2br($body),
        ];

        try {
            if (!function_exists('curl_init')) {
                $msg = 'curl not available';
                Log::error($msg);
                if ($log !== null) $log = $msg;
                return false;
            }
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
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 300) {
                Log::info("Email sent to $to via Brevo API");
                if ($log !== null) $log = "HTTP $httpCode OK";
                return true;
            }

            $msg = "Brevo API error ($httpCode): $response";
            if ($error) $msg .= " | curl: $error";
            Log::error($msg);
            if ($log !== null) $log = $msg;
            return false;
        } catch (\Exception $e) {
            $msg = 'Brevo API exception: ' . $e->getMessage();
            Log::error($msg);
            if ($log !== null) $log = $msg;
            return false;
        }
    }
}
