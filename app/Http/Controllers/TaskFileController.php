<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskFileController extends Controller
{
    public function store(Request $request, Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }
        
        $request->validate([
            'file' => 'required|file|max:10240', 
        ]);
        
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $fileType = $file->getMimeType();
        $fileSize = $file->getSize();
        
        $filename = time() . '_' . $originalFilename;
        
        // Store the file
        $filePath = $file->storeAs('task-files/' . $task->id, $filename, 'public');
        
        TaskFile::create([
            'task_id' => $task->id,
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
        ]);
        
        return redirect()->route('tasks.show', $task)
            ->with('success', 'File uploaded successfully.');
    }
    
    public function destroy(Task $task, TaskFile $file)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }
        
        Storage::disk('public')->delete($file->file_path);
        
        $file->delete();
        
        return redirect()->route('tasks.show', $task)
            ->with('success', 'File deleted successfully.');
    }
    
    public function download(Task $task, TaskFile $file)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }
        
        return Storage::disk('public')->download(
            $file->file_path, 
            $file->original_filename
        );
    }
}