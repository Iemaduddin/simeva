<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAsset extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $user;

    public function __construct($booking, $user)
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
            'title' => 'Booking Aset Diajukan!',
            'booking' => $this->booking,
            'user_id' => $this->user->id,
            'message' => $this->user->name . ' mengajukan booking untuk aset ' . $this->booking->asset->name,
        ];
    }

    // 📢 Notifikasi Real-time (Broadcast)
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Aset Diajukan!')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line($this->user->name . ' mengajukan booking untuk aset ' . $this->booking->asset->name)
            ->line('Segera Konfirmasi!');
    }
}
