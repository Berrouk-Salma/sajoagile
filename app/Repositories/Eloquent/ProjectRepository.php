<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    /**
     * Get projects by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getProjectsByUser($userId)
    {
        return $this->model->whereHas('members', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    /**
     * Get projects by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function getProjectsByTeam($teamId)
    {
        return $this->model->whereHas('teams', function ($query) use ($teamId) {
            $query->where('teams.team_id', $teamId);
        })->get();
    }

    /**
     * Add user to project
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function addMember($projectId, $userId)
    {
        $project = $this->find($projectId);
        return $project->members()->attach($userId);
    }

    /**
     * Remove user from project
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function removeMember($projectId, $userId)
    {
        $project = $this->find($projectId);
        return $project->members()->detach($userId);
    }

    /**
     * Add team to project
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function addTeam($projectId, $teamId)
    {
        $project = $this->find($projectId);
        return $project->teams()->attach($teamId);
    }

    /**
     * Remove team from project
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function removeTeam($projectId, $teamId)
    {
        $project = $this->find($projectId);
        return $project->teams()->detach($teamId);
    }

    /**
     * Get project members
     *
     * @param int $projectId
     * @return mixed
     */
    public function getMembers($projectId)
    {
        $project = $this->find($projectId);
        return $project->members;
    }

    /**
     * Get project teams
     *
     * @param int $projectId
     * @return mixed
     */
    public function getTeams($projectId)
    {
        $project = $this->find($projectId);
        return $project->teams;
    }
}
