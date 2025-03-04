<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDueEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $isOverdue;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, bool $isOverdue = false)
    {
        $this->task = $task;
        $this->isOverdue = $isOverdue;
    }
}