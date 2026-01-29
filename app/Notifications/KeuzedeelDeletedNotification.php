<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KeuzedeelDeletedNotification extends Notification
{
    use Queueable;

    protected $keuzedeelName;

    public function __construct($keuzedeelName)
    {
        $this->keuzedeelName = $keuzedeelName;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Keuzedeel verwijderd: {$this->keuzedeelName}")
            ->greeting("Hallo {$notifiable->name},")
            ->line("Het keuzedeel '{$this->keuzedeelName}' is verwijderd door de administratie.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Keuzedeel verwijderd',
            'message' => "Het keuzedeel '{$this->keuzedeelName}' is verwijderd.",
