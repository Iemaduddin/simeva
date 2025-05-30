<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendETicket extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $event, public $user, public $participant, public $receiverRole) {}

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
        if ($this->event->is_free) {
            if ($this->receiverRole === 'Organizer') {
                return (new MailMessage)
                    ->subject('Peserta Baru untuk Event: ' . $this->event->title)
                    ->greeting('Halo, ' . $notifiable->name)
                    ->line($this->user->name . ' telah mendaftar ke event "' . $this->event->title . '".')
                    ->line('Silakan cek detail di dashboard Anda.');
            }

            return (new MailMessage)
                ->subject('E-Ticket: ' . $this->event->title)
                ->greeting('Halo, ' . $notifiable->name)
                ->line('Terima kasih telah mendaftar pada event "' . $this->event->title . '".')
                ->line('Kode Tiket Anda: ' . $this->participant->ticket_code)
                ->line('Silakan unduh E-Ticket pada "Kegiatanku" pada sistem.');
        } else {
            if ($this->receiverRole === 'Organizer') {
                if ($this->event->status === 'rejected') {
                    return (new MailMessage)
                        ->subject('Registrasi Ulang Peserta untuk Event: ' . $this->event->title)
                        ->greeting('Halo, ' . $notifiable->name)
                        ->line($this->user->name . ' telah melakukan pendaftaran ulang ke event "' . $this->event->title . '".')
                        ->line('Silakan cek detail di dashboard Anda.');
                } else {
                    return (new MailMessage)
                        ->subject('Segera Konfirmasi Peserta Baru untuk Event: ' . $this->event->title)
                        ->greeting('Halo, ' . $notifiable->name)
                        ->line($this->user->name . ' telah mendaftar ke event "' . $this->event->title . '".')
                        ->line('Silakan konfirmasi pembayaran dan cek detail di dashboard Anda.');
                }
            } else {

                return (new MailMessage)
                    ->subject('E-Ticket: ' . $this->event->title)
                    ->greeting('Halo, ' . $notifiable->name)
                    ->line('Terima kasih telah mendaftar pada event "' . $this->event->title . '".')
                    ->line('Kode Tiket Anda: ' . $this->participant->ticket_code)
                    ->line('Silakan unduh E-Ticket pada "Kegiatanku" pada sistem.');
            }
        }
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->event->is_free) {
            if ($this->receiverRole === 'Organizer') {
                return [
                    'title' => 'Peserta Baru Mendaftar',
                    'message' => $this->user->name . ' mendaftar ke event: ' . $this->event->title,
                    'event_id' => $this->event->id,
                    'user_id' => $this->user->id,
                ];
            }

            return [
                'title' => 'Pendaftaran Event Berhasil',
                'message' => 'Anda berhasil mendaftar pada event: ' . $this->event->title,
                'event_id' => $this->event->id,
                'ticket_code' => $this->participant->ticket_code,
                'user_id' => $this->user->id,
            ];
        } else {
            if ($this->receiverRole === 'Organizer') {
                return [
                    'title' => 'Segera Konfirmasi Peserta Baru!',
                    'message' => $this->user->name . ' mendaftar ke event: ' . $this->event->title,
                    'event_id' => $this->event->id,
                    'user_id' => $this->user->id,
                ];
            } else {
                return [
                    'title' => 'Pendaftaran Event Berhasil',
                    'message' => 'Anda berhasil mendaftar pada event: ' . $this->event->title,
                    'event_id' => $this->event->id,
                    'ticket_code' => $this->participant->ticket_code,
                    'user_id' => $this->user->id,
                ];
            }
        }
    }
}
