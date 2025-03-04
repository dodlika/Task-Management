@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-900">Task Details</h1>
    <div class="flex space-x-2">
        <a href="{{ route('tasks.export', $task) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
            Export as PDF
        </a>
        <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-800 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
            Edit
        </a>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this task?')">
                Delete
            </button>
        </form>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 bg-white border-b border-gray-200">
        {{-- Time Tracking Section --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-md">
            <h3 class="text-lg font-semibold mb-4">Time Tracking</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Estimated Time:</p>
                    <p class="font-medium">
                        {{ floor($task->estimated_time / 60) }} hours 
                        {{ $task->estimated_time % 60 }} minutes
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Actual Time Spent:</p>
                    <p class="font-medium">
                        {{ floor($task->actual_time / 60) }} hours 
                        {{ $task->actual_time % 60 }} minutes
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <form 
                    action="{{ route('tasks.toggle-tracking', $task) }}" 
                    method="POST" 
                    class="inline-block"
                >
                    @csrf
                    <button 
                        type="submit" 
                        class="px-4 py-2 
                        {{ $task->is_tracking ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-500 hover:bg-blue-600' }} 
                        text-white rounded transition duration-300 ease-in-out"
                    >
                        {{ $task->is_tracking ? 'Stop Time Tracking' : 'Start Time Tracking' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Existing Task Details --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold">{{ $task->title }}</h2>
            <div class="mt-2 flex items-center space-x-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($task->status == 'completed') bg-green-100 text-green-800 
                    @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800 
                    @elseif($task->status == 'on_hold') bg-yellow-100 text-yellow-800 
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $task->status_name }}
                </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($task->priority == 'critical') bg-red-100 text-red-800 
                    @elseif($task->priority == 'high') bg-orange-100 text-orange-800 
                    @elseif($task->priority == 'medium') bg-blue-100 text-blue-800 
                    @else bg-green-100 text-green-800 @endif">
                    {{ $task->priority_name }}
                </span>
                @if($task->due_date)
                    <span class="{{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        Due: {{ $task->due_date->format('M d, Y g:i A') }}
                        @if($task->isOverdue())
                            <span class="ml-1 text-red-600">(Overdue)</span>
                        @endif
                    </span>
                @endif
            </div>
        </div>

        {{-- Rest of the existing content --}}
        <div class="mb-6">
            <h3 class="text-md font-medium text-gray-700 mb-2">Description</h3>
            <div class="bg-gray-50 p-4 rounded">
                {!! nl2br(e($task->description)) !!}
                @if(!$task->description)
                    <p class="text-gray-400 italic">No description provided.</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-md font-medium text-gray-700 mb-2">Created</h3>
                <p>{{ $task->created_at->format('M d, Y g:i A') }}</p>
            </div>
            <div>
                <h3 class="text-md font-medium text-gray-700 mb-2">Last Updated</h3>
                <p>{{ $task->updated_at->format('M d, Y g:i A') }}</p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Back to Tasks
            </a>
        </div>
    </div>
</div>

{{-- Existing Files Section --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Files</h3>
        
        <div class="mb-4 bg-gray-50 p-4 rounded">
            <form action="{{ route('tasks.files.store', $task) }}" method="POST" enctype="multipart/form-data" class="flex items-center">
                @csrf
                <input type="file" name="file" id="file" class="form-input flex-1 mr-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Upload
                </button>
            </form>
            @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Files List --}}
        <div class="mt-4">
            @if($task->files->isEmpty())
                <p class="text-gray-500">No files attached.</p>
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($task->files as $file)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $file->original_filename }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ round($file->file_size / 1024, 2) }} KB • 
                                                {{ $file->created_at->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tasks.files.download', [$task, $file]) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Download
                                        </a>
                                        <form action="{{ route('tasks.files.destroy', [$task, $file]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this file?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection