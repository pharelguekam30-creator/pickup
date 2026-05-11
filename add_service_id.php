<?php

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement('ALTER TABLE avis ADD COLUMN service_id INT UNSIGNED AFTER user_id');
    echo 'Column service_id added successfully' . PHP_EOL;
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo 'Column service_id already exists' . PHP_EOL;
    } else {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
}
