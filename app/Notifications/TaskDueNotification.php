<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $isOverdue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, bool $isOverdue = false)
    {
        $this->task = $task;
        $this->isOverdue = $isOverdue;
    }

    /**
     * Get the notification's delivery channels.
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
        $message = (new MailMessage)
                    ->subject($this->isOverdue ? 'Task Overdue: ' . $this->task->title : 'Task Due Soon: ' . $this->task->title)
                    ->line($this->isOverdue ? 'You have an overdue task:' : 'You have a task due soon:')
                    ->line('Title: ' . $this->task->title);
        
        if ($this->task->description) {
            $message->line('Description: ' . $this->task->description);
        }
        
        $message->line('Due Date: ' . $this->task->due_date->format('M d, Y H:i'))
                ->action('View Task', url('/tasks/' . $this->task->id))
                ->line('Thank you for using our task management system!');
                
        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date->format('Y-m-d H:i:s'),
            'type' => $this->isOverdue ? 'overdue' : 'upcoming',
        ];
    }
}