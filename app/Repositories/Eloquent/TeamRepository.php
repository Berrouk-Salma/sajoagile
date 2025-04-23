<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\TeamRepositoryInterface;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    /**
     * TeamRepository constructor.
     *
     * @param Team $model
     */
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    /**
     * Add user to team
     *
     * @param int $teamId
     * @param int $userId
     * @return mixed
     */
    public function addMember($teamId, $userId)
    {
        $team = $this->find($teamId);
        return $team->members()->attach($userId);
    }

    /**
     * Remove user from team
     *
     * @param int $teamId
     * @param int $userId
     * @return mixed
     */
    public function removeMember($teamId, $userId)
    {
        $team = $this->find($teamId);
        return $team->members()->detach($userId);
    }

    /**
     * Get teams by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getTeamsByUser($userId)
    {
        return $this->model->whereHas('members', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    /**
     * Get team members
     *
     * @param int $teamId
     * @return mixed
     */
    public function getMembers($teamId)
    {
        $team = $this->find($teamId);
        return $team->members;
    }

    /**
     * Get teams by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getTeamsByProject($projectId)
    {
        return $this->model->whereHas('projects', function ($query) use ($projectId) {
            $query->where('projects.project_id', $projectId);
        })->get();
    }
}
