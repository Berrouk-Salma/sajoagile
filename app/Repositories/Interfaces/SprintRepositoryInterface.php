<?php

namespace App\Repositories\Interfaces;

interface SprintRepositoryInterface extends RepositoryInterface
{
    /**
     * Get sprints by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getSprintsByProject($projectId);

    /**
     * Get current sprint for project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getCurrentSprint($projectId);

    /**
     * Get tasks for sprint
     *
     * @param int $sprintId
     * @return mixed
     */
    public function getTasks($sprintId);

    /**
     * Add task to sprint
     *
     * @param int $sprintId
     * @param int $taskId
     * @return mixed
     */
    public function addTask($sprintId, $taskId);
}
