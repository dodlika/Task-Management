<?php

namespace App\Http\Controllers;

use App\Jobs\CheckDueTasks;
use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function checkDueTasks(Request $request, $token)
    {
        if ($token !== env('SCHEDULER_TOKEN')) {
            abort(403);
        }
        
        CheckDueTasks::dispatch();
        
        return response()->json(['message' => 'Due tasks check dispatched']);
    }
}