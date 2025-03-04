<?php

namespace App\Listeners;

use App\Events\TaskDueEvent;
use App\Notifications\TaskDueNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskDueNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(TaskDueEvent $event): void
    {
        $user = User::find($event->task->user_id);
        if ($user) {
            $user->notify(new TaskDueNotification($event->task, $event->isOverdue));
        }
    }
}