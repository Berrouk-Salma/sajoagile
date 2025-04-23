<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * TaskRepository constructor.
     *
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * Get tasks by sprint
     *
     * @param int $sprintId
     * @return mixed
     */
    public function getTasksBySprint($sprintId)
    {
        return $this->model->where('sprint_id', $sprintId)->get();
    }

    /**
     * Get tasks by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getTasksByUser($userId)
    {
        return $this->model->where('assigned_to', $userId)->get();
    }

    /**
     * Get tasks by status
     *
     * @param string $status
     * @return mixed
     */
    public function getTasksByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Get tasks by priority
     *
     * @param string $priority
     * @return mixed
     */
    public function getTasksByPriority($priority)
    {
        return $this->model->where('priority', $priority)->get();
    }

    /**
     * Update task status
     *
     * @param int $taskId
     * @param string $status
     * @return mixed
     */
    public function updateStatus($taskId, $status)
    {
        $task = $this->find($taskId);
        $task->status = $status;
        return $task->save();
    }

    /**
     * Assign task to user
     *
     * @param int $taskId
     * @param int $userId
     * @return mixed
     */
    public function assignToUser($taskId, $userId)
    {
        $task = $this->find($taskId);
        $task->assigned_to = $userId;
        return $task->save();
    }

    /**
     * Get comments for task
     *
     * @param int $taskId
     * @return mixed
     */
    public function getComments($taskId)
    {
        $task = $this->find($taskId);
        return $task->comments;
    }
}
