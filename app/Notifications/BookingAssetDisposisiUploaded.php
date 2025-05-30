<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAssetDisposisiUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $uploader;
    protected $assetName;
    protected $event_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($uploader, $assetName, $event_id)
    {
        $this->uploader = $uploader;
        $this->assetName = $assetName;
        $this->event_id = $event_id;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Surat Disposisi Sudah Tersedia')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line("Surat Disposisi untuk aset *{$this->assetName}* telah diunggah oleh {$this->uploader->name}.")
            ->line("Silakan unduh pada sistem dan tindak lanjuti sesuai prosedur.");
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Surat Disposisi Sudah Tersedia',
            'user_id' => $this->uploader->id,
            'event_id' => $this->event_id,
            'message' => "Surat disposisi untuk aset '{$this->assetName}' telah diunggah oleh {$this->uploader->name}. Silahkan unduh pada sistem!.",
        ];
    }
}
