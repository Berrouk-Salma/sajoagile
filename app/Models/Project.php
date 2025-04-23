<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'project_id';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the users assigned to the project
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    /**
     * Get the teams assigned to the project
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'planifications', 'project_id', 'team_id');
    }

    /**
     * Get the sprints for the project
     */
    public function sprints()
    {
        return $this->hasMany(Sprint::class, 'project_id');
    }

    /**
     * Get the lists for the project
     */
    public function lists()
    {
        return $this->belongsToMany(Lista::class, 'project_list', 'project_id', 'list_id');
    }
}
