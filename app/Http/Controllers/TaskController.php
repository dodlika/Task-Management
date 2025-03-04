<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Services\Task\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


public function calendar()
{
    $tasks = Task::where('user_id', Auth::id())
                ->whereNotNull('due_date')
                ->orderBy('due_date')
                ->get();
    
    $groupedTasks = [];
    foreach ($tasks as $task) {
        $date = $task->due_date->format('Y-m-d');
        if (!isset($groupedTasks[$date])) {
            $groupedTasks[$date] = [];
        }
        $groupedTasks[$date][] = $task;
    }
    
    return view('tasks.calendar', [
        'tasks' => $tasks,
        'groupedTasks' => $groupedTasks
    ]);
}
  public function create()
  {
      $user_id = Auth::id();
      $categories = Category::where('user_id', $user_id)->orderBy('name')->get();
      
      return view('tasks.create', [
          'statuses' => Task::STATUSES,
          'priorities' => Task::PRIORITIES,
          'categories' => $categories,
      ]);
  }

public function edit(Task $task)
{
    if (Auth::id() !== $task->user_id) {
        abort(403);
    }

    $categories = Category::where('user_id', Auth::id())
    ->orderBy('name')
    ->get();

    return view('tasks.edit', [
        'task' => $task,
        'statuses' => Task::STATUSES,
        'priorities' => Task::PRIORITIES,
        'categories' => $categories,
    ]);
}

public function index(Request $request)
{
    $filters = [
        'status' => $request->status,
        'priority' => $request->priority,
        'category_id' => $request->category_id,
        'search' => $request->search,
        'sort_by' => $request->sort_by ?? 'created_at',
        'sort_direction' => $request->sort_direction ?? 'desc',
    ];

    $user = Auth::user();
    $categories = $user->categories;
    
    // Add category to the existing code
    $tasks = $this->taskService->getUserTasks($user, $filters);
    $statistics = $this->taskService->getTaskStatistics($user);

    return view('tasks.index', [
        'tasks' => $tasks,
        'statistics' => $statistics,
        'statuses' => Task::STATUSES,
        'priorities' => Task::PRIORITIES,
        'categories' => $categories,
        'filters' => $filters,
    ]);
}
public function store(Request $request)
{
    $user = Auth::user();
    $taskData = $request->all();
    
    // Convert estimated time to minutes if provided
    if ($request->has('estimated_hours') && $request->has('estimated_minutes')) {
        $taskData['estimated_time'] = 
            ($request->estimated_hours * 60) + $request->estimated_minutes;
    }

    $task = $this->taskService->createTask($user, $taskData);

    return redirect()->route('tasks.index')
        ->with('success', 'Task created successfully.');
}

    public function toggleTracking(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        if ($task->is_tracking) {
            $duration = $task->stopTracking();
            $message = "Time tracking stopped. Duration: {$duration} minutes.";
        } else {
            $task->startTracking();
            $message = "Time tracking started.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function show(Task $task)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

   
    public function update(Request $request, Task $task)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $this->taskService->updateTask($task, $request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}