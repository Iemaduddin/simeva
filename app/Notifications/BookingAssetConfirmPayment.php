<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetConfirmPayment extends Notification
{
    use Queueable;

    protected $invoiceNo;
    protected $booking;
    protected $confirmBooking;
    protected $user;

    public function __construct($invoiceNo, $booking, $confirmBooking, $user)
    {
        $this->invoiceNo = $invoiceNo;
        $this->booking = $booking;
        $this->confirmBooking = $confirmBooking;
        $this->user = $user;
    }

    // 📩 Notifikasi via Email
    public function via($notifiable)
    {
        // return ['mail', 'database', 'broadcast'];
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Konfirmasi Booking #' . $this->invoiceNo)
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Booking Anda telah disetujui dan selesai.')
            ->line('Detail Booking:')
            ->line('📌 Aset: ' . $this->booking->asset->name)
            ->line('📅 Event: ' . $this->booking->event_name)
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    // 📌 Notifikasi ke Database
    public function toArray($notifiable)
    {
        if ($this->confirmBooking === 'approved') {
            return [
                'title' => 'Pembayaran dan Berkas Disetujui',
                'booking' => $this->booking,
                'user_id' => $this->user->id,
                'message' => 'Booking Anda telah disetujui dan selesai',
            ];
        } else {
            return [
                'title' => 'Pembayaran dan Berkas Ditolak' . $this->confirmBooking,
                'booking' => $this->booking,
                'user_id' => $this->user->id,
                'message' => 'Booking Anda telah disetujui dan selesai. Silahkan download surat disposisi pada profil Anda.',
            ];
        }
    }

    // 📢 Notifikasi Real-time (Broadcast)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Booking ' . $this->confirmBooking,
            'booking' => $this->booking,
            'user_id' => $this->user->id,
            'message' => 'Booking Anda telah disetujui dan selesai',
        ]);
    }
}
