<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'authtest' . rand(1, 9999) . '@test.com';
$password = 'password123';

$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => $email,
    'password' => \Illuminate\Support\Facades\Hash::make($password),
    'role' => 'retailer',
    'status' => 'active'
]);

$attempt = \Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $password, 'status' => 'active']);

echo "HASH: " . $user->password . "\n";
echo "AUTH RESULT: " . ($attempt ? "SUCCESS" : "FAILED") . "\n";
