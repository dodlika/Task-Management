@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Notifications</h1>
            
            @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if($notifications->isEmpty())
                    <p class="text-gray-500 text-center py-4">No notifications found.</p>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="py-4 flex items-center {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}">
                                <div class="flex-1">
                                    @if($notification->data['type'] === 'overdue')
                                        <div class="text-red-600 font-medium">Overdue Task</div>
                                    @else
                                        <div class="text-blue-600 font-medium">Upcoming Task</div>
                                    @endif
                                    <div class="text-lg">{{ $notification->data['title'] }}</div>
                                    <div class="text-sm text-gray-500">Due: {{ \Carbon\Carbon::parse($notification->data['due_date'])->format('M d, Y H:i') }}</div>
                                    <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        View
                                    </a>
                                    
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection