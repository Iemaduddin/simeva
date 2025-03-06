<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class BookingAsset implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $user;
    public $asset;

    public function __construct($booking)
    {
        $this->booking = $booking;
        $this->user = $booking->user; // Mengambil data user yang melakukan booking
        $this->asset = $booking->asset; // Mengambil data aset yang dipesan
    }

    public function broadcastOn()
    {
        return new Channel('bookings'); // Menggunakan channel 'bookings' untuk broadcast event
    }

    public function broadcastAs()
    {
        return 'booking.asset';
    }

    public function broadcastWith()
    {
        return [
            'booking_id' => $this->booking->id,
            'asset_id' => $this->booking->asset_id,
            'asset_name' => $this->asset->name ?? 'Unknown Asset',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'usage_date_start' => $this->booking->usage_date_start->format('Y-m-d H:i'),
            'usage_date_end' => $this->booking->usage_date_end->format('Y-m-d H:i'),
            'usage_event_name' => $this->booking->usage_event_name,
            'status' => $this->booking->status
        ];
    }
}
