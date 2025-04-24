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

    public function __construct(array $bookings, $user)
    {
        $this->bookings = $bookings;
        $this->user = $user;
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
            if ($booking['status'] === 'rejected') {
                $cancelled[] = "📌 {$booking['asset_name']} ({$booking['usage_date']}) — 📝 {$booking['reason_cancelled']}";
            }
        }

        $message = (new MailMessage)
            ->subject('Konfirmasi Booking Aset')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Berikut adalah hasil konfirmasi permintaan booking aset Anda:');


        if (!empty($cancelled)) {
            $message->line('')
                ->line('❌ Booking Dibatalkan:')
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
            'cancelled' => collect($this->bookings)->where('status', 'cancelled')->count(),
            'message' => 'Booking aset Anda dibatalkan. Cek detail untuk melihat status masing-masing aset.'
        ];
    }
}