<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get(['id', 'name', 'email', 'created_at']);
foreach($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Created: {$user->created_at}\n";
}
