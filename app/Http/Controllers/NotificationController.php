<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Use direct DB query instead of relationship
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->where('id', $id)
            ->first();
            
        if ($notification) {
            DB::table('notifications')
                ->where('id', $id)
                ->update(['read_at' => now()]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        // Use direct DB query
        DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}