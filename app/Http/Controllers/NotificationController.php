<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Perbaikan: cek apakah user memiliki method notifications
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Jika user menggunakan Notifiable trait
        if ($user && method_exists($user, 'notifications')) {
            $notifications = $user->notifications()->paginate(20);
            return response()->json($notifications);
        }

        // Jika tidak, return empty array
        return response()->json([]);
    }

    public function markAsRead($id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && method_exists($user, 'notifications')) {
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        if (method_exists($user, 'notifications')) {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
