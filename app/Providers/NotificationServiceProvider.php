<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Mengirimkan notifikasi ke navbar di semua halaman
        View::composer('components.navbar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = $user->unreadNotifications;
                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', collect([])); // Kosong jika belum login
            }
        });
        View::composer('components.landingpage.navbar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = $user->unreadNotifications;
                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', collect([])); // Kosong jika belum login
            }
        });
    }
}
