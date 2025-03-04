<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\Export\TaskExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class TaskExportController extends Controller
{
    protected $exportService;
    
    public function __construct(TaskExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function exportTask(Task $task)
    {
        // Check if the task belongs to the user
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }
        
        $data = $this->exportService->generateTaskData($task);
        
        $pdf = PDF::loadView('tasks.export', $data);
        
        return $pdf->download("task-{$task->id}.pdf");
    }
    

    public function exportAll(Request $request)
    {
        $user = Auth::user();
        
        $filters = [
            'status' => $request->status,
            'priority' => $request->priority,
            'category_id' => $request->category_id,
            'search' => $request->search,
        ];
        
        $tasks = $this->exportService->getFilteredTasks($user, $filters);
        $data = $this->exportService->generateTasksData($tasks, $user);
        
        $pdf = PDF::loadView('tasks.export_all', $data);
        
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('all-tasks.pdf');
    }
}