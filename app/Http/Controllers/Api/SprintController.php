<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SprintController extends Controller
{
    protected $sprintRepository;
    protected $projectRepository;
    protected $taskRepository;

    /**
     * SprintController constructor.
     *
     * @param SprintRepositoryInterface $sprintRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        SprintRepositoryInterface $sprintRepository,
        ProjectRepositoryInterface $projectRepository,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->sprintRepository = $sprintRepository;
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the sprints.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sprints = $this->sprintRepository->all();
        return response()->json($sprints);
    }

    /**
     * Store a newly created sprint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_id' => 'required|exists:projects,project_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sprint = $this->sprintRepository->create([
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'project_id' => $request->project_id,
        ]);

        return response()->json($sprint, 201);
    }

    /**
     * Display the specified sprint.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
            return response()->json($sprint);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sprint not found'], 404);
        }
    }

    /**
     * Update the specified sprint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sprint not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'goal' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'project_id' => 'sometimes|exists:projects,project_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->sprintRepository->update($request->only([
            'goal', 'start_date', 'end_date', 'project_id'
        ]), $id);

        $sprint = $this->sprintRepository->find($id);
        return response()->json($sprint);
    }

    /**
     * Remove the specified sprint from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->sprintRepository->delete($id);
            return response()->json(['message' => 'Sprint deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting sprint'], 500);
        }
    }

    /**
     * Get all tasks for a sprint.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasks($id)
    {
        try {
            $tasks = $this->sprintRepository->getTasks($id);
            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sprint not found'], 404);
        }
    }

    /**
     * Add a task to the sprint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTask(Request $request, $id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sprint not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,task_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->sprintRepository->addTask($id, $request->task_id);

        return response()->json(['message' => 'Task added to sprint successfully']);
    }
}
