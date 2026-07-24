<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SendVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:send-verification {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create (or reuse) a user and send the email verification (uses log mailer)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Test Verify',
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            $this->info("Created user: {$email} (password: password)");
        } else {
            // make sure it's unverified so the notification is relevant
            $user->email_verified_at = null;
            $user->save();

            $this->info("Using existing user: {$email}");
        }

        // Send the verification notification (will use MAIL_MAILER=log and write to storage/logs)
        $user->sendEmailVerificationNotification();

        $this->info("Verification notification dispatched for {$email}");

        return 0;
    }
}
