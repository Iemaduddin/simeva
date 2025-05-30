<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetInternalConfirmDocument extends Notification implements ShouldQueue
{
    use Queueable;
    protected $userSender;
    protected $event_id;
    protected $isApproved;
    protected $assetNames;
    protected $reason;

    public function __construct($userSender, $event_id, bool $isApproved, array $assetNames, ?string $reason = null)
    {
        $this->userSender = $userSender;
        $this->event_id = $event_id;
        $this->isApproved = $isApproved;
        $this->assetNames = $assetNames;
        $this->reason = $reason;
    }

    // 📩 Notifikasi via Email
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->isApproved
                ? 'Surat Peminjaman Disetujui'
                : 'Surat Peminjaman Ditolak')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line($this->generateMessage());

        return $message;
    }
    // 📌 Notifikasi ke Database
    public function toArray($notifiable)
    {
        return [
            'title' => $this->isApproved
                ? 'Surat Peminjaman Disetujui'
                : 'Surat Peminjaman Ditolak',
            'user_id' => $this->userSender,
            'event_id' => $this->event_id,
            'message' => $this->generateMessage(),
        ];
    }
    protected function generateMessage()
    {
        $assetsList = collect($this->assetNames)
            ->map(fn($name) => "• {$name}")
            ->implode("\n");

        if ($this->isApproved) {
            return "Surat peminjaman aset yang Anda ajukan telah disetujui untuk aset berikut:\n{$assetsList}";
        }

        return "Mohon maaf, dokumen peminjaman aset yang Anda ajukan ditolak untuk aset berikut:\n{$assetsList}\n\nAlasan penolakan: {$this->reason}";
    }
}
