<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupRailwayEnv extends Command
{
    protected $signature = 'railway:setup-env {--brevo-key=} {--mailer=log}';
    protected $description = 'Set Railway environment variables via Railway API';

    public function handle()
    {
        $token = env('RAILWAY_API_TOKEN');
        $projectId = env('RAILWAY_PROJECT_ID');
        $environmentId = env('RAILWAY_ENVIRONMENT_ID');

        if (!$token || !$projectId || !$environmentId) {
            $this->error('Not running on Railway or missing RAILWAY_* env vars');
            return 1;
        }

        $vars = [];
        $brevoKey = $this->option('brevo-key');
        if ($brevoKey) {
            $vars['BREVO_API_KEY'] = $brevoKey;
        }
        if ($this->option('mailer')) {
            $vars['MAIL_MAILER'] = $this->option('mailer');
        }

        if (empty($vars)) {
            $this->info('No variables to set. Use --brevo-key=... --mailer=...');
            return 0;
        }

        foreach ($vars as $name => $value) {
            $query = <<<GRAPHQL
mutation {
  variableUpsert(
    projectId: "$projectId"
    environmentId: "$environmentId"
    name: "$name"
    value: "$value"
  ) { name }
}
GRAPHQL;

            $ch = curl_init('https://api.railway.app/graphql/v2');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ],
                CURLOPT_POSTFIELDS => json_encode(['query' => $query]),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $this->info("Set $name");
            } else {
                $this->error("Failed to set $name: $httpCode $response");
            }
        }

        $this->warn('Redeploy for changes to take effect');
        return 0;
    }
}
