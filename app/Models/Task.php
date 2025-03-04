<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use SoftDeletes;

    /**
     * Possible task statuses
     */
    public const STATUSES = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'on_hold' => 'On Hold',
    ];

    /**
     * Task priority levels
     */
    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium', 
        'high' => 'High',
        'critical' => 'Critical',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'user_id',
        'category_id',
        'estimated_time',
        'actual_time',
        'time_started_at',
        'time_stopped_at',
        'is_tracking'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'time_started_at' => 'datetime',
        'time_stopped_at' => 'datetime',
        'is_tracking' => 'boolean'
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function startTracking()
    {
        if ($this->is_tracking) {
            return false;
        }

        $this->update([
            'time_started_at' => now(),
            'is_tracking' => true
        ]);

        return true;
    }

    public function stopTracking()
    {
        if (!$this->is_tracking) {
            return false;
        }

        $duration = now()->diffInMinutes($this->time_started_at);

        $this->update([
            'actual_time' => $this->actual_time + $duration,
            'time_stopped_at' => now(),
            'is_tracking' => false,
            'time_started_at' => null
        ]);

        return $duration;
    }

    // Accessor for formatted time
    public function getFormattedEstimatedTimeAttribute()
    {
        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;
        return sprintf('%d hours %d minutes', $hours, $minutes);
    }

    public function getFormattedActualTimeAttribute()
    {
        $hours = floor($this->actual_time / 60);
        $minutes = $this->actual_time % 60;
        return sprintf('%d hours %d minutes', $hours, $minutes);
    }

public function category()
{
    return $this->belongsTo(Category::class);
}
public function files()
{
    return $this->hasMany(TaskFile::class);
}

    /**
     * Scope to filter tasks by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /**
     * Scope to filter tasks by priority
     */
    public function scopeFilterByPriority($query, $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Get the display name for the status
     */
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get the display name for the priority
     */
    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }
}