<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task #{{ $task->id }} - {{ $task->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .task-title {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .meta {
            margin-bottom: 20px;
            color: #666;
        }
        .meta-item {
            margin-bottom: 5px;
        }
        .meta-item strong {
            display: inline-block;
            width: 100px;
        }
        .description {
            margin-bottom: 20px;
        }
        .description-content {
            white-space: pre-line;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #eee;
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
        <h1 class="task-title">{{ $task->title }}</h1>
        <p>Task ID: #{{ $task->id }}</p>
    </div>
    
    <div class="meta">
        <div class="meta-item">
            <strong>Status:</strong> {{ $task->status_name }}
        </div>
        <div class="meta-item">
            <strong>Priority:</strong> {{ $task->priority_name }}
        </div>
        @if($task->category)
        <div class="meta-item">
            <strong>Category:</strong> {{ $task->category->name }}
        </div>
        @endif
        @if($task->due_date)
        <div class="meta-item">
            <strong>Due Date:</strong> {{ $task->due_date->format('M d, Y g:i A') }}
        </div>
        @endif
        <div class="meta-item">
            <strong>Created:</strong> {{ $task->created_at->format('M d, Y g:i A') }}
        </div>
        <div class="meta-item">
            <strong>Last Updated:</strong> {{ $task->updated_at->format('M d, Y g:i A') }}
        </div>
    </div>
    
    <div class="description">
        <h2>Description</h2>
        <div class="description-content">
            {{ $task->description ?: 'No description provided.' }}
        </div>
    </div>
    
    <div class="print-button">
        <button onclick="printDocument()">Print / Save as PDF</button>
    </div>
    
    <div class="footer">
        Generated from Task Management System on {{ now()->format('M d, Y g:i A') }}
    </div>
    
    <script>
        function printDocument() {
        window.print();
    }
    </script>
</body>
</html>