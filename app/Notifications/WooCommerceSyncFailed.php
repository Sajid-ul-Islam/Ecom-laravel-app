<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WooCommerceSyncFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public string $errorMessage,
        public array $context = []
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->error()
            ->subject("[Critical] WooCommerce Sync Failure: {$this->title}")
            ->greeting('WooCommerce Sync Error Alert')
            ->line("A critical failure occurred during WooCommerce data synchronization:")
            ->line("**Title:** {$this->title}")
            ->line("**Error:** {$this->errorMessage}");

        if (! empty($this->context)) {
            $mail->line('**Context:**')
                ->line(json_encode($this->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return $mail->line('Please check the application logs and dead-letter failure queue for more details.');
    }
}
