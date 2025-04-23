<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\SprintRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\ListaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    protected $projectRepository;
    protected $sprintRepository;
    protected $userRepository;
    protected $teamRepository;
    protected $listaRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @param SprintRepositoryInterface $sprintRepository
     * @param UserRepositoryInterface $userRepository
     * @param TeamRepositoryInterface $teamRepository
     * @param ListaRepositoryInterface $listaRepository
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        SprintRepositoryInterface $sprintRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        ListaRepositoryInterface $listaRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->sprintRepository = $sprintRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->listaRepository = $listaRepository;
    }

    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = $this->projectRepository->all();
        return response()->json($projects);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project = $this->projectRepository->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Add the current user to the project
        $this->projectRepository->addMember($project->project_id, $request->user()->id);

        return response()->json($project, 201);
    }

    /**
     * Display the specified project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $project = $this->projectRepository->find($id);
            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->projectRepository->update($request->only([
            'name', 'description', 'start_date', 'end_date'
        ]), $id);

        $project = $this->projectRepository->find($id);
        return response()->json($project);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->projectRepository->delete($id);
            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting project'], 500);
        }
    }

    /**
     * Get all members of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMembers($id)
    {
        try {
            $members = $this->projectRepository->getMembers($id);
            return response()->json($members);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }

    /**
     * Add a member to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->projectRepository->addMember($id, $request->user_id);

        return response()->json(['message' => 'Member added successfully']);
    }

    /**
     * Remove a member from the project.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMember($id, $userId)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->projectRepository->removeMember($id, $userId);

        return response()->json(['message' => 'Member removed successfully']);
    }

    /**
     * Get all teams of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeams($id)
    {
        try {
            $teams = $this->projectRepository->getTeams($id);
            return response()->json($teams);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }

    /**
     * Add a team to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTeam(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,team_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->projectRepository->addTeam($id, $request->team_id);

        return response()->json(['message' => 'Team added successfully']);
    }

    /**
     * Remove a team from the project.
     *
     * @param  int  $id
     * @param  int  $teamId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTeam($id, $teamId)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $team = $this->teamRepository->find($teamId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $this->projectRepository->removeTeam($id, $teamId);

        return response()->json(['message' => 'Team removed successfully']);
    }

    /**
     * Get projects by user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectsByUser($userId)
    {
        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $projects = $this->projectRepository->getProjectsByUser($userId);
        return response()->json($projects);
    }

    /**
     * Get projects by team.
     *
     * @param  int  $teamId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectsByTeam($teamId)
    {
        try {
            $team = $this->teamRepository->find($teamId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $projects = $this->projectRepository->getProjectsByTeam($teamId);
        return response()->json($projects);
    }

    /**
     * Get all sprints of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSprints($id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $sprints = $this->sprintRepository->getSprintsByProject($id);
        return response()->json($sprints);
    }

    /**
     * Get current sprint of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentSprint($id)
    {
        try {
            $project = $this->projectRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $sprint = $this->sprintRepository->getCurrentSprint($id);
        
        if (!$sprint) {
            return response()->json(['message' => 'No active sprint found'], 404);
        }
        
        return response()->json($sprint);
    }
}
