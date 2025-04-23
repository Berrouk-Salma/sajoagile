<?php

namespace App\Repositories\Interfaces;

interface PlanificationRepositoryInterface extends RepositoryInterface
{
    /**
     * Get planifications by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getPlanificationsByProject($projectId);

    /**
     * Get planifications by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function getPlanificationsByTeam($teamId);

    /**
     * Get planifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getPlanificationsByUser($userId);

    /**
     * Create team planification
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function createTeamPlanification($projectId, $teamId);

    /**
     * Create individual planification
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function createIndividualPlanification($projectId, $userId);
}
