<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetPayAndCompleteFile extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $user;

    public function __construct($booking,  $user)
    {
        $this->booking = $booking;
        $this->user = $user;
    }

    // 📩 Notifikasi via Email
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }


    // 📌 Notifikasi ke Database
    public function toArray($notifiable)
    {
        return [
            'title' => 'Bukti Pembayaran dan Formulir Peminjaman',
            'booking' => $this->booking,
            'user_id' => $this->user->id,
            'message' => $this->user->name . ' telah menggunggah bukti pembayaran dan formulir peminjaman aset ' . $this->booking->asset->name,
        ];
    }

    // 📢 Notifikasi Real-time (Broadcast)
    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'title' => 'Bukti Pembayaran dan Formulir Peminjaman',
    //         'booking' => $this->booking,
    //         'user_id' => $this->user->id,
    //         'message' => $this->user->name . ' telah menggunggah bukti pembayaran dan formulir peminjaman aset' . $this->booking->asset->name,
    //     ]);
    // }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bukti Pembayaran dan Formulir Peminjaman')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line($this->user->name . ' telah menggunggah bukti pembayaran dan formulir peminjaman aset ' . $this->booking->asset->name)
            ->line('Segera Konfirmasi!');
    }
}
