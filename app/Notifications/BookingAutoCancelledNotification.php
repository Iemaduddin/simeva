<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingAutoCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Peminjaman Dibatalkan')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Peminjaman Anda pada tanggal ' . $this->booking->usage_date_start->format('d M Y') . ' telah dibatalkan.')
            ->line('Hal ini karena Anda tidak mengonfirmasi dalam 4 hari setelah pemesanan.')
            ->line('Jika ini adalah kesalahan, silakan hubungi admin segera.')
            ->line('Terima kasih.');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        $user = User::role('UPT PU')->first();
        return [
            'title' => 'Peminjaman Dibatalkan Otomatis',
            'message' => 'Peminjaman Anda telah dibatalkan karena tidak dikonfirmasi dalam 4 hari.',
            'user_id' => $user->id,
            'booking' => $this->booking,
        ];
    }
}
