<?php

namespace App\Http\Controllers\Web;

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
     * Display a listing of the sprints for a project.
     *
     * @param  int  $projectId
     * @return \Illuminate\View\View
     */
    public function index($projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
            $sprints = $this->sprintRepository->getSprintsByProject($projectId);
            $currentSprint = $this->sprintRepository->getCurrentSprint($projectId);
            
            return view('sprints.index', compact('project', 'sprints', 'currentSprint'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Show the form for creating a new sprint.
     *
     * @param  int  $projectId
     * @return \Illuminate\View\View
     */
    public function create($projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
            return view('sprints.create', compact('project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Store a newly created sprint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sprint = $this->sprintRepository->create([
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'project_id' => $projectId,
        ]);

        return redirect()->route('sprints.show', $sprint->sprint_id)
            ->with('success', 'Sprint created successfully.');
    }

    /**
     * Display the specified sprint.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
            $project = $this->projectRepository->find($sprint->project_id);
            $tasks = $this->sprintRepository->getTasks($id);
            
            // Group tasks by status
            $tasksByStatus = [
                'todo' => $tasks->where('status', 'todo'),
                'in_progress' => $tasks->where('status', 'in_progress'),
                'review' => $tasks->where('status', 'review'),
                'done' => $tasks->where('status', 'done'),
            ];
            
            return view('sprints.show', compact('sprint', 'project', 'tasks', 'tasksByStatus'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }
    }

    /**
     * Show the form for editing the specified sprint.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
            $project = $this->projectRepository->find($sprint->project_id);
            return view('sprints.edit', compact('sprint', 'project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }
    }

    /**
     * Update the specified sprint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Sprint not found.');
        }

        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->sprintRepository->update([
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ], $id);

        return redirect()->route('sprints.show', $id)
            ->with('success', 'Sprint updated successfully.');
    }

    /**
     * Remove the specified sprint from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $sprint = $this->sprintRepository->find($id);
            $projectId = $sprint->project_id;
            
            $this->sprintRepository->delete($id);
            
            return redirect()->route('sprints.index', $projectId)
                ->with('success', 'Sprint deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Error deleting sprint.');
        }
    }
}
