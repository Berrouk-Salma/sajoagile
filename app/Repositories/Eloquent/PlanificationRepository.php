<?php

namespace App\Repositories\Eloquent;

use App\Models\Planification;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PlanificationRepositoryInterface;

class PlanificationRepository extends BaseRepository implements PlanificationRepositoryInterface
{
    /**
     * PlanificationRepository constructor.
     *
     * @param Planification $model
     */
    public function __construct(Planification $model)
    {
        parent::__construct($model);
    }

    /**
     * Get planifications by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getPlanificationsByProject($projectId)
    {
        return $this->model->where('project_id', $projectId)->get();
    }

    /**
     * Get planifications by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function getPlanificationsByTeam($teamId)
    {
        return $this->model->where('team_id', $teamId)->get();
    }

    /**
     * Get planifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getPlanificationsByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Create team planification
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function createTeamPlanification($projectId, $teamId)
    {
        return $this->create([
            'project_id' => $projectId,
            'team_id' => $teamId,
            'type' => 'team'
        ]);
    }

    /**
     * Create individual planification
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function createIndividualPlanification($projectId, $userId)
    {
        return $this->create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'type' => 'individual'
        ]);
    }
}
