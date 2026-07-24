<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    
    {
    
        if (app()->environment('production') || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        if (config('app.env') === 'production' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        URL::forceScheme('https');
    }
        // If MAIL_LOG=true in .env then write a copy of every sent mail to the
        // application log. This lets you keep MAIL_MAILER=smtp for actual
        // delivery while also keeping a local log copy for debugging.
        if (env('MAIL_LOG', false)) {
            Event::listen(MessageSent::class, function (MessageSent $event) {
                try {
                    $message = $event->message;

                    // Recipients
                    $to = [];
                    if (method_exists($message, 'getTo')) {
                        $recipients = $message->getTo();
                        if (is_array($recipients)) {
                            foreach ($recipients as $r) {
                                if (is_string($r)) {
                                    $to[] = $r;
                                } elseif (is_object($r)) {
                                    if (method_exists($r, 'getAddress')) {
                                        $to[] = $r->getAddress();
                                    } elseif (method_exists($r, '__toString')) {
                                        $to[] = (string) $r;
                                    } else {
                                        $to[] = json_encode($r);
                                    }
                                }
                            }
                        } elseif ($recipients) {
                            $to[] = is_string($recipients) ? $recipients : (method_exists($recipients, '__toString') ? (string) $recipients : json_encode($recipients));
                        }
                    }

                    // Subject
                    $subject = method_exists($message, 'getSubject') ? $message->getSubject() : '';

                    // Prefer a full string representation when available to avoid
                    // dealing with many different header/body object types.
                    $raw = '';
                    if (method_exists($message, 'toString')) {
                        $raw = $message->toString();
                    } elseif (method_exists($message, '__toString')) {
                        $raw = (string) $message;
                    } else {
                        // Fall back to best-effort pieces
                        $rawBody = '';
                        if (method_exists($message, 'getTextBody') && $message->getTextBody()) {
                            $rawBody = $message->getTextBody();
                        } elseif (method_exists($message, 'getHtmlBody') && $message->getHtmlBody()) {
                            $rawBody = $message->getHtmlBody();
                        } elseif (method_exists($message, 'getBody')) {
                            $b = $message->getBody();
                            $rawBody = is_string($b) ? $b : (method_exists($b, '__toString') ? (string) $b : json_encode($b));
                        }

                        $raw = "Subject: {$subject}\n" . ($to ? "To: " . implode(',', $to) . "\n" : '') . "\n" . $rawBody;
                    }

                    Log::channel(config('logging.default'))->debug("[mail-log] " . $raw);
                } catch (\Throwable $e) {
                    // Don't fail the request if logging mail fails
                    Log::warning('Failed to write mail to log: ' . $e->getMessage());
                }
            });
        }
    }
}
