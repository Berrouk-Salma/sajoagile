<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskRepository;
    protected $sprintRepository;
    protected $userRepository;
    protected $commentRepository;
    protected $projectRepository;

    /**
     * TaskController constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param SprintRepositoryInterface $sprintRepository
     * @param UserRepositoryInterface $userRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        SprintRepositoryInterface $sprintRepository,
        UserRepositoryInterface $userRepository,
        CommentRepositoryInterface $commentRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->sprintRepository = $sprintRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the tasks for a sprint.
     *
     * @param  int  $sprintId
     * @return \Illuminate\View\View
     */
    public function index($sprintId)
    {
        try {
            $sprint = $this->sprintRepository->find($sprintId);
            $project = $this->projectRepository->find($sprint->project_id);
            $tasks = $this->taskRepository->getTasksBySprint($sprintId);
            
            // Group tasks by status
            $tasksByStatus = [
                'todo' => $tasks->where('status', 'todo'),
                'in_progress' => $tasks->where('status', 'in_progress'),
                'review' => $tasks->where('status', 'review'),
                'done' => $tasks->where('status', 'done'),
            ];
            
            return view('tasks.index', compact('sprint', 'project', 'tasks', 'tasksByStatus'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }
    }

    /**
     * Show the form for creating a new task.
     *
     * @param  int  $sprintId
     * @return \Illuminate\View\View
     */
    public function create($sprintId)
    {
        try {
            $sprint = $this->sprintRepository->find($sprintId);
            $project = $this->projectRepository->find($sprint->project_id);
            $projectMembers = $this->projectRepository->getMembers($project->project_id);
            
            return view('tasks.create', compact('sprint', 'project', 'projectMembers'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $sprintId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $sprintId)
    {
        try {
            $sprint = $this->sprintRepository->find($sprintId);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $task = $this->taskRepository->create([
            'title' => $request->title,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'medium',
            'assigned_to' => $request->assigned_to,
            'sprint_id' => $sprintId,
        ]);

        return redirect()->route('tasks.show', $task->task_id)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $task = $this->taskRepository->find($id);
            $sprint = $this->sprintRepository->find($task->sprint_id);
            $project = $this->projectRepository->find($sprint->project_id);
            $assignedUser = $task->assigned_to ? $this->userRepository->find($task->assigned_to) : null;
            $comments = $this->taskRepository->getComments($id);
            
            return view('tasks.show', compact('task', 'sprint', 'project', 'assignedUser', 'comments'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $task = $this->taskRepository->find($id);
            $sprint = $this->sprintRepository->find($task->sprint_id);
            $project = $this->projectRepository->find($sprint->project_id);
            $projectMembers = $this->projectRepository->getMembers($project->project_id);
            
            return view('tasks.edit', compact('task', 'sprint', 'project', 'projectMembers'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->taskRepository->update([
            'title' => $request->title,
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
        ], $id);

        return redirect()->route('tasks.show', $id)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $task = $this->taskRepository->find($id);
            $sprintId = $task->sprint_id;
            
            $this->taskRepository->delete($id);
            
            return redirect()->route('tasks.index', $sprintId)
                ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Error deleting task.');
        }
    }

    /**
     * Update the status of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->taskRepository->updateStatus($id, $request->status);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    /**
     * Assign a user to a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignUser(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }

        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->taskRepository->assignToUser($id, $request->assigned_to);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Task assigned successfully.');
    }

    /**
     * Display comments for a task.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showComments($id)
    {
        try {
            $task = $this->taskRepository->find($id);
            $comments = $this->taskRepository->getComments($id);
            $sprint = $this->sprintRepository->find($task->sprint_id);
            $project = $this->projectRepository->find($sprint->project_id);
            
            return view('tasks.comments', compact('task', 'comments', 'sprint', 'project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }
    }

    /**
     * Add a comment to a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $id)
    {
        try {
            $task = $this->taskRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Task not found.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->commentRepository->addCommentToTask($id, Auth::id(), $request->content);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
