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
        'status',
        'priority',
        'assigned_to',
        'sprint_id',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'task_id';

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
}
