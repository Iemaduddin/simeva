<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;



class BookingAssetEventConfirmed extends Notification implements ShouldQueue
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
        $approved = [];
        $rejected = [];

        foreach ($this->bookings as $booking) {
            if ($booking['status'] === 'booked') {
                $approved[] = "{$booking['asset_name']} ({$booking['usage_date']})";
            } else {
                $rejected[] = "{$booking['asset_name']} ({$booking['usage_date']}) —  {$booking['reason_rejected']}";
            }
        }

        $message = (new MailMessage)
            ->subject('Konfirmasi Booking Aset')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Berikut adalah hasil konfirmasi permintaan booking aset Anda:');

        if (!empty($approved)) {
            $message->line('')
                ->line('Booking Disetujui:')
                ->line(implode("\n", $approved));
            $message->line('')
                ->line('Silakan unduh surat peminjaman untuk aset yang disetujui dan hubungi kami jika ada pertanyaan.');
        }

        if (!empty($rejected)) {
            $message->line('')
                ->line('Booking Ditolak:')
                ->line(implode("\n", $rejected));
            $message->line('')
                ->line('Silakan perbaiki dan hubungi kami jika ada pertanyaan.');
        }


        return $message;
    }

    // 📦 Notifikasi via Database
    public function toArray($notifiable)
    {
        return [
            'title' => 'Konfirmasi Booking Aset',
            'user_id' => $this->user->id,
            'event_id' => $this->event_id,
            'total' => count($this->bookings),
            'approved' => collect($this->bookings)->where('status', 'booked')->count(),
            'rejected' => collect($this->bookings)->where('status', 'rejected_booking')->count(),
            'message' => 'Booking aset Anda telah dikonfirmasi. Cek detail untuk melihat status masing-masing aset.'
        ];
    }
}
