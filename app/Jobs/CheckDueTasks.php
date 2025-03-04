<?php

namespace App\Jobs;

use App\Events\TaskDueEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDueTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get tasks due in the next 24 hours
        $upcomingTasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '>', Carbon::now())
            ->where('due_date', '<=', Carbon::now()->addDay())
            ->get();
            
        foreach ($upcomingTasks as $task) {
            event(new TaskDueEvent($task));
        }
        
        // Get overdue tasks
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->get();
            
        foreach ($overdueTasks as $task) {
            event(new TaskDueEvent($task, true));
        }
    }
}