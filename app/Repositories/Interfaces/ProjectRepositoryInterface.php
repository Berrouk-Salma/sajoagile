<?php

namespace App\Repositories\Interfaces;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * Get projects by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getProjectsByUser($userId);

    /**
     * Get projects by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function getProjectsByTeam($teamId);

    /**
     * Add user to project
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function addMember($projectId, $userId);

    /**
     * Remove user from project
     *
     * @param int $projectId
     * @param int $userId
     * @return mixed
     */
    public function removeMember($projectId, $userId);

    /**
     * Add team to project
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function addTeam($projectId, $teamId);

    /**
     * Remove team from project
     *
     * @param int $projectId
     * @param int $teamId
     * @return mixed
     */
    public function removeTeam($projectId, $teamId);

    /**
     * Get project members
     *
     * @param int $projectId
     * @return mixed
     */
    public function getMembers($projectId);

    /**
     * Get project teams
     *
     * @param int $projectId
     * @return mixed
     */
    public function getTeams($projectId);
}
