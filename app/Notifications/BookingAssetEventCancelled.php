<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetEventCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bookings;
    protected $user;
    protected $event_id;

    public function __construct(array $bookings, $user, $event_id)
    {
        $this->bookings = $bookings;
        $this->user = $user;
        $this->event_id = $event_id;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // 📩 Notifikasi via Email
    public function toMail($notifiable)
    {
        $cancelled = [];

        foreach ($this->bookings as $booking) {
            if ($booking['status'] === 'cancelled') {
                $cancelled[] = "{$booking['asset_name']} ({$booking['usage_date']}) — {$booking['reason_cancelled']}";
            }
        }

        $message = (new MailMessage)
            ->subject('Booking Aset Dibatalkan')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Berikut adalah hasil konfirmasi permintaan booking aset Anda:');


        if (!empty($cancelled)) {
            $message->line('')
                ->line('Booking Dibatalkan:')
                ->line(implode("\n", $cancelled));
        }

        $message->line('')
            ->line('Silakan hubungi kami untuk detailnya.');

        return $message;
    }

    // 📦 Notifikasi via Database
    public function toArray($notifiable)
    {
        return [
            'title' => 'Booking Aset Dibatalkan',
            'user_id' => $this->user->id,
            'event_id' => $this->event_id,
            'cancelled' => collect($this->bookings)->where('status', 'cancelled')->count(),
            'message' => 'Booking aset Anda dibatalkan. Cek detail untuk melihat peminjaman aset yang dibatalkan.'
        ];
    }
}
