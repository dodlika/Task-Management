<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming and overdue tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $upcomingTasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '>', Carbon::now())
            ->where('due_date', '<=', Carbon::now()->addDay())
            ->get();
            
        $this->info('Found ' . $upcomingTasks->count() . ' upcoming tasks');
        
        foreach ($upcomingTasks as $task) {
            $user = User::find($task->user_id);
            $user->notify(new TaskDueNotification($task));
            $this->info('Sent upcoming notification for task: ' . $task->title);
        }
        
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->get();
            
        $this->info('Found ' . $overdueTasks->count() . ' overdue tasks');
        
        foreach ($overdueTasks as $task) {
            $user = User::find($task->user_id);
            $user->notify(new TaskDueNotification($task, true));
            $this->info('Sent overdue notification for task: ' . $task->title);
        }
        
        return 0;
    }
}