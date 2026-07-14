<?php

// BIR MARTALIK SETUP SKRIPTI — ishlatib bo'lgach serverdan O'CHIRIB TASHLANG.
// Foydalanish: https://mohirqollar.uz/setup.php?secret=SIZNING_WEBHOOK_SECRET&action=migrate
// action: migrate | storage-link | set-webhook | webhook-info

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

$secret = $_GET['secret'] ?? '';
$expected = (string) config('services.telegram.webhook_secret');

if ($expected === '' || !hash_equals($expected, (string) $secret)) {
    http_response_code(403);
    exit('Forbidden');
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'migrate':
        Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo Illuminate\Support\Facades\Artisan::output();
        break;

    case 'storage-link':
        Illuminate\Support\Facades\Artisan::call('storage:link');
        echo Illuminate\Support\Facades\Artisan::output();
        break;

    case 'set-webhook':
        Illuminate\Support\Facades\Artisan::call('app:telegram-set-webhook');
        echo Illuminate\Support\Facades\Artisan::output();
        break;

    case 'webhook-info':
        $token = config('services.telegram.bot_token');
        echo file_get_contents("https://api.telegram.org/bot{$token}/getWebhookInfo");
        break;

    case 'config-clear':
        Illuminate\Support\Facades\Artisan::call('config:clear');
        echo Illuminate\Support\Facades\Artisan::output();
        break;

    case 'cache-clear':
        Illuminate\Support\Facades\Artisan::call('cache:clear');
        echo Illuminate\Support\Facades\Artisan::output();
        break;

    default:
        echo "Foydalanish: ?secret=SECRET&action=migrate|storage-link|set-webhook|webhook-info|config-clear|cache-clear";
}
