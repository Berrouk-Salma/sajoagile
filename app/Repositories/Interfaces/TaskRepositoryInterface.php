<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface extends RepositoryInterface
{
    /**
     * Get tasks by sprint
     *
     * @param int $sprintId
     * @return mixed
     */
    public function getTasksBySprint($sprintId);

    /**
     * Get tasks by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getTasksByUser($userId);

    /**
     * Get tasks by status
     *
     * @param string $status
     * @return mixed
     */
    public function getTasksByStatus($status);

    /**
     * Get tasks by priority
     *
     * @param string $priority
     * @return mixed
     */
    public function getTasksByPriority($priority);

    /**
     * Update task status
     *
     * @param int $taskId
     * @param string $status
     * @return mixed
     */
    public function updateStatus($taskId, $status);

    /**
     * Assign task to user
     *
     * @param int $taskId
     * @param int $userId
     * @return mixed
     */
    public function assignToUser($taskId, $userId);

    /**
     * Get comments for task
     *
     * @param int $taskId
     * @return mixed
     */
    public function getComments($taskId);
}
