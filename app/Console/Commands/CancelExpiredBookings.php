<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\AssetBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingAutoCancelledNotification;

class CancelExpiredBookings extends Command
{
    protected $signature = 'booking:cancel-expired';
    protected $description = 'Cancel bookings older than 4 days';

    public function handle()
    {
        $expiredDate = now()->subDays(4);

        $expiredBookings = AssetBooking::where('status', 'booked')
            ->whereNotNull('asset_category_id')
            ->where('updated_at', '<=', $expiredDate)
            ->get();
        foreach ($expiredBookings as $booking) {
            $booking->status = 'cancelled';
            $booking->reason = 'Peminjaman dibatalkan, karena Anda tidak mengonfirmasi dalam 4 hari setelah pemesanan.';
            $booking->save();

            // Kirim notifikasi ke user terkait
            Notification::send($booking->user, new BookingAutoCancelledNotification($booking));
        }

        $this->info("Expired bookings cancelled: " . $expiredBookings->count());
    }
}
