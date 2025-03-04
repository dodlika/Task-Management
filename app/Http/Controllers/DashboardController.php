<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with analytics.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get task counts by status
        $tasksByStatus = Task::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
            
        // Initialize with all statuses (even if count is 0)
        $statusCounts = [];
        foreach (Task::STATUSES as $key => $name) {
            $statusCounts[$name] = $tasksByStatus[$key] ?? 0;
        }
        
        // Get task counts by priority
        $tasksByPriority = Task::where('user_id', $user->id)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();
            
        // Initialize with all priorities (even if count is 0)
        $priorityCounts = [];
        foreach (Task::PRIORITIES as $key => $name) {
            $priorityCounts[$name] = $tasksByPriority[$key] ?? 0;
        }
        
        // Get tasks created per day (last 14 days)
        $tasksCreatedByDay = Task::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
            
        // Fill in missing days with 0
        $tasksTrend = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $tasksTrend[$date] = $tasksCreatedByDay[$date] ?? 0;
        }
        
        // Get overdue tasks
        $overdueTasks = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();
            
        // Get tasks due today
        $tasksDueToday = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', Carbon::today())
            ->count();
            
        // Get tasks completed in the last 7 days
        $recentlyCompleted = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->count();
        
        return view('dashboard', compact(
            'statusCounts', 
            'priorityCounts', 
            'tasksTrend',
            'overdueTasks',
            'tasksDueToday',
            'recentlyCompleted'
        ));
    }
}