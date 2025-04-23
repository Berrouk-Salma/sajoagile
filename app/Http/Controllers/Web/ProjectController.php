<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    protected $projectRepository;
    protected $teamRepository;
    protected $userRepository;
    protected $sprintRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     * @param SprintRepositoryInterface $sprintRepository
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        SprintRepositoryInterface $sprintRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // For regular users, show only their projects
        if (Auth::user()->role !== 'admin') {
            $projects = $this->projectRepository->getProjectsByUser(Auth::id());
        } else {
            // For admins, show all projects
            $projects = $this->projectRepository->all();
        }
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $teams = [];
        
        // For a regular user, only show their teams
        if (Auth::user()->role !== 'admin') {
            $teams = $this->teamRepository->getTeamsByUser(Auth::id());
        } else {
            // For admin, show all teams
            $teams = $this->teamRepository->all();
        }
        
        return view('projects.create', compact('teams'));
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'nullable|exists:teams,team_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = $this->projectRepository->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Add the current user to the project
        $this->projectRepository->addMember($project->project_id, Auth::id());

        // If a team was specified, add it to the project
        if ($request->filled('team_id')) {
            $this->projectRepository->addTeam($project->project_id, $request->team_id);
        }

        return redirect()->route('projects.show', $project->project_id)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $project = $this->projectRepository->find($id);
            $members = $this->projectRepository->getMembers($id);
            $teams = $this->projectRepository->getTeams($id);
            $sprints = $this->sprintRepository->getSprintsByProject($id);
            $currentSprint = $this->sprintRepository->getCurrentSprint($id);
            
            return view('projects.show', compact('project', 'members', 'teams', 'sprints', 'currentSprint'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $project = $this->projectRepository->find($id);
            return view('projects.edit', compact('project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->projectRepository->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ], $id);

        return redirect()->route('projects.show', $id)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->projectRepository->delete($id);
            return redirect()->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Error deleting project.');
        }
    }

    /**
     * Display project members.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showMembers($id)
    {
        try {
            $project = $this->projectRepository->find($id);
            $members = $this->projectRepository->getMembers($id);
            $availableUsers = $this->userRepository->all()->filter(function ($user) use ($members) {
                return !$members->contains('id', $user->id);
            });
            
            return view('projects.members', compact('project', 'members', 'availableUsers'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Add a member to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMember(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->projectRepository->addMember($id, $request->user_id);

        return redirect()->route('projects.members', $id)
            ->with('success', 'Member added successfully.');
    }

    /**
     * Remove a member from the project.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMember($id, $userId)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return redirect()->route('projects.members', $id)
                ->with('error', 'User not found.');
        }

        // Prevent removing the last member
        $members = $this->projectRepository->getMembers($id);
        if ($members->count() <= 1) {
            return redirect()->route('projects.members', $id)
                ->with('error', 'Cannot remove the last member from the project.');
        }

        $this->projectRepository->removeMember($id, $userId);

        return redirect()->route('projects.members', $id)
            ->with('success', 'Member removed successfully.');
    }

    /**
     * Display project teams.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showTeams($id)
    {
        try {
            $project = $this->projectRepository->find($id);
            $projectTeams = $this->projectRepository->getTeams($id);
            
            // Get available teams (teams that are not already in the project)
            $userTeams = [];
            if (Auth::user()->role !== 'admin') {
                $userTeams = $this->teamRepository->getTeamsByUser(Auth::id());
            } else {
                $userTeams = $this->teamRepository->all();
            }
            
            $availableTeams = $userTeams->filter(function ($team) use ($projectTeams) {
                return !$projectTeams->contains('team_id', $team->team_id);
            });
            
            return view('projects.teams', compact('project', 'projectTeams', 'availableTeams'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Add a team to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addTeam(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,team_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->projectRepository->addTeam($id, $request->team_id);

        // Also add all team members to the project
        $teamMembers = $this->teamRepository->getMembers($request->team_id);
        foreach ($teamMembers as $member) {
            // Check if the user is already a member of the project
            $projectMembers = $this->projectRepository->getMembers($id);
            if (!$projectMembers->contains('id', $member->id)) {
                $this->projectRepository->addMember($id, $member->id);
            }
        }

        return redirect()->route('projects.teams', $id)
            ->with('success', 'Team added successfully.');
    }

    /**
     * Remove a team from the project.
     *
     * @param  int  $id
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeTeam($id, $teamId)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        try {
            $team = $this->teamRepository->find($teamId);
        } catch (\Exception $e) {
            return redirect()->route('projects.teams', $id)
                ->with('error', 'Team not found.');
        }

        $this->projectRepository->removeTeam($id, $teamId);

        return redirect()->route('projects.teams', $id)
            ->with('success', 'Team removed successfully.');
    }
}
