<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetEventUploadDoc extends Notification implements ShouldQueue
{
    use Queueable;

    protected $organizer;


    public function __construct($organizer)
    {
        $this->organizer = $organizer;
    }
    public function via($notifiable)

    {
        return ['database'];
    }
    // 📩 Notifikasi via Email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Surat Peminjaman telah diunggah!')
            ->line($this->organizer->name . ' telah mengunggah surat peminjaman yang telah selesai')
            ->line('Segera konfirmasi, terima kasih!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Surat Peminjaman telah diunggah!',
            'message' => $this->organizer->name . ' telah mengunggah surat peminjaman yang telah selesai',
        ];
    }
}
