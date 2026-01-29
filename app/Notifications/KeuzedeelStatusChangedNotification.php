<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KeuzedeelStatusChangedNotification extends Notification
{
    use Queueable;

    protected $keuzedeel;

    public function __construct($keuzedeel)
    {
        $this->keuzedeel = $keuzedeel;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('keuzedelen.show', $this->keuzedeel);
        $status = $this->keuzedeel->is_active ? 'geactiveerd' : 'gedeactiveerd';
        return (new MailMessage)
            ->subject("Keuzedeel $status: {$this->keuzedeel->naam}")
            ->greeting("Hallo {$notifiable->name},")
            ->line("Het keuzedeel '{$this->keuzedeel->naam}' is $status.")
            ->action('Bekijk keuzedeel', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'keuzedeel_id' => $this->keuzedeel->id,
            'title' => 'Keuzedeel status gewijzigd',
            'message' => "Het keuzedeel '{$this->keuzedeel->naam}' is " . ($this->keuzedeel->is_active ? 'geactiveerd' : 'gedeactiveerd') . ".",
            'action_url' => route('keuzedelen.show', $this->keuzedeel),
