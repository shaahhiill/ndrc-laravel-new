<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'janatha@gmail.com')->first();
if ($user) {
    echo "USER CREATED AT: " . $user->created_at . "\n";
    echo "PASSWORD: " . $user->password . "\n";
} else {
    echo "USER NOT FOUND.\n";
}
