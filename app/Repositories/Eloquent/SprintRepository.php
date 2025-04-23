<?php

namespace App\Repositories\Eloquent;

use App\Models\Sprint;
use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use Carbon\Carbon;

class SprintRepository extends BaseRepository implements SprintRepositoryInterface
{
    /**
     * SprintRepository constructor.
     *
     * @param Sprint $model
     */
    public function __construct(Sprint $model)
    {
        parent::__construct($model);
    }

    /**
     * Get sprints by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getSprintsByProject($projectId)
    {
        return $this->model->where('project_id', $projectId)->get();
    }

    /**
     * Get current sprint for project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getCurrentSprint($projectId)
    {
        $today = Carbon::now()->toDateString();
        return $this->model->where('project_id', $projectId)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
    }

    /**
     * Get tasks for sprint
     *
     * @param int $sprintId
     * @return mixed
     */
    public function getTasks($sprintId)
    {
        $sprint = $this->find($sprintId);
        return $sprint->tasks;
    }

    /**
     * Add task to sprint
     *
     * @param int $sprintId
     * @param int $taskId
     * @return mixed
     */
    public function addTask($sprintId, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->sprint_id = $sprintId;
        return $task->save();
    }
}
