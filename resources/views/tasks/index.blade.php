@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-900">Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
        Create Task
    </a>
</div>

<div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 bg-white border-b border-gray-200">
            <div class="text-lg font-semibold">Total</div>
            <div class="text-2xl">{{ $statistics['total'] }}</div>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 bg-white border-b border-gray-200">
            <div class="text-lg font-semibold">Pending</div>
            <div class="text-2xl">{{ $statistics['pending'] }}</div>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 bg-white border-b border-gray-200">
            <div class="text-lg font-semibold">In Progress</div>
            <div class="text-2xl">{{ $statistics['in_progress'] }}</div>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 bg-white border-b border-gray-200">
            <div class="text-lg font-semibold">Completed</div>
            <div class="text-2xl">{{ $statistics['completed'] }}</div>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <form action="{{ route('tasks.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search tasks..." class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <select name="status" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="priority" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $key => $value)
                            <option value="{{ $key }}" {{ ($filters['priority'] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Filter
                    </button>
                </div>
                <a href="{{ route('tasks.export.all', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Export All
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tasks as $task)
                        <tr>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $task->title }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($task->status == 'completed') bg-green-100 text-green-800 
                                    @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800 
                                    @elseif($task->status == 'on_hold') bg-yellow-100 text-yellow-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $task->status_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($task->priority == 'critical') bg-red-100 text-red-800 
                                    @elseif($task->priority == 'high') bg-orange-100 text-orange-800 
                                    @elseif($task->priority == 'medium') bg-blue-100 text-blue-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $task->priority_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                @if($task->due_date)
                                    <span class="{{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $task->due_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm font-medium">
                                <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-6 text-sm text-gray-500 text-center">No tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tasks->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection