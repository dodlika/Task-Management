<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TaskService
{
    /**
     * Create a new task
     *
     * @param User $user
     * @param array $data
     * @return Task
     */
    public function createTask(User $user, array $data): Task 
{
    // Calculate estimated time in minutes if provided
    if (isset($data['estimated_hours']) || isset($data['estimated_minutes'])) {
        $data['estimated_time'] = 
            (int)($data['estimated_hours'] ?? 0) * 60 + 
            (int)($data['estimated_minutes'] ?? 0);
    }

    // Validate input
    $validatedData = $this->validateTaskData($data);

    // Parse due date if provided
    if (isset($validatedData['due_date']) && $validatedData['due_date']) {
        $validatedData['due_date'] = Carbon::parse($validatedData['due_date']);
    }

    // Create task - category_id and estimated_time will be included in $validatedData if present
    return $user->tasks()->create($validatedData);
}

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        // Validate input
        $validatedData = $this->validateTaskData($data, $task->id);

        // Parse due date if provided
        if (isset($validatedData['due_date']) && $validatedData['due_date']) {
            $validatedData['due_date'] = Carbon::parse($validatedData['due_date']);
        }

        // Update task
        $task->update($validatedData);

        return $task;
    }

    /**
     * Get paginated tasks for a user with advanced filtering
     *
     * @param User $user
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    // In the getUserTasks method of your TaskService
public function getUserTasks(
    User $user, 
    array $filters = [], 
    int $perPage = 10
): LengthAwarePaginator {
    $query = $user->tasks();

    // Filter by status
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    // Filter by priority
    if (!empty($filters['priority'])) {
        $query->where('priority', $filters['priority']);
    }
    
    // Filter by category
    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }

    // Search by title or description
    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Sort options
    $sortField = $filters['sort_by'] ?? 'created_at';
    $sortDirection = $filters['sort_direction'] ?? 'desc';
    $query->orderBy($sortField, $sortDirection);

    return $query->paginate($perPage);
}

    /**
     * Get task statistics for a user
     *
     * @param User $user
     * @return array
     */
    public function getTaskStatistics(User $user): array
    {
        return [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->where('status', 'pending')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'overdue' => $user->tasks()->where('status', '!=', 'completed')
                          ->whereNotNull('due_date')
                          ->where('due_date', '<', now())
                          ->count(),
        ];
    }

    /**
     * Get recent tasks for a user
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentTasks(User $user, int $limit = 5)
    {
        return $user->tasks()
            ->latest()
            ->take($limit)
            ->get();
    }

    

    /**
     * Validate task input data
     *
     * @param array $data
     * @param int|null $taskId
     * @return array
     */
    private function validateTaskData(array $data, ?int $taskId = null): array
{
    $uniqueRule = $taskId 
        ? 'unique:tasks,title,' . $taskId 
        : 'unique:tasks,title';

    return Validator::make($data, [
        'title' => ['required', 'max:255', $uniqueRule],
        'description' => 'nullable|string',
        'status' => 'in:' . implode(',', array_keys(Task::STATUSES)),
        'priority' => 'in:' . implode(',', array_keys(Task::PRIORITIES)),
        'due_date' => 'nullable|date|after_or_equal:today',
        'category_id' => 'nullable|exists:categories,id',
        
        // New validation for estimated time
        'estimated_time' => 'nullable|integer|min:0',
        'estimated_hours' => 'nullable|integer|min:0',
        'estimated_minutes' => 'nullable|integer|min:0|max:59',
    ])->validate();
}
}