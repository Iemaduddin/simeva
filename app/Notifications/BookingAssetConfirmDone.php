<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAssetConfirmDone extends Notification implements ShouldQueue
{
    use Queueable;

    protected $assetName;
    protected $approver;
    protected $event_id;

    public function __construct($assetName, $approver, $event_id)
    {
        $this->assetName = $assetName;
        $this->approver = $approver;
        $this->event_id = $event_id;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pemesanan Aset Disetujui')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line("Permohonan peminjaman aset *{$this->assetName}* telah disetujui oleh {$this->approver->name}.")
            ->line('Silakan cek sistem untuk informasi selengkapnya.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pemesanan Aset Disetujui',
            'user_id' => $notifiable->id,
            'event_id' => $this->event_id,
            'message' => "Pemesanan aset '{$this->assetName}' telah disetujui oleh {$this->approver->name}.",
        ];
    }
}
