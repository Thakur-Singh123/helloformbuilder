<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test 
                            {email? : Email address to send the test to (default: from config)}';

    protected $description = 'Send a test email to verify SMTP and settings are working';

    public function handle(): int
    {
        $to = $this->argument('email') ?: config('mail.from.address');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        try {
            Mail::raw(
                "This is a test email from Lead Matrix.\n\nIf you received this, your mail configuration is working correctly.\n\nSent at: " . now()->format('Y-m-d H:i:s'),
                function ($message) use ($to, $fromAddress, $fromName) {
                    $message->to($to)
                        ->subject('Lead Matrix – Test Email')
                        ->from($fromAddress, $fromName);
                }
            );
            $this->info("Test email sent successfully to: {$to}");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
            if ($this->output->isVerbose()) {
                $this->line($e->getTraceAsString());
            }
            return self::FAILURE;
        }
    }
}
