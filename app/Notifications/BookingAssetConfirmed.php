<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingAssetConfirmed extends Notification
{
    use Queueable;

    protected $booking;
    protected $confirmBooking;
    protected $user;
    protected $vaNumber;
    protected $paymentAmount;
    protected $vaExpiredDate;

    public function __construct($booking, $confirmBooking, $user, $vaNumber, $paymentAmount,  $vaExpiredDate)
    {
        $this->booking = $booking;
        $this->confirmBooking = $confirmBooking;
        $this->user = $user;
        $this->vaNumber = $vaNumber;
        $this->paymentAmount = $paymentAmount;
        $this->vaExpiredDate = $vaExpiredDate;
    }
    public function via($notifiable)

    {

        return ['mail', 'database', 'broadcast'];
    }
    // 📩 Notifikasi via Email
    public function toMail($notifiable)
    {
        if ($this->confirmBooking === 'approved') {
            return (new MailMessage)
                ->subject('Konfirmasi Booking #' . $this->booking->id)
                ->greeting('Halo, ' . $this->user->name . '!')
                ->line('Booking Anda telah disetujui.')
                ->line('Detail Booking:')
                ->line('📌 Aset: ' . $this->booking->asset->name)
                ->line('📅 Event: ' . $this->booking->event_name)
                ->line('💰 Total Harga: Rp ' . number_format($this->paymentAmount, 2))
                ->line('🏦 Virtual Account: ' . $this->vaNumber)
                ->line('⏳ Berlaku Sampai: ' . $this->vaExpiredDate)
                ->action('Bayar Sekarang', url('/payment?booking_id=' . $this->booking->id))
                ->line('Terima kasih telah menggunakan layanan kami!');
        } else {
            return (new MailMessage)
                ->subject('Penolakan Booking #' . $this->booking->id)
                ->greeting('Halo, ' . $this->user->name . '!')
                ->line('Mohon maaf, booking Anda tidak dapat disetujui.')
                ->line('Detail Booking:')
                ->line('📌 Aset: ' . $this->booking->asset->name)
                ->line('📅 Event: ' . $this->booking->event_name)
                ->line('❌ Status: Ditolak')
                ->line('📝 Alasan Penolakan: ' . $this->booking->reason_rejected)
                ->action('Buat Booking Baru', url('/assets'))
                ->line('Silakan hubungi kami jika membutuhkan bantuan lebih lanjut.')
                ->line('Terima kasih atas pengertiannya.');
        }
    }

    public function toArray($notifiable)
    {
        if ($this->confirmBooking === 'approved') {
            return [
                'title' => 'Booking Disetujui',
                'booking' => $this->booking,
                'user_id' => $this->user->id,
                'message' => 'Booking Anda telah dikonfirmasi. Silakan lakukan pembayaran sebelum ' . $this->vaExpiredDate,
                'va_number' => $this->vaNumber,
                'amount' => $this->paymentAmount,
                'expired_date' => $this->vaExpiredDate
            ];
        } else {
            return [
                'title' => 'Booking Ditolak',
                'booking' => $this->booking,
                'user_id' => $this->user->id,
                'message' => 'Mohon maaf, booking Anda untuk ' . $this->booking->asset->name . ' ditolak dengan alasan: ' . $this->booking->reason_rejected,
            ];
        }
    }

    public function toBroadcast($notifiable)
    {
        if ($this->confirmBooking === 'approved') {
            return new BroadcastMessage([
                'title' => 'Booking Dikonfirmasi',
                'message' => 'Silakan lakukan pembayaran sebelum ' . $this->vaExpiredDate,
                'va_number' => $this->vaNumber,
                'amount' => $this->paymentAmount,
                'expired_date' => $this->vaExpiredDate
            ]);
        } else {
            return new BroadcastMessage([
                'title' => 'Booking Ditolak',
                'message' => 'Booking ' . $this->booking->asset->name . ' ditolak: ' . $this->booking->reason_rejected,
                'status' => 'rejected',
                'booking_id' => $this->booking->id,
                'reason' => $this->booking->reason_rejected
            ]);
        }
    }
}
