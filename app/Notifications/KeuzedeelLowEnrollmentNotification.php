<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KeuzedeelLowEnrollmentNotification extends Notification
{
    use Queueable;

    protected $keuzedeel;
    protected $current;

    public function __construct($keuzedeel, $current)
    {
        $this->keuzedeel = $keuzedeel;
        $this->current = $current;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Te weinig inschrijvingen',
            'message' => "Keuzedeel '{$this->keuzedeel->naam}' heeft nu {$this->current} inschrijvingen, terwijl minimaal {$this->keuzedeel->min_deelnemers} vereist is.",
            'action_url' => route('keuzedelen.show', $this->keuzedeel->id),
            'keuzedeel_id' => $this->keuzedeel->id,
        ];
    }
}