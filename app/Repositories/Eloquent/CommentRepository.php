<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * CommentRepository constructor.
     *
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get comments by task
     *
     * @param int $taskId
     * @return mixed
     */
    public function getCommentsByTask($taskId)
    {
        return $this->model->where('task_id', $taskId)->get();
    }

    /**
     * Get comments by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getCommentsByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Add comment to task
     *
     * @param int $taskId
     * @param int $userId
     * @param string $content
     * @return mixed
     */
    public function addCommentToTask($taskId, $userId, $content)
    {
        return $this->model->create([
            'task_id' => $taskId,
            'user_id' => $userId,
            'content' => $content
        ]);
    }
}
