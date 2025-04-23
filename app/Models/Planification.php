<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'team_id',
        'user_id',
        'type', // 'team' or 'individual'
    ];

    /**
     * Get the project associated with the planification
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the team associated with the planification
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the user associated with the planification
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
