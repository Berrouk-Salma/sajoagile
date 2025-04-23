<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $primaryKey = 'team_id';


    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

  
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'planifications', 'team_id', 'project_id');
    }
}
