<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KeuzedeelUpdatedNotification extends Notification
{
    use Queueable;

    protected $keuzedeel;
    protected $changes;

    public function __construct($keuzedeel, $changes = [])
    {
        $this->keuzedeel = $keuzedeel;
        $this->changes = $changes;
    }

    public function via(object $notifiable): array
    {
        // store in database and attempt to send mail if mail is configured
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('keuzedelen.show', $this->keuzedeel);
        $mail = (new MailMessage)
            ->subject("Keuzedeel gewijzigd: {$this->keuzedeel->naam}")
            ->greeting("Hallo {$notifiable->name},")
            ->line("Het keuzedeel '{$this->keuzedeel->naam}' is bijgewerkt.")
            ->action('Bekijk keuzedeel', $url);

        if (!empty($this->changes)) {
            $mail->line('Gewijzigde velden: ' . implode(', ', array_keys($this->changes)));
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'keuzedeel_id' => $this->keuzedeel->id,
            'title' => 'Keuzedeel bijgewerkt',
            'message' => "Het keuzedeel '{$this->keuzedeel->naam}' is bijgewerkt.",
            'changes' => $this->changes,
            'action_url' => route('keuzedelen.show', $this->keuzedeel),
        ];
    }
}
