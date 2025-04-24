<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAssetInternalCampus extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Pinjaman Aset oleh ' . $this->user->name)
            ->line($this->message)
            ->line('Silakan cek detail peminjaman pada sistem.')
            // ->action('Lihat Detail', url('/your-url-here'))
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Pengajuan Pinjaman Aset oleh ' . $this->user->name,
            'user_id' => $this->user->id,
            'message' => $this->message,
        ];
    }
}
