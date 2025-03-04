<?php

namespace App\Services\Export;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskExportService
{
    /**
     * Generate export data for a single task
     *
     * @param Task $task
     * @return array
     */
    public function generateTaskData(Task $task): array
    {
        return [
            'task' => $task,
            'title' => "Task #{$task->id} - {$task->title}",
            'generated_at' => now()->format('M d, Y g:i A'),
        ];
    }
    
    /**
     * Generate export data for multiple tasks
     *
     * @param Collection $tasks
     * @param User $user
     * @return array
     */
    public function generateTasksData(Collection $tasks, User $user): array
    {
        return [
            'tasks' => $tasks,
            'user' => $user,
            'title' => "All Tasks - Task Management System",
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'on_hold_tasks' => $tasks->where('status', 'on_hold')->count(),
            'generated_at' => now()->format('M d, Y g:i A'),
        ];
    }
    
    /**
     * Filter tasks based on request parameters
     *
     * @param User $user
     * @param array $filters
     * @return Collection
     */
    public function getFilteredTasks(User $user, array $filters): Collection
    {
        $query = $user->tasks();
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
}