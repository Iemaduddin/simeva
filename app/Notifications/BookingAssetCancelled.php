<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetCancelled extends Notification
{
    use Queueable;


    protected $booking;
    protected $user;
    protected $userBooking;

    public function __construct($booking, $user, $userBooking)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->userBooking = $userBooking;
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
            ->subject('Konfirmasi Booking #' . $this->booking->booking_number)
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Booking telah dibatalkan oleh ' . $this->user->name)
            ->line('Detail Booking:')
            ->line('📌 Aset: ' . $this->booking->asset->name)
            ->line('📅 Event: ' . $this->booking->event_name)
            ->line('Terima kasih telah menggunakan layanan kami!');
    }
    // 📌 Notifikasi ke Database
    public function toArray($notifiable)
    {
        // Ambil nama role pengguna (bukan objek)
        $role = $this->user->getRoleNames()->first();

        // Inisialisasi pesan default
        $message = 'Booking aset ' . $this->booking->asset->name . ' telah dibatalkan.';

        // Cek status booking
        if ($role === 'Tenant') {
            // Dibatalkan oleh Tenant (notifikasi ke Admin Jurusan/UPT PU)
            $message = 'Booking aset ' . $this->booking->asset->name . ' telah dibatalkan oleh ' . $this->user->name .
                ' dengan alasan: "' . $this->booking->reason . '"';

            // Dibatalkan oleh UPT PU/Admin Jurusan (notifikasi ke Tenant)
        } elseif ($role !== 'Tenant') {
            if (
                $this->booking->status === 'submission_dp_payment' || $this->booking->status === 'submission_full_payment'
                || $this->booking->status === 'approved_dp_payment' || $this->booking->status === 'approved_full_payment'
            ) {
                $message = 'Booking aset ' . $this->booking->asset->name . ' telah dibatalkan oleh ' . $this->user->name .
                    ' dengan alasan: "' . $this->booking->reason .
                    '". Uang Anda akan dikembalikan 100%. Anda akan segera kami hubungi.';
            } elseif ($this->booking->status === 'booked') {
                $message = 'Booking aset ' . $this->booking->asset->name . ' telah dibatalkan oleh ' . $this->user->name .
                    ' dengan alasan: "' . $this->booking->reason .
                    '". Selengkapnya dapat menghubungi Admin UPT PU.';
            }
        }

        return [
            'title' => 'Booking Aset Dibatalkan!',
            'booking' => $this->booking,
            'user_id' => $this->userBooking->id,
            'message' => $message,
        ];
    }



    // 📢 Notifikasi Real-time (Broadcast)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Booking Aset Dibatalkan!',
            'booking' => $this->booking,
            'user_id' => $this->userBooking->id,
            'message' => 'Booking telah dibatalkan oleh ' . $this->user->name,
        ]);
    }
}
