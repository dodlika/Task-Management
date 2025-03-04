<!-- resources/views/tasks/export_all.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin-bottom: 5px;
            color: #2563eb;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .stat-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            width: 22%;
            box-sizing: border-box;
            margin-bottom: 10px;
            border: 1px solid #eee;
        }
        .stat-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
        }
        .completed {
            color: #10b981;
        }
        .pending {
            color: #6b7280;
        }
        .in-progress {
            color: #3b82f6;
        }
        .on-hold {
            color: #f59e0b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #047857;
        }
        .status-in-progress {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .status-pending {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .status-on-hold {
            background-color: #fef3c7;
            color: #b45309;
        }
        .priority-critical {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .priority-high {
            background-color: #ffedd5;
            color: #c2410c;
        }
        .priority-medium {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .priority-low {
            background-color: #d1fae5;
            color: #047857;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-button {
            text-align: center;
            margin: 20px 0;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ $user->name }}'s Tasks</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="stat-title">Total Tasks</div>
            <div class="stat-value">{{ $total_tasks }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Completed</div>
            <div class="stat-value completed">{{ $completed_tasks }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">In Progress</div>
            <div class="stat-value in-progress">{{ $in_progress_tasks }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Pending</div>
            <div class="stat-value pending">{{ $pending_tasks }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Category</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>
                        <span class="status-badge status-{{ $task->status }}">
                            {{ ucfirst($task->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td>
                        @if($task->due_date)
                            {{ $task->due_date->format('M d, Y') }}
                            @if($task->due_date->isPast() && $task->status !== 'completed')
                                <span style="color: red; font-weight: bold;">(Overdue)</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($task->category)
                            {{ $task->category->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $task->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="print-button">
        <button onclick="window.print()">Print / Save as PDF</button>
    </div>
    
    <div class="footer">
        Generated from Task Management System on {{ $generated_at }}
    </div>
    
    <script>
        // Automatically open print dialog when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure the page is fully loaded
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>