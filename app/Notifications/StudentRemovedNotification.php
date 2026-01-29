<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentRemovedNotification extends Notification
{
    use Queueable;

    protected $keuzedeelName;
    protected $countRemoved;
    protected $keuzedeelId;

    public function __construct($keuzedeelName, $countRemoved = 1, $keuzedeelId = null)
    {
        $this->keuzedeelName = $keuzedeelName;
        $this->countRemoved = $countRemoved;
        $this->keuzedeelId = $keuzedeelId;
    }

    public function via(object $notifiable): array
    {
        // Only database channel for in-app notifications
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $text = $this->countRemoved > 1 ? "Alle {$this->countRemoved} inschrijvingen voor '{$this->keuzedeelName}' zijn verwijderd." : "Je inschrijving voor '{$this->keuzedeelName}' is verwijderd.";
        return (new MailMessage)
            ->subject('Verwijderd uit keuzedeel')
            ->greeting("Hallo {$notifiable->name},")
            ->line($text);
    }

    public function toArray(object $notifiable): array
    {
        $data = [
            'title' => 'Verwijderd uit keuzedeel',
            'message' => $this->countRemoved > 1 ? "Alle {$this->countRemoved} inschrijvingen voor '{$this->keuzedeelName}' zijn verwijderd." : "Je inschrijving voor '{$this->keuzedeelName}' is verwijderd.",
        ];

        if ($this->keuzedeelId) {
            $data['action_url'] = route('keuzedelen.show', $this->keuzedeelId);
            $data['keuzedeel_id'] = $this->keuzedeelId;
        }

        return $data;
    }
}
