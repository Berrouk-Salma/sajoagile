<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskRepository;
    protected $userRepository;
    protected $sprintRepository;
    protected $commentRepository;

    /**
     * TaskController constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param UserRepositoryInterface $userRepository
     * @param SprintRepositoryInterface $sprintRepository
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        UserRepositoryInterface $userRepository,
        SprintRepositoryInterface $sprintRepository,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->sprintRepository = $sprintRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = $this->taskRepository->all();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'sprint_id' => 'required|exists:sprints,sprint_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = $this->taskRepository->create([
            'title' => $request->title,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'medium',
            'assigned_to' => $request->assigned_to,
            'sprint_id' => $request->sprint_id,
        ]);

        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $task = $this->taskRepository->find($id);
            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'sprint_id' => 'sometimes|exists:sprints,sprint_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->taskRepository->update($request->only([
            'title', 'status', 'priority', 'assigned_to', 'sprint_id'
        ]), $id);

        $task = $this->taskRepository->find($id);
        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->taskRepository->delete($id);
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting task'], 500);
        }
    }

    /**
     * Update task status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->taskRepository->updateStatus($id, $request->status);

        $task = $this->taskRepository->find($id);
        return response()->json($task);
    }

    /**
     * Assign task to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignToUser(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->taskRepository->assignToUser($id, $request->user_id);

        $task = $this->taskRepository->find($id);
        return response()->json($task);
    }

    /**
     * Get all comments for a task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments($id)
    {
        try {
            $comments = $this->taskRepository->getComments($id);
            return response()->json($comments);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Add a comment to a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = $this->commentRepository->addCommentToTask(
            $id,
            $request->user()->id,
            $request->content
        );

        return response()->json($comment, 201);
    }

    /**
     * Get tasks by sprint.
     *
     * @param  int  $sprintId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksBySprint($sprintId)
    {
        try {
            $sprint = $this->sprintRepository->find($sprintId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sprint not found'], 404);
        }

        $tasks = $this->taskRepository->getTasksBySprint($sprintId);
        return response()->json($tasks);
    }

    /**
     * Get tasks by user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksByUser($userId)
    {
        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $tasks = $this->taskRepository->getTasksByUser($userId);
        return response()->json($tasks);
    }

    /**
     * Get tasks by status.
     *
     * @param  string  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksByStatus($status)
    {
        $validator = Validator::make(['status' => $status], [
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tasks = $this->taskRepository->getTasksByStatus($status);
        return response()->json($tasks);
    }

    /**
     * Get tasks by priority.
     *
     * @param  string  $priority
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksByPriority($priority)
    {
        $validator = Validator::make(['priority' => $priority], [
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tasks = $this->taskRepository->getTasksByPriority($priority);
        return response()->json($tasks);
    }
}
