<?php

namespace App\Repositories\Interfaces;

interface TeamRepositoryInterface extends RepositoryInterface
{
    /**
     * Add user to team
     *
     * @param int $teamId
     * @param int $userId
     * @return mixed
     */
    public function addMember($teamId, $userId);

    /**
     * Remove user from team
     *
     * @param int $teamId
     * @param int $userId
     * @return mixed
     */
    public function removeMember($teamId, $userId);

    /**
     * Get teams by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getTeamsByUser($userId);

    /**
     * Get team members
     *
     * @param int $teamId
     * @return mixed
     */
    public function getMembers($teamId);

    /**
     * Get teams by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getTeamsByProject($projectId);
}
