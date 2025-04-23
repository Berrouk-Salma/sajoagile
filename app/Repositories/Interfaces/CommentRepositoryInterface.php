<?php

namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface extends RepositoryInterface
{
    /**
     * Get comments by task
     *
     * @param int $taskId
     * @return mixed
     */
    public function getCommentsByTask($taskId);

    /**
     * Get comments by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getCommentsByUser($userId);

    /**
     * Add comment to task
     *
     * @param int $taskId
     * @param int $userId
     * @param string $content
     * @return mixed
     */
    public function addCommentToTask($taskId, $userId, $content);
}
