<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application (console kernel) so service providers are loaded
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = $argv[1] ?? 'maillogtest2@example.com';

$user = User::where('email', $email)->first();

if (! $user) {
    $user = User::create([
        'name' => 'MailLogScript',
        'email' => $email,
        'password' => Hash::make('password'),
    ]);
    echo "Created user: {$email}\n";
} else {
    $user->email_verified_at = null;
    $user->save();
    echo "Using existing user: {$email}\n";
}

$user->sendEmailVerificationNotification();

echo "Verification notification dispatched for {$email}\n";

return 0;
