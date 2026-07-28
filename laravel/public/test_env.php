<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo 'USER: ' . env('MQTT_USERNAME') . PHP_EOL;
echo 'PASS: ' . env('MQTT_PASSWORD') . PHP_EOL;
