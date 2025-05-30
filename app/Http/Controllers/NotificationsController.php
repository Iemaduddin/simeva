<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc') // opsional: urut dari terbaru
            ->get();
        if (auth()->user()->hasAnyRole(['Super Admin', 'UPT PU', 'Organizer', 'Kaur RT'])) {
            $view = 'dashboardPage.notifications';
        } else {
            $view = 'homepage.notifications';
        }
        return view($view, compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['status' => 'success']);
    }
}
