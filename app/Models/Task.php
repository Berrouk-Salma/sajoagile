<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'sprint_id',
        'due_date',
        'estimated_hours',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'task_id';
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'estimated_hours' => 'float',
    ];

    /**
     * Get the sprint that owns the task
     */
    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }

    /**
     * Get the user assigned to the task
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the comments for the task
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }
    
    /**
     * Get the time logs for the task
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'task_id');
    }
    
    /**
     * Get the tags for the task
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag', 'task_id', 'tag_id');
    }
    
    /**
     * Get the total time spent on this task in seconds
     */
    public function getTotalTimeAttribute()
    {
        return $this->timeLogs()->sum('duration');
    }
    
    /**
     * Check if a timer is currently running for this task for the given user
     */
    public function hasActiveTimer($userId = null)
    {
        $query = $this->timeLogs()->whereNull('end_time');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->exists();
    }
}
